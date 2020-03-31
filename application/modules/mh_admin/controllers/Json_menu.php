<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Json_menu extends MX_Controller {
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
	public function lists(){
		//$this->menu_m_f->load_db();
		$json = array();
		$json['mn_rows'] = $this->menu_m_f->select();
		return $this->echo_json($json);
	}
	public function get_field_post(){
		$fs = array(
			'mn_id','mn_uri','mn_url','mn_text','mn_sort','mn_parent_id','mn_hide_sitemap',
			'mn_module','mn_arg1','mn_arg2','mn_arg3',
			'mn_use','mn_hide','mn_use_banners','mn_lock','mn_m_level','mn_allowed_m_id','mn_layout','mn_head_contents','mn_top_html',
		);
		if($this->input->post('mn_lock')=='1'){
			$fs = array(
				'mn_id','mn_uri','mn_url','mn_text','mn_sort','mn_parent_id',
				//'mn_module','mn_arg1','mn_arg2','mn_arg3',
				//'mn_use','mn_hide',
				'mn_lock',
			);
		}
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
		unset($sets['mn_id']);

		$mn_id = $this->menu_m_f->insert($sets);
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
		$sets = $this->get_field_post();
		unset($sets['mn_id']);
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
	public function module_lists(){
		$path=APPPATH.$this->modules_path;
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
	// page 모듈용
	public function page_lists(){
		$path=APPPATH.$this->page_path;
		$arr = array();
		$d = dir($path);
		while (false !== ($entry = $d->read())) {
			if($entry=='.' || $entry=='..'){continue;}
			if(is_file($path.$entry)){
				$v = str_ireplace('.php','',$entry);
				$k = $this->page_prefix.$v;
				$arr[$k] = $v;
			}
		}
		$d->close();
		asort($arr);
		//print_r($path);
		return $arr;
	}
	public function first(){
		$this->load->model('mh/bbs_master_model','bm_m');
		$json = array(
			'bbs_lists'=>$this->bm_m->select_for_list_for_menu(),
			'mn_rows' => $this->menu_m_f->select(),
			'module_lists'=>$this->module_lists(),
			'page_lists'=>$this->page_lists(),
		);
		return $this->echo_json($json);
	}
}
