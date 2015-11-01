<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->module('www/common');
		// $this->load->model('product_model','product_m');
		// $this->load->model('tag_model','tag_m');
		// $this->load->model('bbs_model','bbs_m');

		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		//$this->load->driver('cache');
		
		$this->config->load('conf_front'); // 프론트 사이트 설정
		$this->load->module('mh/common');
	}

	public function _remap($method, $params = array())
	{
		if (method_exists($this, $method))
		{
				return call_user_func_array(array($this, $method), $params);
		}
		$this->index();
		
	}
	public function get_menu($uri){
		return $this->config->item($uri,'menu_rows');
	}
/**
 * 분배 위치
 * @return null
 */
	public function index(){
		$data = array();
		//echo $uri = $this->uri->uri_string();
		//echo $this->uri->ruri_string()
		$menu_seg = $this->uri->segment(1,'');
		$menu = $this->get_menu($menu_seg);
		if(!isset($menu)){
			show_error('메뉴가 없습니다.',404);
			//show_404();
			return false;
		}
		$this->config->set_item('menu', $menu); 
		
		$conf = array(
			'menu'=>$menu,
			'base_url'=>base_url().$menu['mn_uri'],
		);
		$this->load->module('mh/'.$menu['mn_module'],$conf);
		if(!class_exists($menu['mn_module'],false)){
			show_error('모듈이 없습니다.',404);
		}else{
			$this->{$menu['mn_module']}->index_as_front($conf);
		}
		return true;
	}
	
	public function login(){
		$this->load->module('mh/member');
		$this->member->login();
	}
	public function user_info(){
		$this->load->module('mh/member');
		$this->member->modify();
	}
	public function logout(){
		$this->load->module('mh/member');
		$this->member->logout();
	}
	public function join(){
		$this->load->module('mh/member');
		$this->member->join();
	}
	
	public function search_id(){
		$this->load->module('mh/member');
		$this->member->search_id();
	}
	public function search_pw(){
		$this->load->module('mh/member');
		$this->member->search_pw();
	}
	public function reset_pw(){
		$this->load->module('mh/member');
		$this->member->reset_pw();
	}


}






