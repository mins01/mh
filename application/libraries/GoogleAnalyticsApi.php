<?
/**
 * https://developers.google.com/analytics/devguides/reporting/core/v3/reference
 * https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/?apix=true
 */
class GoogleAnalyticsApi
{
	private $mproxy = null;
	public $access_token = '';
	public $prefix_url = 'https://www.googleapis.com/analytics/v3';
	public function __construct()
	{
		// $this->mproxy = new Mproxy();
	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function set_access_token($access_token){
		$this->access_token = $access_token;
		// print_r($this->access_token);
	}
	public function accountSummaries(){
		$obj = $this->callAndData('get','/management/accountSummaries');
		return $obj;
	}
	public function data_ga($gets){
		//https://developers.google.com/analytics/devguides/reporting/core/v3/reference
		$qstr = http_build_query($gets);
		$obj = $this->callAndData('get','/data/ga?'.$qstr);
		return $obj;
	}
	public function callAndData($method,$url,$posts = null){
			$res = $this->call($method,$this->prefix_url.$url);
			return json_decode($res['body'],1);
	}
	public function call($method,$url,$posts = null){
		// $url = $this->client['token_uri']; // https://oauth2.googleapis.com/token

		$opts = array();
		$opts[CURLOPT_SSL_VERIFYPEER]=false;
		$opts[CURLOPT_SSL_VERIFYHOST]=false;
		$opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; //HTTP 1.1 사용
		$opts[CURLOPT_FAILONERROR] = false;

		$headers = array();
		$headers['authorization']="Bearer {$this->access_token}";
		$cookieRaw = '';
		// print_r($postRaw);
		set_time_limit(120);// 동작 타임아웃
		$this->mproxy->conn_timeout = 60;
		$this->mproxy->exec_timeout = 60;

		if(isset($posts)){
			$postRaw = http_build_query($posts);
			$res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);
		}else{
			$res = $this->mproxy->get($url,$cookieRaw,$headers, $opts);
		}
		return $res;
	}

}
