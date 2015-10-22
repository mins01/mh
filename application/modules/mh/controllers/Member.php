<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MX_Controller {
	
	public function __construct()
	{
		//var_dump(func_get_args());
		//var_dump($conf);
		$this->load->model('bbs_master_model','bm_m');
		$this->load->model('member_model','member_m');
		$this->load->module('mh/common');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	}
	
	public function login(){
		
		
		
		$process = $this->input->post_get('process');
		if($process && $process=='login'){
			return $this->login_process();
		}
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		
		$data = array(
			'ret_url' => $ret_url,
		);
		
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','로그인');
		
		$this->load->view('mh/member/login',$data);
	}
	
	public function login_process(){
		
		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','로그인 처리');
		
		$m_id = $this->input->post('m_id');
		$m_pass = $this->input->post('m_pass');
		$enc_m_pass = $this->member_m->hash($m_pass);
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		
		
		$this->form_validation->set_rules('m_id', '아이디', 'required|min_length[1]|max_length[12]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[1]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			$data = array(
				'ret_url' => $ret_url,
			);
			return $this->load->view('mh/member/login',$data);
		}	
		

		

		$m_row = $this->member_m->select_by_m_id($m_id);
		
		if(!$m_row){
			return $this->login_process_end(true,'해당 회원 정보가 없습니다.');
		}
		if($m_row['m_pass'] != $m_pass && $m_row['m_pass'] != $enc_m_pass){
			return $this->login_process_end(true,'해당 회원 정보를 찾을 수 없습니다.');
		}
		//-- 로그인 처리
		$this->common->set_login($m_row);
		return $this->login_process_end(false,'로그인에 성공하였습니다.',$ret_url);
	}
	
	public function login_process_end($error,$msg,$ret_url=null){
		if(!isset($ret_url)){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		$this->common->redirect($msg,$ret_url);
		return;
	}
	
	public function logout(){
		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','로그아웃 처리');
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		$this->common->set_logout();
		return $this->login_process_end(false,'로그아웃에 성공하였습니다.',$ret_url);
	}
	
	public function join(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','회원가입');
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}

		$data = array(
			'ret_url' => $ret_url,
		);		
		
		$this->form_validation->set_rules('m_id', '아이디', 'required|valid_email|min_length[4]|max_length[40]|is_unique[mh_member.m_id]');
		$this->form_validation->set_rules('m_nick', '별명', 'required|min_length[2]|max_length[40]|is_unique[mh_member.m_nick]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]|matches[m_pass_re]');
		$this->form_validation->set_rules('m_pass_re', '비밀번호 확인', 'required|min_length[4]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view('mh/member/join',$data);
		}
		$process = $this->input->post('process');
		if($process=='join'){
			$this->join_process();
		}else{
			show_error('이상접근');
		}
	}
	public function join_process(){
		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','회원가입 처리');
		$m_idx = $this->member_m->insert_row($this->input->post());
		
		$m_row = $this->member_m->select_by_m_idx($m_idx);
		if(!$m_row){
			return $this->login_process_end(true,'해당 회원 정보가 없습니다.');
		}
		//-- 로그인 처리
		$this->common->set_login($m_row);
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		
		return $this->login_process_end(true,'회원 가입 완료',$ret_url);
	}

}






