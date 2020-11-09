<?
/*
https://naver.github.io/searchad-apidoc/#/guides
 */
class ApiSearchadNaver{
	private $url_api_naver_com = 'https://api.naver.com';
	private $mproxy = null;
	private $account = null;
	public function __construct()
	{
		// $this->mproxy = new Mproxy();
	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function set_account($account){
		$this->account = $account;
		// var_dump($this->account);
	}
	public function call($method,$url,$posts=null,$headers=array()){
		// $url = $this->client['token_uri']; // https://oauth2.googleapis.com/token

		$opts = array();
		$opts[CURLOPT_SSL_VERIFYPEER]=false;
		$opts[CURLOPT_SSL_VERIFYHOST]=false;
		$opts[CURLOPT_HTTP_VERSION] = CURL_HTTP_VERSION_1_1; //HTTP 1.1 사용
		$opts[CURLOPT_FAILONERROR] = false;

		$cookieRaw = '';
		// print_r($postRaw);
		set_time_limit(120);// 동작 타임아웃
		$this->mproxy->conn_timeout = 60;
		$this->mproxy->exec_timeout = 60;
		if($method=='post_json'){
			$postRaw =& $posts;
			$headers['content-type']="application/json";
			$res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);
		}else if(isset($postRaw)){
			$postRaw = http_build_query($posts);
			$res = $this->mproxy->post($url,$postRaw,$cookieRaw,$headers, $opts);
		}else{
			$res = $this->mproxy->get($url,$cookieRaw,$headers, $opts);
		}
		return $res;
	}
	protected function getTimestamp()
	{
			return round(microtime(true) * 1000);
	}
	protected function generateSignature($timestamp, $method, $path)
	{
			$sign = $timestamp . "." . $method . "." . $path;
			// echo "sign = " . $sign . "\n";
			$signature = hash_hmac('sha256', $sign, $this->account['secret_key'], true);
			return base64_encode($signature);
	}
	public function call_api($method,$path,$qstr,$posts=null){
		$url = $this->url_api_naver_com.$path.$qstr;
		$headers = array();
		$timestamp = $this->getTimestamp();
		$headers[] = "X-Timestamp: ".$timestamp;
		$headers[] = "X-API-KEY: ".$this->account['access_license'];
		$headers[] = "X-Customer: ".$this->account['customer_id'];
		$signature =  $this->generateSignature($timestamp, $method, $path);
		$headers[] = "X-Signature: ".$signature;
		$res = $this->call('get',$url,$posts,$headers);
		if($res['errorno']==0){
			return json_decode($res['body'],true);
		}
	}

	//https://naver.github.io/searchad-apidoc/#/operations/GET/~2Fncc~2FmanagedKeyword%7B%3Fkeywords%7D
	/**
	 * 키워드 정보 검색
	 * @param  [type] $keywords ,로 구분한 최대 5단어
	 * @return [type]           [description]
	 */
	public function ncc_managedKeyword($keywords){
		$path = '/ncc/managedKeyword';
		$qstr = '?keywords='.urlencode($keywords);
		return $this->call_api('GET',$path,$qstr,null);
	}

	// https://naver.github.io/searchad-apidoc/#/operations/GET/~2Fkeywordstool
	/**
	* 연관키워드
	* @param  string $hintKeywords 힌트 키워드 (, 구분 5개까지)
	* @param  string $siteId       웹사이트
	* @param  string $biztpId      업종
	* @param  string $event        시즌 테마
	* @param  string $month        월 (1~12)
	* @param  string $showDetail   [description]
	* @return [type]               [description]
 	*/
	public function keywordstool($hintKeywords,$siteId='',$biztpId='',$event='',$month='',$showDetail='1'){
		$path = '/keywordstool';
		$qs = array();
		$qs['hintKeywords']=$hintKeywords;
		$qs['siteId']=$siteId;
		$qs['biztpId']=$biztpId;
		$qs['event']=$event;
		$qs['month']=$month;
		$qs['showDetail']=$showDetail;
		$qstr = '?'.http_build_query($qs);
		return $this->call_api('GET',$path,$qstr,null);
	}
}
