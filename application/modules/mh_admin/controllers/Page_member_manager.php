<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__).'/Page.php');

class Page_member_manager extends Page {

	public function __construct()
	{
		parent::__construct();
	}
	public function index($conf=array(),$view=array()){
		$this->config->load('bbs');
		$this->bbs_conf = $this->config->item('bbs');
		$this->levels = $this->bbs_conf['levels'];
		$view = 'mh_admin/page/member_manager'; //강제 고정
		// $this->config->set_item('layout_head_contents','<script>console.log("xxx");</script>');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view($view,array('conf'=>$conf,'levels'=>$this->levels));
	}

}
