<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_search extends MX_Controller {
	private $base_url = null;
	private $view_dir = 'mh_admin/item_search/';
	// private $conf_searchad_naver = null;
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
			// show_error("지정 메소드가 없습니다.");
			$method = 'index';
		}
		$this->{$method}($conf,$param);
	}
	public function index($conf,$param){
		// $this->test($conf,$param);
		$this->keyword($conf,$param);
	}
	public function keyword($conf,$param){
		$keyword = $this->input->get('keyword');
		if(!isset($keyword)){ $keyword = '';}

		$search_totals = null;
		$managedKeyword = null;
		$keywordstool = null;
		if(isset($keyword[0])){
			$search_totals = $this->apiopenapinavercom->v1_search_totals($keyword,'1','1','sim');
			$managedKeywords = $this->apisearchadnaver->ncc_managedKeyword($keyword);
			if(isset($managedKeywords[0])){
				$managedKeyword = $managedKeywords[0]['managedKeyword'];
			}
			$keywordstool = $this->apisearchadnaver->keywordstool($keyword);
			// var_dump($keywordstool);
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
			)
		);
	}
	public function test($conf,$param){
		// $keywords = '가습기,히터';
		// $res = $this->apisearchadnaver->ncc_managedKeyword($keywords);
		// var_dump($res);
		// $res = $this->apisearchadnaver->keywordstool($keywords,'','','','',1);
		// var_dump($res);
		// var_dump($this->conf_searchad_naver);
		// -------------------------
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
		// -------------------------
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
		// -------------------------
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
		// -------------------------
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
