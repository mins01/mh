<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once(dirname(__FILE__).'/Json_menu.php');
class Json_admin_menu extends Json_menu {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $limit = 20;
	public $modules_path = '/modules/mh_admin/controllers/';
	public $page_path = '/modules/mh_admin/views/page/';
	public $page_prefix = 'mh_admin/page/';
	public function __construct()
	{
		$this->load->model('mh/menu_model','menu_m_f');
		$this->menu_m_f->set_init_conf('admin_menu','');
		$this->load->module('mh_admin/layout');
		$this->load->module('mh_admin/common');

		$this->config->set_item('layout_disable',true);
		
		$this->m_row = $this->common->get_login();
		$this->logedin = & $this->common->logedin;
		//$this->config->load('bbs');
		//$this->bbs_conf = $this->config->item('bbs');
	}
	
	public function _remap($method, $params = array())
	{
		$this->index($params);
	}
	
	public function module_lists1(){
		$path=APPPATH.'/modules/mh_admin/controllers/';
		$arr = array();
		$d = dir($path);
		while (false !== ($entry = $d->read())) {
			if($entry=='.' || $entry=='..'){continue;}
			if(is_file($path.$entry)){
				$arr[] = strtolower(str_ireplace('.php','',$entry));
			}
		}
		$d->close();
		sort($arr);
		//print_r($path);
		return $arr;
	}
	public function first(){
		$this->load->model('mh/bbs_master_model','bm_m');
		$json = array(
			//'bbs_lists'=>$this->bm_m->select_for_list_for_menu(),
			'mn_rows' => $this->menu_m_f->select(),
			'module_lists'=>$this->module_lists(),
			'page_lists'=>$this->page_lists(),
		);
		return $this->echo_json($json);
	}
}





