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
		$mode = $this->uri->segment(4);
		$page = $this->uri->segment(5,'1');
		
		$this->action($b_id,$b_idx,$mode,$page);
	}

	public function action($b_id,$b_idx,$mode,$page){
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
		
		$this->{'mode_'.$mode}($b_id,$b_idx,$page);

	}
	public function print_json($json){
		if(defined('JSON_UNESCAPED_UNICODE')){
			echo json_encode($json,JSON_UNESCAPED_UNICODE);
		}else{
			echo json_encode($json);
		}
		return;
	}

	public function mode_list($b_id,$b_idx,$page){
		//print_r($conf);
		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		$get['page']=$page;
		$get['b_idx']=$b_idx;
		$bc_rows = $this->bbs_c_m->select_for_list($get);
		//echo $this->db->last_query();
		//print_r($bc_rows);
		$json = array(
			'bc_rows'=>$bc_rows,
		);
		$this->print_json($json);
		return;
	}

}






