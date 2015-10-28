<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bbs extends MX_Controller {
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
		$this->load->module('mh/layout');
		$this->load->module('mh/common');
		
		$this->bbs_conf = $bbs_conf;
		
		$this->m_row = $this->common->get_login();
		$this->logedin = $this->common->logedin;

		$this->action();
	}

	public function action(){
		//-- 게시판 마스터 정보 가져오기
		if(!isset($this->bbs_conf['menu']['b_id'])){
			show_error('게시판 정보가 잘못되었습니다.');
		}
		$this->bm_row = $this->bm_m->get_bm_row($this->bbs_conf['menu']['b_id']);
		if($this->bm_row['bm_open']!='1'){
			show_error('사용 불가능한 게시판 입니다.');
		}
		//print_r($conf['bm_row']);
		$this->bbs_m->set_bm_row($this->bm_row);
		$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];

		$mode = $this->uri->segment(2,'list');
		$b_idx = $this->uri->segment(3,''); //b_idx
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
		
		$this->bbs_conf['list_url'] = $this->bbs_conf['menu_url'] . "/list?".http_build_query($this->input->get());
		$this->bbs_conf['write_url'] = $this->bbs_conf['menu_url'] . "/write?".http_build_query($this->input->get());
		$this->bbs_conf['mode'] = $mode;
		
		$this->{'mode_'.$mode}();

	}

	private function pagination($get,$total_rows){
		$max_page = ceil($total_rows/$this->bm_row['bm_page_limit']);
		$uri = $this->bbs_conf['menu_url'] . "/list";
		return generate_paging($get,$max_page,$uri);
	}
	private function extends_b_row(& $b_row,$get){
		$b_row['read_url'] = $this->bbs_conf['menu_url'] . '/read/'.$b_row['b_idx'].'?'.http_build_query($get);
		
		$b_row['answer_url'] = $this->bbs_conf['menu_url'] . '/answer/'.$b_row['b_idx'].'?'.http_build_query($get);
		
		$b_row['edit_url'] = $this->bbs_conf['menu_url'] . '/edit/'.$b_row['b_idx'].'?'.http_build_query($get);
		
		$b_row['delete_url'] = $this->bbs_conf['menu_url'] . '/delete/'.$b_row['b_idx'].'?'.http_build_query($get);
		
		unset($get['b_idx']);
		
		$b_row['write_url'] = $this->bbs_conf['menu_url'] . '/write?'.http_build_query($get);
	}
	private function extends_b_rows(&$b_rows,$get){
		foreach($b_rows as & $r){
			$this->extends_b_row($r,$get);
		}
	}

	public function mode_list($with_read=false){
		//print_r($conf);
		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		$get['page']=$this->bbs_conf['page'];
		$b_rows = $this->bbs_m->select_for_list($get);
		$this->extends_b_rows($b_rows,$get);
		$count = $this->bbs_m->count($get);
		$start_num = $this->bbs_m->get_start_num($count,$get);
		
		$tmp = $this->input->get();
		$tmp['page'] ='page';
		$def_url = $this->bbs_conf['menu_url'] . "/list?".str_replace('page=page','page={{page}}',http_build_query($tmp));
		$pagination = $this->load->view($this->skin_path.'/pagination',array(
		'max_page' => ceil($count/$this->bm_row['bm_page_limit']),
		'page'=>$this->bbs_conf['page'],
		'def_url'=>$def_url
		),true);
		if(!$with_read){
			$this->config->set_item('layout_head_contents',$this->load->view( $this->skin_path.'/head_contents',array('mode'=>$this->bbs_conf['mode']),true));
			$this->config->set_item('layout_hide',false);
			$this->config->set_item('layout_title','list : '.$this->bm_row['bm_title']);
		}
		$this->load->view($this->skin_path.'/list',array(
		'b_rows' => $b_rows,
		'bm_row' => $this->bm_row,
		'count' => $count,
		'max_page' => ceil($count/$this->bm_row['bm_page_limit']),
		'start_num' => $start_num,
		'get'=>$get,
		'pagination' => $pagination,
		'bbs_conf'=>$this->bbs_conf,
		));
	}
	
	public function mode_read(){
		//print_r($conf);
		$get = $this->input->get();
		$b_idx = $this->uri->segment(3);
		
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_idx){
			show_error('데이터가 없습니다');
		}
		$this->extends_b_row($b_row,$get);

		$this->config->set_item('layout_head_contents',$this->load->view( $this->skin_path.'/head_contents',array('mode'=>$this->bbs_conf['mode']),true));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','read : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);
		
		$this->load->view($this->skin_path.'/read',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		));
	}
	public function mode_edit(){
		$b_idx = $this->uri->segment(3);
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다');
		}
		
		$b_pass = $this->input->post('b_pass');
		if($this->required_password($b_row,$b_pass)){
			return;
		}
		

		$this->_mode_form($b_row);
	}
	private function required_password($b_row,$b_pass,$title='비밀번호 확인',$sub_title=''){
		if(isset($b_row['m_idx'][0]) && $b_row['m_idx'] != $this->common->get_login('m_idx') || !isset($b_row['m_idx'][0])){
			//echo $this->bbs_m->hash($b_pass).'::'. $b_row['b_pass'];
			$data = array(
			'error_msg'=>'',
			'title'=>$title,
			'sub_title'=>$sub_title,
			);
			if(!$b_pass){
				$data['error_msg'] = '';
				$this->load->view($this->skin_path.'/required_password',$data);
				return true;
			}else if( $this->bbs_m->hash($b_pass) != $b_row['b_pass']){
				$data['error_msg'] = '비밀번호를 확인해주세요.';
				$this->load->view($this->skin_path.'/required_password',$data);
				return true;
			}
		}
		return false;
	}
	public function mode_answer(){
		$b_idx = $this->uri->segment(3);
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다');
		}
		$b_row['m_idx'] = null;
		$b_row['b_name'] = null;
		$b_row['b_insert_date'] = null;
		$b_row['b_title'] = preg_replace('/^(RE\:)*/','',$b_row['b_title']);
		$b_row['b_title'] = 'RE:'.$b_row['b_title'];
		$b_row['b_text'] = $b_row['b_text']."\n=-----------------=\n";

		$this->_mode_form($b_row);
	}
	public function mode_write(){
		$b_row = $this->bbs_m->generate_empty_b_row();
		$b_row['b_name']=$this->common->get_login('m_nick');
		$this->_mode_form($b_row);
	}

	private function _mode_form($b_row){
		//print_r($conf);
		if($this->input->post('process')){
			return $this->_mode_process();
		}
		$get = $this->input->get();
		$post = $this->input->post();

		$this->extends_b_row($b_row,$get);

		$this->config->set_item('layout_head_contents',$this->load->view( $this->skin_path.'/head_contents',array('mode'=>$this->bbs_conf['mode']),true));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);
		
		if(isset($post['b_pass'])){
			$b_row['b_pass'] = $post['b_pass'];
		}else{
			$b_row['b_pass'] = '';
			
		}
		
		
		$this->load->view($this->skin_path.'/form',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'mode'=>$this->bbs_conf['mode'],
		'process'=>$this->bbs_conf['mode'],
		'm_row' => $this->m_row,
		'logedin' => $this->logedin,
		'input_b_name'=>!isset($b_row['b_insert_date']) && !$this->logedin, //이름을 입력 받아야하는가?
		'input_b_pass'=>!$this->logedin, //비밀번호를 입력 받아야하는가?
		));
	}

	public function mode_delete(){
		$b_idx = $this->uri->segment(3);
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다');
		}
		

		$get = $this->input->get();
		$post = $this->input->post();
		
		$this->extends_b_row($b_row,$get);

		$this->config->set_item('layout_head_contents',$this->load->view( $this->skin_path.'/head_contents',array('mode'=>$this->bbs_conf['mode']),true));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);
		
		$b_pass = $this->input->post('b_pass');
		if($this->required_password($b_row,$b_pass,'삭제하시겠습니까?',$b_row['b_title'])){
			return;
		}
		
		$b_row['b_pass'] = $b_pass;
		
		//print_r($conf);
		if($this->input->post('process')){
			return $this->_mode_process();
		}
		$error_msg = '';
		
		$this->load->view($this->skin_path.'/delete',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'process'=>$this->bbs_conf['mode'],
		'm_row' => $this->m_row,
		'logedin' => $this->logedin,
		'error_msg' => $error_msg,
		));
	}
	private function extends_b_row_for_m_row(&$b_row){
		if($this->common->logedin){
			$b_row['m_idx'] = $this->common->get_login('m_idx');
			$b_row['b_name'] = $this->common->get_login('m_nick');
		}
	}
	private function _mode_process(){
		$process = $this->input->post('process');
		$get = $this->input->get();
		$b_idx = $this->uri->segment(3,0);
		$post = $this->input->post();
		unset($post['process']);
		
		
		$this->config->set_item('layout_head_contents',$this->load->view( $this->skin_path.'/head_contents',array('mode'=>$this->bbs_conf['mode']),true));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : process : '.$this->bm_row['bm_title']);
		
		$r = 0;
		switch($process){
			case 'edit':
				unset($post['b_pass']);
				$r = $this->bbs_m->update_b_row($b_idx,$post);
			break;
			case 'write':
				$this->extends_b_row_for_m_row($post);
				$r = $this->bbs_m->insert_b_row($post);
				$b_idx = $r;
			break;
			case 'answer':
				$this->extends_b_row_for_m_row($post);
				$r = $this->bbs_m->insert_answer_b_row($b_idx,$post);
				$b_idx = $r;
			break;
			case 'delete':
				$r = $this->bbs_m->delete_b_row($b_idx);
				$b_idx = $r;
			break;
		}

		$b_row = array('b_idx'=>$b_idx);
		$this->extends_b_row($b_row,$get);
		
		if($process =='delete'){
			$ret_url = $this->bbs_conf['list_url'];
		}else{
			$ret_url = $b_row['read_url'];
		}
		
		$this->load->view($this->skin_path.'/process',array(
		//'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'process'=>$this->bbs_conf['mode'],
		'ret_url'=>$ret_url,
		'msg'=>'처리완료.',
		));

	}

}






