<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bbs_comment extends MX_Controller {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	public function __construct($bbs_conf=array())
	{
		//var_dump(func_get_args());
		//var_dump($conf);
		$this->load->model('mh/bbs_master_model','bm_m');
		$this->load->model('mh/bbs_model','bbs_m');
		$this->load->model('mh/bbs_comment_model','bbs_c_m');
		$this->load->module('mh/layout');
		$this->load->module('mh/common');
		
		$this->bbs_conf = $bbs_conf;
		
		$this->m_row = $this->common->get_login();
		$this->logedin = $this->common->logedin;

		$this->config->set_item('layout_disable',true);
		//$this->action();
	}
	public function index(){
		$b_id = $this->uri->segment(2);
		$b_idx = $this->uri->segment(3);
		
		
		$this->action($b_id,$b_idx);
	}

	public function action($b_id,$b_idx){
		$mode = $this->input->post_get('mode');
		if(!isset($mode)){
			$mode = 'list';
		}
		//var_dump($_POST);
		//echo $this->uri->segment(1);

		$this->bm_row = $this->bm_m->get_bm_row($b_id);

		if($this->bm_row['bm_open']!='1'){
			show_error('사용 불가능한 게시판 입니다.');
		}
		//print_r($conf['bm_row']);
		$this->bbs_c_m->set_bm_row($this->bm_row);
		$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];

		$this->bbs_conf['page'] = (int)$this->input->get('page',1);
		if(!is_int($this->bbs_conf['page']) || $this->bbs_conf['page'] <= 0){
			$this->bbs_conf['page'] = 1;
		}
		//$this->bbs_conf['page']=$this->uri->segment(3,'1');
		//$mode = $this->input->get_post('mode',true);
		if(!isset($mode)){
			$mode = 'list';
		}
		
		
		if(!method_exists($this,'mode_'.$mode)){
			show_error('잘못된 모드입니다.');
		}
		//echo $this->bbs_conf['menu_url'];
		
		
		if(method_exists($this,'mode_'.$mode)){
			$this->{'mode_'.$mode}($b_idx);
		}else{
			$this->mode_error($b_id,$b_idx,'지원하지 않는 모드입니다.');
		}
	}
	public function print_json($json){
		$json['m_row'] = array(
			'm_nick'=>$this->common->get_login('m_nick'),
		);
		if(defined('JSON_UNESCAPED_UNICODE')){
			echo json_encode($json,JSON_UNESCAPED_UNICODE);
		}else{
			echo json_encode($json);
		}
		return;
	}
	public function data_list($b_idx,$get){
		$get['b_idx']=$b_idx;
		return $this->bbs_c_m->select_for_list($get);
	}
	public function mode_list($b_idx){
		$page = $this->input->post_get('page','1');
		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		$get['page']=$page;
		$get['b_idx']=$b_idx;
		$json = array(
			'bc_rows'=>$this->data_list($b_idx,$get),
		);
		$this->db->last_query();
		$this->print_json($json);
		return;
	}
	public function mode_error($b_idx,$error=''){
		$json = array(
			'bc_rows'=>array(),
			'error'=>$error
		);
		$this->print_json($json);
		return;
	}
	public function mode_write($b_idx){
		$page = $this->input->post_get('page','1');
		$post = $this->input->post();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		$post['b_idx']=$b_idx;
		unset($post['mode']);
		
		//$get['page']=$page;
		$post['b_idx']=$b_idx;
		$post['m_idx'] = $this->common->get_login('m_idx');
		$post['bc_name'] = $this->common->get_login('m_nick');
		$json = array(
			'bc_idx' => $this->bbs_c_m->insert_bc_row($post),
			'bc_rows' => $this->data_list($b_idx,$post),
		);
		$this->print_json($json);
		return;
	}

}






