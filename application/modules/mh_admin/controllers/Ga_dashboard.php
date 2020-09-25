<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ga_dashboard extends MX_Controller {
	private $client = null;
	private $profileId = null;
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

		$this->load->library('GoogleAnalyticsApi');
		$this->googleanalyticsapi->set_mproxy($this->mproxy);

		$this->profileId = $conf['menu']['mn_arg1'];
		if(!isset($this->profileId[0])){
			show_error('mn_arg1(profileId) is empty.');
		}
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
		$this->dashboard($conf,$param);
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

	public function dashboard($conf,$param){
		$key='ga_dashboard';
		$rowss = $this->mh_cache->get($key);
		if(!$rowss){
			$rowss = $this->get_ga_rowss();
			$this->mh_cache->save($key,$rowss,60*10); //10분 캐시
		}

		// print_r($rowss);
		// echo 'dashboard';

		$this->load->view(
			'mh_admin/ga_dashboard/dashboard',
			array(
				'conf'=>$conf,
				'param'=>$param,
				'rowss'=>$rowss,
			)
		);
	}

	private function get_ga_rowss(){
		$rowss = array();
		$access_token = $this->get_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		$this->googleanalyticsapi->set_access_token($access_token);

		$profileId = $this->profileId;
		$res = array('rows'=>array());
		//--- 검색어 7일간 TOP10
		$gets = array(
			'ids'=> 'ga:'.$profileId,
			'start-date'=> '14daysAgo',
			'end-date'=> 'today',
			// 검색어용
			'dimensions'=> 'ga:searchKeyword,ga:pagePath',
			'metrics'=> 'ga:searchResultViews,ga:searchUniques',  //PV,UPV
			'sort'=> '-ga:searchUniques',
			'max-results'=> 10
		);
		$res = $this->googleanalyticsapi->data_ga($gets);
		$rowss['searchs'] = $res['rows'];
		$rowss['total_search'] = $res['totalsForAllResults'];

		//--- Page 7일간 TOP10
		$gets = array(
			'ids'=> 'ga:'.$profileId,
			'start-date'=> '14daysAgo',
			'end-date'=> 'today',
			// 검색어용
			'dimensions'=> 'ga:pagePath,ga:pageTitle',
			'metrics'=> 'ga:pageviews,ga:uniquePageviews',  //PV,UPV
			'sort'=> '-ga:uniquePageviews',
			'max-results'=> 10
		);
		$res = $this->googleanalyticsapi->data_ga($gets);
		$rowss['pages'] = $res['rows'];
		// print_r($res);exit;
		$rowss['total_page'] = $res['totalsForAllResults'];

		//--- 방문자 7일간 TOP10
		$gets = array(
			'ids'=> 'ga:'.$profileId,
			'start-date'=> '14daysAgo',
			'end-date'=> 'today',
			// 검색어용
			// 'dimensions'=> 'ga:userDefinedValue',
			// 'dimensions'=> 'ga:userType',
			// 'dimensions'=> 'ga:userGender,ga:userAgeBracket',
			'dimensions'=> 'ga:date',
			// 'metrics'=> 'ga:users,ga:newUsers,ga:percentNewSessions,ga:1dayUsers,ga:7dayUsers,ga:14dayUsers,ga:28dayUsers,ga:sessionsPerUser',  //PV,UPV
			'metrics'=> 'ga:users,ga:newUsers',  //방문자,신규방문자
			// 'sort'=> '-ga:uniquePageviews',
			'max-results'=> 10
		);
		$res = $this->googleanalyticsapi->data_ga($gets);
		// print_r($res);
		// exit;
		$rowss['users'] = $res['rows'];
		$rowss['total_user'] = $res['totalsForAllResults'];
		return $rowss;
	}



	public function analytics_test($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		header('Content-Type: application/json');

		$this->load->library('GoogleAnalyticsApi');
		$this->googleanalyticsapi->set_mproxy($this->mproxy);
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
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
