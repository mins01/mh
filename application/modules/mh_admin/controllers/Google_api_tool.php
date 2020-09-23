<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_api_tool extends MX_Controller {
	private $client = null;

	public function __construct($conf=array())
	{

		$this->load->library('Mh_cache');
		$this->load->library('Mproxy');
		$this->config->load('google_oauth2');
		// $this->bbs_conf = ;
		$this->load->library('GoogleOAuth2');

		$conf_google_oauth2 = $this->config->item('google_oauth2');
		$this->googleoauth2->set_mproxy($this->mproxy);
		$this->client =& $this->googleoauth2->client;
		$this->googleoauth2->set_client($conf_google_oauth2['clients']['default']);
		$this->googleoauth2->set_access_token($conf_google_oauth2['access_tokens']['analytics.readonly']);
		$this->cache_key = 'analytics.readonly';


	}

		// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		// print_r($param);exit;
		$method = isset($param[0][0])?$param[0]:'index';
		if(!method_exists($this,$method)){
			show_error("지정 메소드가 없습니다.");
		}
		$this->{$method}($conf,$param);
	}


	public function index($conf,$param){
		$this->default_view($conf,$param);
	}
	public function default_view($conf,$param){
		// $url = "{$ENDPOINT}?client_id={$this->CLIENT_ID}&response_type=code&scope={$this->SCOPE}&access_type=offline&redirect_uri=urn:ietf:wg:oauth:2.0:oob";
		// echo '<a href="'.$url.'" target="_blank">인증코드 받기</a>';

		$process = $this->input->post('process');
		$method = 'process_'.$process;
		if(method_exists($this,$method)){
			$this->{$method}($conf,$param);
			return;
		}


		$this->load->view(
			'mh_admin/google_api_tool/default_view',
			array(
				'conf'=>$conf,
				'param'=>$param,
				'client'=>$this->googleoauth2->client,
				'access_token'=>$this->googleoauth2->access_token,
			)
		);
	}
	public function process_authorization_code($conf,$param){
		// $CODE = '4/4QF3fKrHBAdXqzPWfVYim6KwapBLai9mosNk9VUSJCQ7FkHJFcAd3xk';
		$req = array(
			'code'=>$this->input->post('code'),
			'client_id'=>$this->googleoauth2->client['client_id'],
			'client_secret'=>$this->googleoauth2->client['client_secret'],
			'redirect_uri'=>$this->input->post('redirect_uri'),
		);
		$res = $this->googleoauth2->authorization_code($req);

		header('Content-Type: application/json');
		echo $res['body'];
		exit;

	}
	public function process_refresh_token($conf,$param){
		// $CODE = '4/4QF3fKrHBAdXqzPWfVYim6KwapBLai9mosNk9VUSJCQ7FkHJFcAd3xk';
		$req = array(
			'code'=>$this->input->post('code'),
			'client_id'=>$this->googleoauth2->client['client_id'],
			'client_secret'=>$this->googleoauth2->client['client_secret'],
			'refresh_token'=>$this->input->post('refresh_token'),
		);
		$res = $this->googleoauth2->refresh_token($req);

		header('Content-Type: application/json');
		echo $res['body'];
		exit;

	}
	public function process_refreshed_access_token($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token();
		header('Content-Type: text/plain');
		print_r($access_token);
		// header('Content-Type: application/json');
		// echo $res['body'];
		exit;

	}
	public function get_access_token(){
		$key = $this->cache_key;
		// $this->mh_cache->use_log_header = 1;
		$access_token = $this->mh_cache->get($key);
		if(!$access_token){
			$access_token = $this->googleoauth2->refreshed_access_token();
			if(!isset($access_token['access_token'][5])){
				show_error('Wrong access_token 1');
			}
			$this->mh_cache->save($key,$access_token,60*50);
		}
		if(!isset($access_token['access_token'][5])){
			show_error('Wrong access_token 2');
		}
		return $access_token['access_token'];
	}

	public function analytics_acounts($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		header('Content-Type: application/json');

		$this->load->library('GoogleAnalyticsApi');
		$this->googleanalyticsapi->set_mproxy($this->mproxy);
		// $this->googleanalyticsapi->set_access_token($access_token['access_token']);
		$this->googleanalyticsapi->set_access_token($this->get_access_token());
		// $res = $this->googleanalyticsapi->accountSummaries();
		$res = $this->googleanalyticsapi->accountSummaries();
		print_r($res);
		exit;
	}

	public function analytics_test($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		header('Content-Type: application/json');

		$this->load->library('GoogleAnalyticsApi');
		$this->googleanalyticsapi->set_mproxy($this->mproxy);
		$this->googleanalyticsapi->set_access_token($access_token['access_token']);
		// $res = $this->googleanalyticsapi->accountSummaries();
		//-- ex
		$profileId = '54658549'; //Lee Minsu/mins01.com/homepage
		// ga:searchResultViews //검색수
		// ga:searchUniques //유니크 검색수
		// *pageviewsPerSearch = ga:searchResultViews / ga:searchUniques //검색당 굘과 페이지뷰수
		// ga:searchExitRate // 검색 후 종료율
		// ga:percentSearchRefinements //재검색율
		// ga:avgSearchDuration //검색후 시간
		// ga:avgSearchDepth //평균검색심도
		$gets = array(
			'ids'=> 'ga:'.$profileId,
	    // 'start-date'=> 'yesterday',
	    // 'end-date'=> 'today',
			'start-date'=> '2daysAgo',
			'end-date'=> 'yesterday',
			// 검색어용
			'dimensions'=> 'ga:searchKeyword,ga:date',
			'metrics'=> 'ga:searchResultViews,ga:searchUniques,ga:searchExitRate,ga:percentSearchRefinements,ga:avgSearchDuration,ga:avgSearchDepth',
			'sort'=> '-ga:searchUniques',
			// 일반 페이지용
			// 'dimensions'=> 'ga:pagePath,ga:date',
			// 'metrics'=> 'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage,ga:entrances,ga:bounceRate,ga:exitRate',
			// 'sort'=> '-ga:pageviews',
			// 필터
			// 'filters'=>'ga:pagePath=~^/main/item/itemList.php?'
			'max-results'=> 10
		);
		$res = $this->googleanalyticsapi->data_ga($gets);

		print_r($res);
		exit;
	}







}
