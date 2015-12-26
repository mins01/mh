<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_member_manager extends MX_Controller {
	
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $limit = 20;
	public $modules_path = '/modules/mh/controllers/';
	public $page_path = '/modules/mh/views/page/';
	public $page_prefix = 'mh/page/';
	
	public function __construct()
	{
		
		$this->load->model('mh/bbs_master_model','bm_m');
		
		
		$this->load->model('mh/menu_model','menu_m_f');
		
		$this->load->model('mh/member_model','member_m');
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
	//
	public function index($param){
		$mode = isset($param[0][0])?$param[0]:'list';
		$mn_id = isset($param[1][0])?$param[1]:'';
		//$mode = $this->uri->segment(3,'list');//option
		
		//$this->set_base_url(ADMIN_URI_PREFIX.'bbs_admin'); 의미 없음
		$this->action($mode);
	}
	//
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$mn_id = isset($param[1][0])?$param[1]:'';
		$mode = isset($param[0][0])?$param[0]:'tree';
		$this->set_base_url($base_url);
		$this->action($mode);
	}

	public function action($mode){
		$this->{$mode}();
		
	}

	public function echo_json($obj){
		header('Content-Type: application/json');
		echo json_encode($obj);
	}
	
	public function get_field_post(){
		$fs = array(
			'm_id','m_idx',
			//'m_insert_date','m_ip',
			//'m_isdel',
			'm_level',
			'm_pass',
			//'m_login_date',
			'm_name','m_nick','m_isout',
			//'m_update_date',
		);
		$rt = array();
		foreach($fs as $k){
			$v = $this->input->post($k);
			if(isset($v)){
				$rt[$k] = $this->input->post($k);
			}
			
		}
		return $rt;
	}
	private function insert(){
		// $mn_id = $this->input->post('mn_id');
		// if(!isset($mn_id[0])){
			// $json = array(
				// 'msg' => 'mn_id가 없습니다.',
			// );
			// return $this->echo_json($json);
		// }
		// $cnt = $this->menu_m_f->count(array('mn_id'=>$mn_id));
		// if($cnt!=0){
			// $json = array(
				// 'msg' => '이미 등록된 아이디입니다.',
			// );
			// return $this->echo_json($json);
		// }
		$post = $this->input->post();
		$sets = $this->get_field_post();
		//$sets['mn_id']=$mn_id;
		unset($sets['m_id'],$sets['m_idx']);
		
		$mn_id = $this->menu_m_f->insert($sets);
		$json = array(
			'mn_rows' => $this->menu_m_f->select(),
			'mn_id'=>$mn_id,
			'msg' => "{$mn_id}를 등록하였습니다.",
		);
		return $this->echo_json($json);
	}
	private function update(){
		$m_idx = $this->input->post('m_idx');
		$m_id = $this->input->post('m_id');
		if(!isset($m_idx[0])){
			$json = array(
				'msg' => 'm_idx가 없습니다.',
			);
			return $this->echo_json($json);
		}
		$cnt = $this->member_m->count(array('m_idx'=>$m_idx));
		if($cnt==0){
			$json = array(
				'msg' => '등록되지 않은 회원입니다.',
			);
			return $this->echo_json($json);
		}
		$post = $this->input->post();
		$sets = $this->get_field_post();
		unset($sets['m_id'],$sets['m_idx']);
		$wheres = array(
			'm_idx'=>$m_idx,
		);
		$r = $this->member_m->update($wheres,$sets);
		if(!$r){
			$json = array(
				//'m_row'=>$this->member_m->select_by_m_idx($m_idx),
				//'m_idx'=>$m_idx,
				'msg' => $this->member_m->msg,
			);
		}else{
			$json = array(
				'm_row'=>$this->member_m->select_by_m_idx($m_idx),
				'm_idx'=>$m_idx,
				'msg' => "{$m_id}을 수정하였습니다.",
			);
		}
		return $this->echo_json($json);
	}
	private function delete(){
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
		$wheres = array(
			'mn_id'=>$mn_id,
		);
		$this->menu_m_f->delete($wheres);
		$json = array(
			'mn_rows' => $this->menu_m_f->select(),
			'mn_id'=>$mn_id,
			'msg' => "{$mn_id}을 삭제하였습니다.",
		);
		return $this->echo_json($json);
	}
	
	public function first(){
		
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		if(!$limit) $limit = 3;
		if(!$offset) $offset = 0;
		$json = array(
			'm_rows'=>$this->member_m->select_for_lists(array('limit'=>$limit,'order_by'=>'m_idx DESC')),
			'm_cnt'=>$this->member_m->count_for_lists(array()),
			'offset'=>$offset,
		);
		return $this->echo_json($json);
	}
	
	public function lists(){
		$this->load->model('mh/bbs_master_model','bm_m');
		$limit = $this->input->get('limit');
		$offset = $this->input->get('offset');
		if(!$limit) $limit = 3;
		if(!$offset) $offset = 0;
		
		$wheres = array();
		$or_likes = array();
		$tq = $this->input->get('tq');
		$q = $this->input->get('q');
		if($tq && $q ){
			if($tq=='_all_'){
				$or_likes['m_id']=$q;
				$or_likes['m_nick']=$q;
			}else{
				$or_likes[$tq]=$q;
			}
		}
		
		
		$json = array(
			'm_rows'=>$this->member_m->select_for_lists(
																		array('limit'=>$limit,
																					'offset'=>$offset,
																					'wheres'=>$wheres,
																					'or_likes'=>$or_likes,
																					'order_by'=>'m_idx DESC')
																		),
			'm_cnt'=>$this->member_m->count_for_lists(array(
																					//'limit'=>$limit,
																					//'offset'=>$offset,
																					'wheres'=>$wheres,
																					'or_likes'=>$or_likes,
																					//'order_by'=>'m_idx DESC'
																					)
																		),
			'offset'=>$offset,
		);
		
		return $this->echo_json($json);
	}
	
	public function select_by_m_idx(){
		$this->load->model('mh/bbs_master_model','bm_m');
		$m_idx = $this->input->get('m_idx');
		$json = array(
			'm_row'=>$this->member_m->select_by_m_idx($m_idx),
		);
		return $this->echo_json($json);
	}
}
