<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends MX_Controller {
	
	public function __construct($conf=array())
	{

	}

		// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$view = $conf['menu']['mn_key1'];
		$this->action($conf,$param);
	}
	
	public function action($conf,$param){
		$this->config->set_item('layout_head_contents','<script>console.log("xxx");</script>');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view('mh/main/main',array('conf'=>$conf));
	}
	


}






