<?
/**
 * https://developers.google.com/analytics/devguides/reporting/core/v3/reference
 * https://developers.google.com/analytics/devguides/config/mgmt/v3/mgmtReference/?apix=true
 */
class GoogleAnalyticsReportingApiV4
{
	private $mproxy = null;
	public $access_token = '';
	public $url = 'https://analyticsreporting.googleapis.com/v4/reports:batchGet';
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
		$obj = $this->callAndData('get','https://www.googleapis.com/analytics/v3/management/accountSummaries');
		return $obj;
	}
	/*
	// 기본모양
	$posts = array(
		'reportRequests'=>array(
			array(
				'viewId'=>'xxxx',
				'dateRanges'=>array(
						"startDate"=>"2015-11-01",
						"endDate"=>"2015-11-06"
					)
				),
				'metrics'=>array(
					array("expression"=>"ga:users"),
					array("expression"=>"ga:sessions")
				),
				'dimensions'=>array(
					array("name"=>"ga:country"),
					array("name"=>"ga:browser")
				),
				'orderBys'=>array(
					array(
						"fieldName"=> "ga:users",
		 				"sortOrder"=> "DESCENDING"
					),
					array(
						"fieldName"=> "ga:source"
					)
				),
				'samplingLevel'=>'LARGE', //SMALL , LARGE, DEFAULT
				'segments'=>array(
					array(
						"segmentId"=> "gaid::-11"
					)
				),
				'dimensionFilterClauses'=>array(
					'filters'=>array(
						"dimension_name"=>"ga:browser",
						"operator"=>"EXACT",
						"expressions"=>array("Firefox"),
					)
				),
				"filtersExpression"=> "ga:browser==Firefox",
				"includeEmptyRows"=> "true",
				// "pageToken"=>"10000",
		    "pageSize"=>"10000",
		)
	)
	 */
	public function data_ga($posts){
		// https://developers.google.com/analytics/devguides/reporting/core/v4
		$postRaw = pretty_json_encode($posts);
		// echo $postRaw;
		// exit;

		$obj = $this->callAndData('post_json',$this->url,$postRaw);
		return $obj;
	}
	public function extract_rowss($res){
		$rowss = array();
		foreach ($res['reports'] as $k=>$r) {
			$rows = array();
			if(isset($r['data']['rows'])){
				foreach ($r['data']['rows'] as $r2) {
					foreach ($r2['metrics'] as $r3) {
						$rows[] = array_merge($r2['dimensions'],$r3['values']);
					}
				}
			}			
			$rowss[]=$rows;
		}
		return $rowss;
	}
	public function callAndData($method,$url,$posts = null){
			$res = $this->call($method,$url,$posts);
			return json_decode($res['body'],1);
	}
	public function call($method,$url,$posts=null){
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

}
