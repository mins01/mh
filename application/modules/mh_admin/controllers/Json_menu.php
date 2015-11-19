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
		$this->load->model('mh/menu_model','menu_m_f');
		$this->menu_m_f->set_init_conf('menu','');
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

	public function echo_json($obj){
		echo json_encode($obj);
	}
	public function lists(){
		//$this->menu_m_f->load_db();
		$json = array();
		$json['mn_rows'] = $this->menu_m_f->select();
		return $this->echo_json($json);
	}
	private function insert(){
		$mn_id = $this->input->post('mn_id');
		if(!isset($mn_id[0])){
			$json = array(
				'msg' => 'mn_id가 없습니다.',
			);
			return $this->echo_json($json);
		}
		$cnt = $this->menu_m_f->count(array('mn_id'=>$mn_id));
		if($cnt!=0){
			$json = array(
				'msg' => '이미 등록된 아이디입니다.',
			);
			return $this->echo_json($json);
		}
		$post = $this->input->post();
		$sets = array(
			'mn_id'=>$mn_id,
			'mn_uri'=>$this->input->post('mn_uri'),
			'mn_url'=>$this->input->post('mn_url'),
			'mn_text'=>$this->input->post('mn_text'),
			'mn_sort'=>$this->input->post('mn_sort'),
			'mn_parent_id'=>$this->input->post('mn_parent_id'),
		);
		$this->menu_m_f->insert($sets);
		$json = array(
			'mn_rows' => $this->menu_m_f->select(),
			'mn_id'=>$mn_id,
			'msg' => "{$mn_id}를 등록하였습니다.",
		);
		return $this->echo_json($json);
	}
	private function update(){
		$mn_id = $this->input->post('mn_id');
		if(!isset($mn_id[0])){
			$json = array(
				'msg' => 'mn_id가 없습니다.',
			);
			return $this->echo_json($json);
		}
		$cnt = $this->menu_m_f->count(array('mn_id'=>$mn_id));
		if($cnt==0){
			$json = array(
				'msg' => '등록되지 않은 아이디입니다.',
			);
			return $this->echo_json($json);
		}
		$post = $this->input->post();
		$sets = array(
			//'mn_id'=>$mn_id,
			'mn_uri'=>$this->input->post('mn_uri'),
			'mn_url'=>$this->input->post('mn_url'),
			'mn_text'=>$this->input->post('mn_text'),
			'mn_sort'=>$this->input->post('mn_sort'),
			'mn_parent_id'=>$this->input->post('mn_parent_id'),
		);
		$wheres = array(
			'mn_id'=>$mn_id,
		);
		$this->menu_m_f->update($wheres,$sets);
		$json = array(
			'mn_rows' => $this->menu_m_f->select(),
			'mn_id'=>$mn_id,
			'msg' => "{$mn_id}을 수정하였습니다.",
		);
		return $this->echo_json($json);
	}

}






