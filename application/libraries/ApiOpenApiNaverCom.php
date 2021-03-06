<?
/*
https://developers.naver.com/docs/datalab/search/
https://developers.naver.com/docs/search/blog/
*/
class ApiOpenApiNaverCom{
	private $url_api_naver_com = 'https://openapi.naver.com';
	private $mproxy = null;
	private $mh_cache = null;
	private $account = null;
	public $error_exit = true;
	public $cids = array( //우선 1단만
		'50000000'=>'패션의류',
		'50000001'=>'패션잡화',
		'50000002'=>'화장품/미용',
		'50000003'=>'디지털/가전',
		'50000004'=>'가구/인테리어',
		'50000005'=>'출산/육아',
		'50000006'=>'식품',
		'50000007'=>'스포츠/레저',
		'50000008'=>'생활/건강',
		'50000009'=>'여가/생활편의',
		'50000010'=>'면세점',
	);
	public function __construct()
	{
		// $this->mproxy = new Mproxy();
	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function set_mh_cache($mh_cache){
		$this->mh_cache = $mh_cache;
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
		$args = func_get_args();
		$key = __CLASS__.'_'.hash('sha256',serialize($args));
		// $this->mh_cache->use_log_header = 1;
		$res = $this->mh_cache->get($key);
		if(!$res){
			$url = $this->url_api_naver_com.$path;
			$headers = array();
			$headers[] = "X-Naver-Client-Id: ".$this->account['client_id'];
			$headers[] = "X-Naver-Client-Secret: ".$this->account['client_secret'];
			// print_r($posts);
			// var_dump($headers);exit;
			$res = $this->call($method,$url,$posts,$headers);
			$this->mh_cache->save($key,$res,60*60*3);
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
			return json_decode($res['body'],true);
		}
	}
	//==================== 유틸
	// data를 날짜=>값 형식으로 group 이 있다면 날짜=>그룹=>값 형식으로
	public function extend_results($result){
		switch ($result['timeUnit']) {
			case 'month':	$group_type='month';	break;
			// case 'week':	$group_type='week';	break;
			// case 'date':	$group_type='day';	break;
			default: show_error('지원되지 않는 날짜형식');	break;
		}
		$hasGroup = false;
		$dates = array_date_key($group_type,$result['startDate'],$result['endDate'],0);
		// print_r($result['results'][0]['data']);
		$hasGroup = isset($result['results'][0]['data'][0]['group']);
		foreach ($result['results'] as $k => &$r) {
			$new_data = $dates;
			$groups = array();
			foreach ($r['data'] as  $v) {
				if($hasGroup){
					// if(!$hasGroup) $hasGroup = true;
					if(!isset($groups[$v['group']])){
						$groups[$v['group']] = array();
					}
					if(!is_array($new_data[substr($v['period'],0,7)])){
						$new_data[substr($v['period'],0,7)] = array();
					}
					$new_data[substr($v['period'],0,7)][$v['group']]=$v['ratio'];
					$groups[$v['group']][]=$v['ratio'];
				}else{
					$new_data[substr($v['period'],0,7)]=$v['ratio'];
					$groups[]=$v['ratio'];
				}
			}
			$r['data'] = $new_data;
			// $r['groups'] = $groups;

			if($hasGroup){
				$r['sum'] = array();
				// $r['avg'] = array();
				$r['ratio'] = array();
				foreach ($groups as  $group => $groupR) {
					$r['sum'][$group] = array_sum($groupR);
					// $r['avg'][$group] = array_avg($groupR,$r['sum'][$group]);
				}
				$t = array_sum($r['sum']);
				foreach ($r['sum'] as  $group => $sum) {
					$r['ratio'][$group] = $sum/$t;
				}
			}else{
				$r['sum'] = array_sum($groups);
				// $r['avg'] = array_avg($groups,$r['sum']);
				$r['ratio'] = 1;
			}


		}
		return $result;
	}
	//==================== 네이버 데이터랩 쇼핑
	// 4개 한번에
	public function v1_datalab_shopping_category_keyword_all($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		$rs = array();
		$i_keyword = array(array('name'=>$keyword,'param'=>array($keyword)));

		$d = $this->extend_results($this->v1_datalab_shopping_category_keywords($startDate,$endDate,$timeUnit,$category,$i_keyword,$device,$gender,$ages));
		if(!isset($d['startDate'])){
			return null;
		}
		$rs['startDate']=$d['startDate'];
		$rs['endDate']=$d['endDate'];
		$rs['timeUnit']=$d['timeUnit'];
		$rs['results']=array();
		$rs['results']['keywords']=$d['results'];
		$d = $this->extend_results($this->v1_datalab_shopping_category_keyword_device($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages));
		$d['results'][0]['ratio'] = array_merge(array('mo'=>0,'pc'=>0),$d['results'][0]['ratio']);
		$rs['results']['device']=$d['results'];
		$d = $this->extend_results($this->v1_datalab_shopping_category_keyword_gender($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages));
		$d['results'][0]['ratio'] = array_merge(array('f'=>0,'m'=>0),$d['results'][0]['ratio']);
		$rs['results']['gender']=$d['results'];
		$d = $this->extend_results($this->v1_datalab_shopping_category_keyword_age($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages));
		// var_dump($d['results'][0]['ratio']);exit;
		// $d['results'][0]['ratio'] = array_merge(array('10'=>0,'20'=>0,'30'=>0,'40'=>0,'50'=>0,'60'=>0),$d['results'][0]['ratio']);
		$d['results'][0]['ratio'] = $d['results'][0]['ratio']+array('10'=>0,'20'=>0,'30'=>0,'40'=>0,'50'=>0,'60'=>0);
		// ksort($d['results'][0]['ratio']);
		// var_dump(array(10=>0,20=>0,30=>0,40=>0,50=>0,60=>0));
		// var_dump($d['results'][0]['ratio']);exit;
		$rs['results']['age']=$d['results'];
		// print_r($rs);exit;
		return $rs;
	}
	public function v1_datalab_shopping_category_keyword_call($service,$startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		$path = '/v1/datalab/shopping/category/'.$service;
		$arr = array();
		$arr['startDate'] = $startDate;
		$arr['endDate'] = $endDate;
		$arr['timeUnit'] = $timeUnit;
		$arr['category'] = $category;
		$arr['keyword'] = $keyword; // JSON 구조 "keyword": [{"name": "패션의류/정장", "param": ["정장"]}, {"name": "패션의류/비지니스 캐주얼", "param": ["비지니스 캐주얼"]}]
		if(isset($arr['device'])){ $arr['device'] = $device; }
		if(isset($arr['gender'])){ $arr['gender'] = $gender; }
		if(isset($arr['ages'])){ $arr['ages'] = $ages; }
		$postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('post_json',$path,$postRaw);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keywords($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		//$keyword; // JSON 구조 "keyword": [{"name": "패션의류/정장", "param": ["정장"]}, {"name": "패션의류/비지니스 캐주얼", "param": ["비지니스 캐주얼"]}]
		return $this->v1_datalab_shopping_category_keyword_call('keywords',$startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EA%B8%B0%EA%B8%B0%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_device($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		return $this->v1_datalab_shopping_category_keyword_call('keyword/device',$startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EC%84%B1%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_gender($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		return $this->v1_datalab_shopping_category_keyword_call('keyword/gender',$startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
	}
	// https://developers.naver.com/docs/datalab/shopping/#%EC%87%BC%ED%95%91%EC%9D%B8%EC%82%AC%EC%9D%B4%ED%8A%B8-%ED%82%A4%EC%9B%8C%EB%93%9C-%EC%97%B0%EB%A0%B9%EB%B3%84-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_shopping_category_keyword_age($startDate='',$endDate='',$timeUnit='month',$category='',$keyword='',$device=null,$gender=null,$ages=null){
		return $this->v1_datalab_shopping_category_keyword_call('keyword/age',$startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
	}
	//====  네이버 데이터랩 검색
	// https://developers.naver.com/docs/datalab/search/#%EB%84%A4%EC%9D%B4%EB%B2%84-%ED%86%B5%ED%95%A9-%EA%B2%80%EC%83%89%EC%96%B4-%ED%8A%B8%EB%A0%8C%EB%93%9C-%EC%A1%B0%ED%9A%8C
	public function v1_datalab_search($startDate='',$endDate='',$timeUnit='month',$keywordGroups='',$device=null,$gender=null,$ages=null){
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

	// ======================================-=============== 네이버 검색용
	// 네이버 검색: 호출용
	public function v1_search_call_json($service,$query,$display='10',$start='1',$sort='sim'){
		$path = '/v1/search/'.$service.'.json';
		$arr = array();
		$arr['query'] = $query;
		$arr['display'] = $display;
		$arr['start'] = $start;
		$arr['sort'] = $sort;
		$qstr = http_build_query($arr);
		// $postRaw = pretty_json_encode($arr);
		// print_r($postRaw);
		return $this->call_api('GET',$path.'?'.$qstr,null);
	}
	// 한번에 5번 호출한다. 사용에 주의하라.
	public function v1_search_totals($query,$display='10',$start='1',$sort='sim'){
		$totals = array(
			'blog'=>0,
			'cafearticle'=>0,
			'kin'=>0,
			'webkr'=>0,
			'shop'=>0,
		);
		foreach ($totals as $k => $v) {
			$res = $this->v1_search_call_json($k,$query,$display,$start,$sort);
			$totals[$k]=$res['total'];
		}
		return $totals;

	}
	// 네이버 검색: 블로그
	// https://developers.naver.com/docs/search/blog/
	public function v1_search_blog_json($query,$display='10',$start='1',$sort='sim'){
		return $this->v1_search_call_json('blog',$query,$display,$start,$sort);
	}
	// 네이버 검색: 카페
	// https://developers.naver.com/docs/search/cafearticle/
	public function v1_search_cafearticle_json($query,$display='10',$start='1',$sort='sim'){
		return $this->v1_search_call_json('cafearticle',$query,$display,$start,$sort);
	}
	// 네이버 검색: 지식인
	// https://developers.naver.com/docs/search/kin/
	public function v1_search_kin_json($query,$display='10',$start='1',$sort='sim'){
		return $this->v1_search_call_json('kin',$query,$display,$start,$sort);
	}
	// 네이버 검색: 웹문서
	// https://developers.naver.com/docs/search/web/
	public function v1_search_webkr_json($query,$display='10',$start='1',$sort='sim'){
		return $this->v1_search_call_json('webkr',$query,$display,$start,$sort);
	}
	// 네이버 검색: 쇼핑
	// https://developers.naver.com/docs/search/shopping/
	public function v1_search_shop_json($query,$display='10',$start='1',$sort='sim'){
		return $this->v1_search_call_json('shop',$query,$display,$start,$sort);
	}
	public function categories_v1_search_shop_json($results){
		if(!isset($results['items'][0])){ return null;}
		$catetories = array();
		foreach ($results['items'] as $key => $r) {
			$cid_names = array(
				'category1'=>$r['category1'],
				'category2'=>$r['category2'],
				'category3'=>$r['category3'],
				'category4'=>$r['category4'],
			);
			$md5 = md5(serialize($cid_names));
			if(!isset($catetories[$md5])){
				$cid_names['cid1'] = array_search($r['category1'],$this->cids);
				$catetories[$md5] = $cid_names;
			}
		}
		return array_values($catetories);
	}



}
