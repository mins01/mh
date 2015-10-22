<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Page extends MX_Controller {
	
	public function __construct($conf=array())
	{
		//var_dump(func_get_args());
		//var_dump($conf);
		$this->load->module('mh/layout');
		$this->action($conf);
	}

	public function action($conf){
		$this->config->set_item('layout_head_contents','<script>console.log("xxx");</script>');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view($conf['menu']['view'],$conf);
	}

}






