<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_search extends MX_Controller {
	private $base_url = null;
	private $view_dir = 'mh_service/item_search/';
	// private $conf_searchad_naver = null;
	//
	public $cids = array(
		'50000000'=>'패션의류',
		'50000001'=>'패션잡화',
		'50000002'=>'화장품/미용',
		'50000003'=>'디지털/가전',
		'50000004'=>'가구/인테리어',
		'50000005'=>'출산/육아',
		'50000006'=>'식품',
		'50000007'=>'스포츠/레저',
		'50000008'=>'생활/건강',
		// '50000009'=>'여가/생활편의',
		// '50000010'=>'면세점',
	);
	public $group_types = array('day'=>'일별','week'=>'주별','month'=>'월별');
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mh_cache');
		$this->mh_cache->use_log_header = true;

		$this->load->library('Mproxy');
		$this->config->load('api_searchad_naver');
		$this->config->load('openapi_naver_com');
		$conf_api_searchad_naver = $this->config->item('api_searchad_naver');
		$conf_openapi_naver_com = $this->config->item('openapi_naver_com');
		$this->load->library('ApiSearchadNaver');
		$this->load->library('ApiOpenApiNaverCom');
		$this->apisearchadnaver->set_account($conf_api_searchad_naver['accounts']['default']);
		$this->apisearchadnaver->set_mproxy($this->mproxy);
		$this->apisearchadnaver->set_mh_cache($this->mh_cache);
		$this->apiopenapinavercom->set_account($conf_openapi_naver_com['accounts']['default']);
		$this->apiopenapinavercom->set_mproxy($this->mproxy);
		$this->apiopenapinavercom->set_mh_cache($this->mh_cache);

	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		// print_r($param);exit;
		$method = isset($param[0][0])?$param[0]:'index';
		if(!method_exists($this,$method)){
			// show_error("지정 메소드({$method})가 없습니다.");
			$method = 'index';
		}
		$this->{$method}($conf,$param);
	}
	public function index($conf,$param){
		// $this->test($conf,$param);
		// $this->keyword($conf,$param);
		// $this->category($conf,$param);
		header('Location: '.$conf['base_url'].'/category');
		header('Location: '.$conf['base_url'].'/keyword');
		exit;
	}
	public function keyword($conf,$param){
		$keyword = $this->input->get('keyword');
		if(!isset($keyword)){ $keyword = '';}

		$search_totals = null;
		$managedKeyword = null;
		$keywordstool = null;
		$datalab_search = null;
		$datalab_shops = null;
		$search_shop_catetories = null;
		if(isset($keyword[0])){
			$search_totals = $this->apiopenapinavercom->v1_search_totals($keyword,'1','1','sim');
			$search_shop = $this->apiopenapinavercom->v1_search_shop_json($keyword,'10','1','sim');
			$search_shop_catetories = $this->apiopenapinavercom->categories_v1_search_shop_json($search_shop);
			// print_r($search_shop_catetories);

			$managedKeywords = $this->apisearchadnaver->ncc_managedKeyword($keyword);
			if(isset($managedKeywords[0])){
				$managedKeyword = $managedKeywords[0]['managedKeyword'];
			}
			$keywordstool = $this->apisearchadnaver->keywordstool($keyword);
			// print_r($keywordstool);exit;
			$search_totals['search'] = $keywordstool['keywordList'][0]['monthlyTotalQcCnt']; //검색수 추가
			$search_totals['competitive_strength'] = $search_totals['shop']/$search_totals['search']; //경쟁강도 추가
			//상위 5개 뽑기
			$keywordstool_topN = array();
			if(isset($keywordstool['keywordList'])){
				for($i=0,$m=5;$i<$m;$i++){
					if(!isset($keywordstool['keywordList'][$i]['relKeyword'])){ break;}
					$keywordstool_topN[] = $keywordstool['keywordList'][$i]['relKeyword'];
				}
			}

			//--- 네이버 검색 데이터 랩용
			$tm = time();
			$startDate=date('Y-m-01',$tm-86400*365*2);
			$endDate=date('Y-m-d',$tm);
			$timeUnit='month'; // date,week,month

			$keywordGroups=array();
			// $keywordGroups=array(array('groupName'=>$keyword,'keywords'=>array($keyword)));
			foreach ($keywordstool_topN as $v) {
				$keywordGroups[]=array('groupName'=>$v,'keywords'=>array($v));
			}
			$device=null;
			$gender=null;
			$ages=null;
			$datalab_search = $this->apiopenapinavercom->v1_datalab_search($startDate,$endDate,$timeUnit,$keywordGroups,$device,$gender,$ages);
			// var_dump($datalab_search);exit;
			$datalab_search = $this->apiopenapinavercom->extend_results($datalab_search);
			// var_dump($datalab_search);exit;
			//--- 네이버 데이터랩 쇼핑
			// $startDate='2019-11-01';
			// $endDate='2020-10-31';
			// $timeUnit='month';
			$category=(string)$search_shop_catetories[0]['cid1'];
			// $keyword='가습기';
			$device=null;
			$gender=null;
			$ages=null;
			$datalab_shops = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_all($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
			// print_r($datalab_shops);
			// exit;
		}


		$this->load->view(
			$this->view_dir.'keyword',
			array(
				'conf'=>$conf,
				'param'=>$param,
				'keyword'=>$keyword,
				'search_totals'=>$search_totals,
				'managedKeyword'=>$managedKeyword,
				'keywordstool'=>$keywordstool,
				'datalab_search'=>$datalab_search,
				'datalab_shops'=>$datalab_shops,
				'search_shop_catetories'=>$search_shop_catetories,
			)
		);
	}
	public function category($conf,$param){
		$this->load->model('mh_service/keyword_rank_naver_model','kr_m');
		$cid = $this->input->get('cid');

		// $date_st = $this->input->get('date_st');
		// if(!$date_st) $date_st = date('Y-m-d',time()-60*60*24*31);
		// $date_ed = $this->input->get('date_ed');
		// if(!$date_ed) $date_ed = date('Y-m-d',time()-60*60*24);

		$date_period = $this->input->get('date_period');
		if(!$date_period) $date_period = 365;

		$date_st = date('Y-m-d',time()-60*60*24*$date_period);
		$date_ed = date('Y-m-d',time()-60*60*24);

		$shw = $this->input->get('shw');
		if(!$shw) $shw = '';
		$shw = trim(preg_replace('/^,+|,+$/','',$shw));
		$group_type = $this->input->get('group_type');
		// if(!$group_type) $group_type = 'day';
		if(!$group_type) $group_type = 'month';
		if($date_period>=365){
			$group_type = 'month';
		}

		$width_dome = 1;

		$period = (strtotime($date_ed) - strtotime($date_st)) / 86400 +1;

		if($period>1000){
			show_error('기간은 최대 1000일까지 설정이 가능합니다.');
		}


		$rowss = null;
		$def_date_array = null;
		$shws = array();
		if($cid){
			if(isset($shw[0])){
				$shws = preg_split('/[\t,]+/',$shw);
				$shws = array_unique($shws);
				$shw = implode(',',$shws);
				// $rowss = $this->kr_m->rows_per_days_extended($this->kr_m->rows_per_days_by_keywords($cid,$date_st,$date_ed,$shws),$date_st,$date_ed);
				$rowss = $this->kr_m->rows_4_group_extended($this->kr_m->rows_4_group_by_keywords($group_type,$cid,$date_st,$date_ed,$shws,$width_dome),$group_type,$date_st,$date_ed);

			}else{
				// $rowss = $this->kr_m->rows_per_days_extended($this->kr_m->rows_per_days($cid,$date_st,$date_ed),$date_st,$date_ed);
				$rowss = $this->kr_m->rows_4_group_extended($this->kr_m->rows_4_group($group_type,$cid,$date_st,$date_ed,$width_dome),$group_type,$date_st,$date_ed);

			}
			$def_date_array = $this->kr_m->array_date_key($group_type,$date_st,$date_ed,'text');


		}
		$this->load->view($this->view_dir.'category',array(
			'conf'=>$conf,
			'param'=>$param,
			'cids'=>$this->cids,
			'cid'=>$cid,
			// 'gdk_date'=>$gdk_date,
			'date_st'=>$date_st,
			'date_ed'=>$date_ed,
			'rowss'=>$rowss,
			'period'=>$period,
			'shw'=>$shw,
			'shws'=>$shws,
			'group_type'=>$group_type,
			'group_types'=>$this->group_types,
			'def_date_array'=>$def_date_array,
			'date_period'=>$date_period,
		));
	}
	public function naver_category($conf,$param){
		$this->load->model('mh_service/keyword_rank_naver_model','kr_m');
		$ca_rows = $this->kr_m->select_naver_shop_category(array(),'*','nsc_id_1,nsc_id_2,nsc_id_3,nsc_id_4');
		// print_r($ca_rows);
		$this->load->view($this->view_dir.'naver_category',array(
			'conf'=>$conf,
			'param'=>$param,
			'ca_rows'=>$ca_rows,
		));
	}
	public function test($conf,$param){
		// $keywords = '가습기,히터';
		// $res = $this->apisearchadnaver->ncc_managedKeyword($keywords);
		// var_dump($res);
		// $res = $this->apisearchadnaver->keywordstool($keywords,'','','','',1);
		// var_dump($res);
		// var_dump($this->conf_searchad_naver);
		// -------------------------
		$startDate='2019-11-01';
		$endDate='2020-10-31';
		$timeUnit='month';
		$category='50000003';
		$keyword='가습기';
		$device=null;
		$gender=null;
		$ages=null;
		$res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_all($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		var_dump($res);exit;
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword=array(array('name'=>'가습기','param'=>array('가습기')),array('name'=>'히터','param'=>array('히터')));
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keywords($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_device($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_gender($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_age($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// exit;
		// -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $keywordGroups=array(array('groupName'=>'가습기','keywords'=>array('가습기')),array('groupName'=>'히터','keywords'=>array('히터')));
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_search($startDate,$endDate,$timeUnit,$keywordGroups,$device,$gender,$ages);
		// var_dump($res);
		// ------------------------- // 네이버 검색
		$query = '가습기';
		// $res = $this->apiopenapinavercom->v1_search_blog_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_cafearticle_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_kin_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_webkr_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_shop_json($query,'1','1','sim');
		// var_dump($res);
		$res = $this->apiopenapinavercom->v1_search_totals($query,'1','1','sim');
		var_dump($res);

		exit;
	}
}
