<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// header('HTTP/1.0 404 Not Found');
header("Location: /");
exit('DontUseIt');

class Front_sdgn extends MX_Controller {

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
		$this->load->module('mh/layout');


		$sdgn_menu = $this->get_current_menu('sdgn_menus'); //기본 메뉴 그룹 변경.
		$this->config->set_item('menu_tree', array($sdgn_menu));
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
/**
 * 분배 위치
 * @return null
 */
	public function index($menu_uri,$params=array()){
		$data = array();

		if($menu_uri!=''){
			$menu_uri='/sdgn/'.$menu_uri;
		}else{
			$menu_uri='/sdgn';
		}
		$menu = $this->get_current_menu($menu_uri);
		if(!isset($menu)){
			show_error('메뉴가 없습니다.',404);
			//show_404();
			return false;
		}
		$this->config->set_item('menu', $menu);
		$conf = array(
			'menu'=>$menu,
			'base_url'=>mh_base_url($menu['mn_uri']),
		);
		$this->load->module('mh/'.$menu['mn_module'],$conf);
		if(!class_exists($menu['mn_module'],false)){
			show_error('모듈이 없습니다.',404);
		}else{
			$this->{$menu['mn_module']}->index_as_front($conf,$params);
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
	public function user_pass(){
		$this->load->module('mh/member');
		$this->member->password();
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
