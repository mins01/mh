<?
/*
https://naver.github.io/searchad-apidoc/#/guides
 */
class ApiOpenApiNaverCom{
	private $url_api_naver_com = 'https://openapi.naver.com';
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
	public function call_api($method,$path,$posts=null){
		$url = $this->url_api_naver_com.$path;
		$headers = array();
		$headers[] = "X-Naver-Client-Id: ".$this->account['client_id'];
		$headers[] = "X-Naver-Client-Secret: ".$this->account['client_secret'];
		$res = $this->call($method,$url,$posts,$headers);
		// var_dump($res);
		if($res['errorno']==0){
			return json_decode($res['body'],true);
		}
	}

	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keywords($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device='',$gender='',$ages=''){
		$path = '/v1/datalab/shopping/category/keywords';
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['category'] = $category;
		$arr['keyword'] = $keyword;
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EA%B8%B0%EA%B8%B0%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_device($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device='',$gender='',$ages=''){
		$path = '/v1/datalab/shopping/category/keyword/device';
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['category'] = $category;
		$arr['keyword'] = $keyword;
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EC%84%B1%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_gender($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device='',$gender='',$ages=''){
		$path = '/v1/datalab/shopping/category/keyword/gender';
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['category'] = $category;
		$arr['keyword'] = $keyword;
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EC%97%B0%EB%A0%B9%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_age($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device='',$gender='',$ages=''){
		$path = '/v1/datalab/shopping/category/keyword/age';
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['category'] = $category;
		$arr['keyword'] = $keyword;
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
	// https://developers.naver.com/docs/datalab/search/#%EB%84%A4%EC%9D%B4%EB%B2%84-%ED%86%B5%ED%95%A9-%EA%B2%80%EC%83%89%EC%96%B4-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_search($startDate='',$endDate='',$timeUnit='month',$keywordGroups='',$device='',$gender='',$ages=''){
		$path = '/v1/datalab/search';
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['keywordGroups'] = $keywordGroups;
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
}
