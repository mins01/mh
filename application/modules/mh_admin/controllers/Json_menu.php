<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_menu extends MX_Controller {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $limit = 20;
	public function __construct()
	{
		$this->load->model('mh/menu_model','menu_m');
		$this->load->module('mh_admin/layout');
		$this->load->module('mh_admin/common');

		$this->config->set_item('layout_disable',true);
		
		$this->m_row = $this->common->get_login();
		$this->logedin = & $this->common->logedin;
		$this->config->load('bbs');
		$this->bbs_conf = $this->config->item('bbs');

	}
	
	public function _remap($method, $params = array())
	{
		$this->index($params);
	}
	
	public function set_base_url($base_url){
		$this->base_url = $base_url;
	}
	// /bbs로 접근할 경우, 맨 처음은 b_id가 된다.
	public function index($param){
		$mode = isset($param[0][0])?$param[0]:'list';
		$mn_id = isset($param[1][0])?$param[1]:'';
		//$mode = $this->uri->segment(3,'list');//option
		
		$this->set_base_url(ADMIN_URI_PREFIX.'bbs_admin');
		$this->action($mode,$mn_id);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$mn_id = isset($param[1][0])?$param[1]:'';
		$mode = isset($param[0][0])?$param[0]:'tree';
		$this->set_base_url($base_url);
		$this->action($mode,$mn_id);
	}

	public function action($mode,$b_id){
		$this->{$mode}();
		
	}

	public function tree(){
		//$this->menu_m->load_db();
		$json = array();
		//$json['mn_tree'] = array();
		//$json['mn_tree'][0] = $this->menu_m->menu_tree;
		$json['mn_rows'] = $this->menu_m->select();
		echo json_encode($json);
	}

}






