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
		$this->load->module('mh/layout');
		$this->load->module('mh/common');
	}

	public function _remap($method, $params = array())
	{
		// $menu_uri = $method=='index'?'':$method;
		$menu_uri = uri_string();
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
/**
 * 분배 위치
 * @return null
 */
 public function index($menu_uri,$params=array()){
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
	 $this->config->set_item('layout_use_banners',$menu['mn_use_banners']=='1');
	 if(isset($menu['mn_layout'][0])){
		 $this->config->set_item('layout_view_head',$menu['mn_layout'].'_head');
		 $this->config->set_item('layout_view_tail',$menu['mn_layout'].'_tail');
	 }

	 $conf = array(
		 'menu'=>$menu,
		 'base_url'=>mh_base_url($menu['mn_uri']),
	 );
	 $this->load->module('mh/'.$menu['mn_module'],$conf);
	 if(!class_exists($menu['mn_module'],false)){
		 show_error('모듈이 없습니다.',404);
	 }else{
		 $this->config->set_item('layout_og_title', $this->config->item('layout_og_title').' : '.$menu['mn_text']);
		 $this->config->set_item('layout_og_description', $this->config->item('layout_og_title'));
		 $this->{$menu['mn_module']}->index_as_front($conf,$params);
	 }
	 return true;
 }

	// public function login(){
		// $this->load->module('mh/member');
		// $this->member->login();
	// }
	// public function user_info(){
		// $this->load->module('mh/member');
		// $this->member->modify();
	// }
	// public function user_pass(){
		// $this->load->module('mh/member');
		// $this->member->password();
	// }
	// public function logout(){
		// $this->load->module('mh/member');
		// $this->member->logout();
	// }
	// public function join(){
		// $this->load->module('mh/member');
		// $this->member->join();
	// }

	// public function search_id(){
		// $this->load->module('mh/member');
		// $this->member->search_id();
	// }
	// public function search_pw(){
		// $this->load->module('mh/member');
		// $this->member->search_pw();
	// }
	// public function reset_pw(){
		// $this->load->module('mh/member');
		// $this->member->reset_pw();
	// }


}
