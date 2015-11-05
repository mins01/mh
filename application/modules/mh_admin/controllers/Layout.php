<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout extends MX_Controller {
	public $prefix_title = '';
	public $suffix_title = '';
	public $vars = array();

	public function __construct()
	{
		// print_r(func_get_args());
		// print_r($this->config);
		$this->prefix_title = $this->config->item('prefix_title','layout');
		$this->suffix_title = $this->config->item('suffix_title','layout');
	}
	
	public function get_conf_from_config(){
		$conf = array();
		$conf['menu_tree'] = $this->config->item('menu_tree');
		$conf['menu'] = $this->config->item('menu');
		$conf['head_contents'] = $this->config->item('layout_head_contents');
		$conf['tail_contents'] = $this->config->item('layout_tail_contents');
		$conf['hide'] = $this->config->item('layout_hide');
		$conf['title'] = $this->config->item('layout_title');
		$conf['logedin'] = $this->config->item('layout_logedin');
		$conf['login_label'] = $this->common->get_login('adm_nick');

		if(!isset($conf['head_contents'])) $conf['head_contents'] = '';
		if(!isset($conf['tail_contents'])) $conf['tail_contents'] = '';
		if(!isset($conf['hide'])) $conf['hide'] = false;
		if(!isset($conf['title'])) $conf['title'] = '';
		
		return $conf;
	}

	public function layout_head($conf=array()){
		$conf = array_merge($this->get_conf_from_config(),$conf);
		
		if(!isset($conf['title'][0])){
			$conf['title'] = $this->prefix_title . $conf['menu']['mn_text']. $this->suffix_title;
		}else{
			$conf['title'] = $this->prefix_title . $conf['title']. $this->suffix_title;
		}
		
		
		return $this->load->view('mh_admin/layout/head',$conf,true);
	}
	public function layout_tail($conf = array()){
		$conf = array_merge($this->get_conf_from_config(),$conf);
		
		return $this->load->view('mh_admin/layout/tail',$conf,true);
	}

}






