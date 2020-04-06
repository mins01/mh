<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {
	public $def_module_dir = 'mh_admin/';

	public function __construct()
	{
		parent::__construct();
		$this->config->load('conf_front'); // 프론트 사이트 설정
		$this->config->load('conf_admin'); // 관리자 사이트 설정
		$this->load->module($this->def_module_dir.'layout');
		$this->load->module($this->def_module_dir.'common');
		$this->load->model('mh/menu_model','menu_m');
	}

	public function _remap($method, $params = array())
	{
		$menu_uri = $method=='index'?'':$method;
		// $menu_uri = $method=='index'?'':$method;
		$menu_uri = uri_string();
		$menu_uri = preg_replace('/^'.ADMIN_PREFIX.'\/?/','',$menu_uri);


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


	 $m_level = (int)$this->common->get_login('m_level');
	 // 접근 제한용
	 if($m_level < 99){ //슈퍼관리자 미만이면 적용
		 $allowed = false;
		 $auth_msg = '';
		 //-- 접근 레벨 설정
		 if(!$allowed && $m_level < $menu['mn_m_level']){
			 $auth_msg = '메뉴 접근권한이 없습니다. (레벨)';
		 }else{
			 $allowed = true;
		 }

		 //-- 접근 허용아이디 설정
		 if(!$allowed && isset($menu['mn_allowed_m_id'][0])){
			 $m_id = $this->common->get_login('m_id');
			 $tt = explode(',',$menu['mn_allowed_m_id']);
			 // var_dump( $m_id);
			 // print_r($tt);
			 if(!in_array($m_id,$tt)){
				 $auth_msg = "메뉴 접근권한이 없습니다. (아이디: {$m_id})";
			 }else{
				 $allowed = true;
			 }
		 }

		 if(!$allowed){
			 show_error($auth_msg,401);
			 //show_404();
			 return false;
		 }
	 }


	 $this->config->set_item('menu', $menu);
	 if(isset($menu['mn_layout'][0])){
		 $this->config->set_item('layout_view_head',$menu['mn_layout'].'_head');
		 $this->config->set_item('layout_view_tail',$menu['mn_layout'].'_tail');
	 }

	 $conf = array(
		 'menu'=>$menu,
		 'base_url'=>ADMIN_URI_PREFIX.$menu['mn_uri'],
	 );
	 // print_r($menu);
	 // echo $this->get_segment($conf['base_url'],$params);
	 if(strpos($menu['mn_module'],'/')!==false){
		 $this->load->module($menu['mn_module'],$conf);
	 }else{
		 $this->load->module($this->def_module_dir.$menu['mn_module'],$conf);
	 }
	 $module_name = basename($menu['mn_module']);

	 if(!class_exists($module_name,false)){
		 show_error("모듈이 없습니다.($module_name , {$menu['mn_module']})",500);
	 }else{
		 $this->config->set_item('layout_og_title', $this->config->item('layout_og_title').' : '.$menu['mn_text']);
		 $this->config->set_item('layout_og_description', $this->config->item('layout_og_title'));
		 $this->{$module_name}->index_as_front($conf,$params);
	 }
	 return true;
 }


	public function login(){
		if($this->common->get_login('m_idx')){
			$this->common->redirect('',ADMIN_URI_PREFIX);
			return;
		}
		$this->load->module($this->def_module_dir.'member');
		$this->member->login();
		$this->config->set_item('layout_hide',true);
	}
	// public function user_info(){
		// $this->load->module('mh/member');
		// $this->member->modify();
	// }
	public function logout(){
		$this->load->module($this->def_module_dir.'member');
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
