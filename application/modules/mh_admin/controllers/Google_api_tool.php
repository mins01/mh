<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Google_api_tool extends MX_Controller {
	private $client = null;

	public function __construct($conf=array())
	{

		// $this->load->library('Mproxy');
		$this->config->load('google_oauth2');
		// $this->bbs_conf = ;

		$this->load->library('GoogleOAuth2');
		$conf_google_oauth2 = $this->config->item('google_oauth2');
		$this->client =& $this->googleoauth2->client;
		$this->googleoauth2->set_client($conf_google_oauth2['clients']['default']);
		$this->googleoauth2->set_access_token($conf_google_oauth2['access_tokens']['analytics.readonly']);

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
	public function refresh_token($conf,$param){
		// https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps?hl=ko
		/*
			POST /o/oauth2/token HTTP/1.1
			Host: accounts.google.com
			Content-Type: application/x-www-form-urlencoded

			client_id=21302922996.apps.googleusercontent.com&
			client_secret=XTHhXh1SlUNgvyWGwDk1EjXB&
			refresh_token=1/6BMfW9j53gdGImsixUH6kU5RsR4zwI9lUVX-tqf8JXQ&
			grant_type=refresh_token
		 */
		 $url = $this->oauth2_token_url;

		 $opts = array();
		 $opts[CURLOPT_SSL_VERIFYPEER]=false;
		 $opts[CURLOPT_SSL_VERIFYHOST]=false;
		 $opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; //HTTP 1.1 사용
		 $opts[CURLOPT_FAILONERROR] = false;

		 $posts = array();
		 $posts['client_id']=$this->client_id;
		 $posts['client_secret']=$this->client_secret;
		 $posts['refresh_token']=$this->refresh_token;
		 $posts['grant_type']='refresh_token';
		 $postRaw = http_build_query($posts);
		 $headers = array();
		 $cookieRaw = '';
		 // print_r($postRaw);
		 set_time_limit(120);// 동작 타임아웃
		 $this->mproxy->conn_timeout = 60;
		 $this->mproxy->exec_timeout = 60;
		 $res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);
		 print_r($res);
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
		$res = $this->googleoauth2->refreshed_access_token();

		header('Content-Type: application/json');
		echo $res['body'];
		exit;

	}







}
