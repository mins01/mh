<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->config->load('conf_front'); // 프론트 사이트 설정
		$this->config->load('conf_admin'); // 관리자 사이트 설정
		$this->load->module('mh_admin/common');
		$this->load->module('mh_admin/layout');
	}

	public function _remap($method, $params = array())
	{
		$this->config->set_item('base_url',base_url($this->uri->segment(1)));

		$menu_uri = $method=='index'?'':$method;
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), array($menu_uri,$params));
		}
		$this->index($menu_uri,$params);
		
	}
	public function get_menu($uri){
		return $this->config->item($uri,'menu_rows');
	}
/**
 * 분배 위치
 * @return null
 */
	public function index($menu_uri,$params=array()){
		if(!$this->common->required_login()){
			return false;
		}
		
		$data = array();

		$menu = $this->get_menu($menu_uri);
		if(!isset($menu)){
			show_error('메뉴가 없습니다.',404);
			//show_404();
			return false;
		}
		$this->config->set_item('menu', $menu); 
		$conf = array(
			'menu'=>$menu,
			'base_url'=>base_url($menu['mn_uri']),
		);
		$this->load->module('mh_admin/'.$menu['mn_module'],$conf);
		if(!class_exists($menu['mn_module'],false)){
			show_error('모듈이 없습니다.',404);
		}else{
			$this->{$menu['mn_module']}->index_as_front($conf,$params);
		}
		return true;
	}
	
	public function login(){
		$this->load->module('mh_admin/staff');
		$this->staff->login();
		$this->config->set_item('layout_hide',true);
	}
	public function user_info(){
		$this->load->module('mh/staff');
		$this->staff->modify();
	}
	public function logout(){
		$this->load->module('mh_admin/staff');
		$this->staff->logout();
	}
	// public function join(){
		// $this->load->module('mh/staff');
		// $this->staff->join();
	// }
	
	// public function search_id(){
		// $this->load->module('mh/staff');
		// $this->staff->search_id();
	// }
	// public function search_pw(){
		// $this->load->module('mh/staff');
		// $this->staff->search_pw();
	// }
	// public function reset_pw(){
		// $this->load->module('mh/staff');
		// $this->staff->reset_pw();
	// }


}






