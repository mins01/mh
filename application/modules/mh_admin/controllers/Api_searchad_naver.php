<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_searchad_naver extends MX_Controller {
	private $base_url = null;
	// private $conf_searchad_naver = null;
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mproxy');
		$this->config->load('api_searchad_naver');
		$this->config->load('openapi_naver_com');
		$conf_api_searchad_naver = $this->config->item('api_searchad_naver');
		$conf_openapi_naver_com = $this->config->item('openapi_naver_com');
		$this->load->library('ApiSearchadNaver');
		$this->load->library('ApiOpenApiNaverCom');
		$this->apisearchadnaver->set_account($conf_api_searchad_naver['accounts']['default']);
		$this->apisearchadnaver->set_mproxy($this->mproxy);
		$this->apiopenapinavercom->set_account($conf_openapi_naver_com['accounts']['default']);
		$this->apiopenapinavercom->set_mproxy($this->mproxy);

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
		$keywords = '가습기,히터';
		$res = $this->apisearchadnaver->ncc_managedKeyword($keywords);
		var_dump($res);
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
		exit;
	}
}
