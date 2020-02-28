<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->config->load('conf_front'); // 프론트 사이트 설정
		$this->config->load('conf_admin'); // 관리자 사이트 설정
		$this->load->module('mh_admin/common');
		$this->load->module('mh_admin/layout');
		$this->load->model('mh/menu_model','menu_m');
	}

	public function _remap($method, $params = array())
	{
		$menu_uri = $method=='index'?'':$method;
		if (method_exists($this, $method))
		{
			return call_user_func_array(array($this, $method), array($menu_uri,$params));
		}
		$this->index($menu_uri,$params);

	}
	public function get_current_menu($uri){
		$current_menu = $this->menu_m->get_current_menu($uri);
		$this->config->set_item('current_menu', $current_menu);
		return $current_menu;
	}
	public function get_segment($menu,$url){
		$mn_url = $menu['url'];
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

		$menu = $this->get_current_menu($menu_uri);
		if(!isset($menu)){
			show_error('메뉴가 없습니다.',404);
			//show_404();
			return false;
		}
		//-- 접근 레벨 설정
		if((int)$this->common->get_login('m_level') < $menu['mn_m_level']){
			show_error('접근권한이 없습니다.',401);
			//show_404();
			return false;
		}
		$this->config->set_item('menu', $menu);
		$conf = array(
			'menu'=>$menu,
			'base_url'=>ADMIN_URI_PREFIX.$menu['mn_uri'],
		);
		// print_r($menu);
		// echo $this->get_segment($conf['base_url'],$params);
		$this->load->module('mh_admin/'.$menu['mn_module'],$conf);
		if(!class_exists($menu['mn_module'],false)){
			show_error('모듈이 없습니다.',404);
		}else{
			$this->{$menu['mn_module']}->index_as_front($conf,$params);
		}
		return true;
	}


	public function login(){
		if($this->common->get_login('m_idx')){
			$this->common->redirect('',ADMIN_URI_PREFIX);
			return;
		}
		$this->load->module('mh_admin/member');
		$this->member->login();
		$this->config->set_item('layout_hide',true);
	}
	// public function user_info(){
		// $this->load->module('mh/member');
		// $this->member->modify();
	// }
	public function logout(){
		$this->load->module('mh_admin/member');
		$this->member->logout();
	}

	// public function login(){
		// $this->load->module('mh_admin/staff');
		// $this->staff->login();
		// $this->config->set_item('layout_hide',true);
	// }
	// public function user_info(){
		// $this->load->module('mh/staff');
		// $this->staff->modify();
	// }
	// public function logout(){
		// $this->load->module('mh_admin/staff');
		// $this->staff->logout();
	// }
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
