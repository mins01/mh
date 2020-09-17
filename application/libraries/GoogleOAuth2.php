<?
require_once(dirname(__FILE__).'/Mproxy.php');
/**
 *
 */


class GoogleOAuth2
{
	private $mproxy = null;
	public $client = array (
		'client_id' => '',
		'project_id' => '',
		'auth_uri' => 'https://accounts.google.com/o/oauth2/auth',
		'token_uri' => 'https://oauth2.googleapis.com/token',
		'auth_provider_x509_cert_url' => 'https://www.googleapis.com/oauth2/v1/certs',
		'client_secret' => '',
		'redirect_uris' =>
		array (
			0 => 'urn:ietf:wg:oauth:2.0:oob',
			1 => 'http://localhost',
		),
	);
	public $access_token =  array (
		'access_token' => '',
		'expires_in' => 0,
		'refresh_token' => '',
		'scope' => '',
		'token_type' => '',
	);
	public function __construct()
	{
		// $this->mproxy = new Mproxy();
	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function set_client($client){
		$this->client = array_merge($this->client,$client);
		// print_r($this->client);
	}
	public function set_access_token($access_token){
		$this->access_token = array_merge($this->access_token,$access_token);
		// print_r($this->access_token);
	}
	//-------
	public function authorization_code($req){
		$url = $this->client['token_uri']; // https://oauth2.googleapis.com/token

		$opts = array();
		$opts[CURLOPT_SSL_VERIFYPEER]=false;
		$opts[CURLOPT_SSL_VERIFYHOST]=false;
		$opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; //HTTP 1.1 사용
		$opts[CURLOPT_FAILONERROR] = false;

		$posts = array();
		$posts['code']=$req['code'];
		$posts['client_id']=$req['client_id'];
		$posts['client_secret']=$req['client_secret'];
		$posts['redirect_uri']=$req['redirect_uri'];
		// $posts['redirect_uri']='http://localhost/oauth2callback';
		// $posts['redirect_uri'] = '';
		$posts['grant_type']='authorization_code';
		$postRaw = http_build_query($posts);
		$headers = array();
		$cookieRaw = '';
		// print_r($postRaw);
		set_time_limit(120);// 동작 타임아웃
		$this->mproxy->conn_timeout = 60;
		$this->mproxy->exec_timeout = 60;
		$res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);
		// print_r($res);
		return $res;
	}

	public function refresh_token($req){
		$url = $this->client['token_uri']; // https://oauth2.googleapis.com/token

		$opts = array();
		$opts[CURLOPT_SSL_VERIFYPEER]=false;
		$opts[CURLOPT_SSL_VERIFYHOST]=false;
		$opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; //HTTP 1.1 사용
		$opts[CURLOPT_FAILONERROR] = false;

		$posts = array();
		$posts['client_id']=$req['client_id'];
		$posts['client_secret']=$req['client_secret'];
		$posts['refresh_token']=$req['refresh_token'];
		$posts['grant_type']='refresh_token';
		$postRaw = http_build_query($posts);
		$headers = array();
		$cookieRaw = '';
		// print_r($postRaw);
		set_time_limit(120);// 동작 타임아웃
		$this->mproxy->conn_timeout = 60;
		$this->mproxy->exec_timeout = 60;
		$res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);

		return $res;
	}

	public function refreshed_access_token(){
		$req = array();
		$req['client_id']=$this->client['client_id'];
		$req['client_secret']=$this->client['client_secret'];
		$req['refresh_token']=$this->access_token['refresh_token'];
		$res = $this->refresh_token($req);
		return $res;
	}
}

// https://stackoverflow.com/questions/53357741/how-to-perform-oauth-2-0-using-the-curl-cli
// https://developers.google.com/youtube/v3/guides/auth/server-side-web-apps?hl=ko
/*
	https://accounts.google.com/o/oauth2/token

	POST /o/oauth2/token HTTP/1.1
	Host: accounts.google.com
	Content-Type: application/x-www-form-urlencoded

	code=4/ux5gNj-_mIu4DOD_gNZdjX9EtOFf&
	client_id=1084945748469-eg34imk572gdhu83gj5p0an9fut6urp5.apps.googleusercontent.com&
	client_secret=hDBmMRhz7eJRsM9Z2q1oFBSe&
	redirect_uri=http://localhost/oauth2callback&
	grant_type=authorization_code

	curl ^
	--data client_id=%CLIENT_ID% ^
	--data client_secret=%CLIENT_SECRET% ^
	--data code=%AUTH_CODE% ^
	--data redirect_uri=urn:ietf:wg:oauth:2.0:oob ^
	--data grant_type=authorization_code ^
	https://www.googleapis.com/oauth2/v4/token
 */
