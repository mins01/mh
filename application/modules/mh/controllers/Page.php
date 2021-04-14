<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MX_Controller {
	public $module_type = 2;
	public function __construct($conf=array())
	{
		//var_dump(func_get_args());
		//var_dump($conf);
		//$this->load->module('mh/layout');
		//$this->action($conf);
	}

	// front 컨트롤에서 접근할 경우. //모듈타입1의 호환성을 위해 놔둠
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$view = $conf['menu']['mn_arg1'];
		$this->action($conf,$view);
	}

	public function index($conf=array(),$param=array()){
		$view = isset($conf['menu']['mn_arg1'])?$conf['menu']['mn_arg1']:'';
		$this->action($conf,$view);
	}
	private function action($conf,$view){
		// $this->config->set_item('layout_head_contents','<script>console.log("xxx");</script>');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view($view,array('conf'=>$conf));
	}
}
