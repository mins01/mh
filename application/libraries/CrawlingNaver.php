<?
/*
https://developers.naver.com/docs/datalab/search/
https://developers.naver.com/docs/search/blog/
*/
class CrawlingNaver{
	private $mproxy = null;
	private $mh_cache = null;
	public $error_exit = true;
	public $use_proxy = 0;
	public $proxy_urls = null;
	public function __construct()
	{
		$this->proxy_urls = array('','http://inno.domeggook.com/ex/php_Mproxy/src/proxy.php','https://www.miraeassetstore.com/ex/php_Mproxy/src/proxy.php');
	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function set_mh_cache($mh_cache){
		$this->mh_cache = $mh_cache;
	}
	public function proxy($method,$url,$posts=null,$headers=array()){
		$proxy_url = $this->proxy_urls[$this->use_proxy];
		$headers['X-Url'] = $url;
		$headers['X-Conn-Timeout'] = 120;
		$headers['X-Exec-Timeout'] = 120;
		return $this->call($method,$proxy_url,$posts,$headers);
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
	public function call_crawling($method,$url,$posts=null){
		$args = func_get_args();
		$key = __CLASS__.'_'.hash('sha256',serialize($args));
		// $this->mh_cache->use_log_header = 1;
		$res = $this->mh_cache->get($key);
		if(!$res){
			if(!$this->use_proxy){
				$res = $this->call('get',$url);
			}else{
				$res = $this->proxy('get',$url);
			}

			$this->mh_cache->save($key,$res);
		}

		if($res['httpcode']!=200){
			echo __METHOD__,"\n";
			var_dump(func_get_args());
			var_dump($res);
			if($this->error_exit){
				exit;
			}else{
				return null;
			}

		}
		if($res['errorno']==0){
			return $res['body'];
		}
	}
	public function crawling_shop_by_keyword($keyword)
	{
		$enc_keyword = urlencode($keyword);
		$url = 'https://search.shopping.naver.com/search/all?query='.$enc_keyword.'&cat_id=&frm=NVSHATC';
		$body = $this->call_crawling('get',$url);
		if($body==null){
			return 0;
		}
		// header('Content-Type: text/plain');
		// print_r($body);
		// $xml = simplexml_load_string($body , null  , LIBXML_NOCDATA|LIBXML_HTML_NOIMPLIED|LIBXML_HTML_NODEFDTD |LIBXML_NOERROR );
		// $xml = simplexml_load_string($body , null  );
		// $xml->xpath
		try{
			$document = new DOMDocument();
			$document->loadXml($body);
		}catch(Exception $e){
			// echo $body;
			return 0;
		}

		$xpath = new DOMXpath($document);
		$total = $xpath->evaluate('string((//span[@class="subFilter_num__2x0jq"])[1])');
		if(!isset($total[0])){
			$total = 0;
		}else{
			$total = (int)preg_replace('/[^\d]/','',$total);
		}
		return $total;
	}
}
