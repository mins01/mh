<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bbs_table_manager extends MX_Controller {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $limit = 20;
	public function __construct()
	{
		$this->load->helper('form');
		$this->load->model('mh_admin/bbs_table_manager_model','btm_m');
		$this->load->module('mh_admin/layout');
		$this->load->module('mh_admin/common');
		
		$this->m_row = $this->common->get_login();
		$this->logedin = & $this->common->logedin;
		$this->config->load('bbs');
		$this->bbs_conf = $this->config->item('bbs');
	}
	
	public function _remap($method, $params = array())
	{
		$this->index($params);
	}

	// /bbs로 접근할 경우, 맨 처음은 b_id가 된다.
	public function index($param){
		$mode = $this->input->post_get('mode');
		$this->action($mode);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$mode = $this->input->post_get('mode');
		$this->action($mode);
	}
	//동작분배
	public function action($mode){
		

		$this->skin_path = 'mh_admin/bbs_manager';

		$this->bbs_conf['page'] = (int)$this->input->get('page',1);
		if(!is_int($this->bbs_conf['page']) || $this->bbs_conf['page'] <= 0){
			$this->bbs_conf['page'] = 1;
		}
		if(!isset($mode)){
			$mode = 'list';
		}
		
		
		if(!method_exists($this,'mode_'.$mode)){
			show_error('잘못된 모드입니다.');
		}
		//echo $this->base_url;
		
		$this->bbs_conf['list_url'] = $this->base_url . "/list?".http_build_query($this->input->get());
		$this->bbs_conf['write_url'] = $this->base_url . "/write?".http_build_query($this->input->get());
		$this->bbs_conf['mode'] = $mode;
		
		
		$this->{'mode_'.$mode}();

	}

	public function mode_list(){
		
		
		$get = $this->input->get();
		//if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = null; }
		if(!isset($get['q'])){ $get['q'] = null; }
		if(!isset($get['ct'])){ $get['ct'] = null; }
	
		
		$btm_rows = $this->btm_m->select_for_lists();
		//print_r($btm_rows);
		
		$this->config->set_item('layout_head_contents','');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','list : 게시판테이블관리자');
	
		$this->load->view('mh_admin/bbs_table_manager/list',array(
			'btm_rows'=>$btm_rows,
		
		));
	}
	
	public function mode_process(){
		//$mode = 'process';
		$process = $this->input->post('process');
		switch($process){
			case 'copy_tables':
				$r = $this->btm_m->copy_tables($this->input->post('tbl_id'),$this->input->post('to_tbl_id'));
				if(!$r){
					show_error($this->btm_m->msg);
				}
			break;
			case 'drop_tables':
				$r = $this->btm_m->drop_tables($this->input->post('tbl_id'));
				if(!$r){
					show_error($this->btm_m->msg);
				}
			break;
		}
		$url = $_SERVER['HTTP_REFERER'];
		header('location: '.$url);
	}
}






