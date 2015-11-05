<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Staff extends MX_Controller {
	
	public function __construct()
	{
		//var_dump(func_get_args());
		//var_dump($conf);
		$this->load->model('mh_admin/staff_model','staff_m');
		//$this->load->module('mh/common');
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
		
		$this->load->view('mh_admin/staff/login',$data);
	}
	
	public function login_process(){
		
		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','로그인 처리');

		$adm_id = $this->input->post('adm_id');
		$adm_pass = $this->input->post('adm_pass');
		$enc_m_pass = $this->staff_m->hash($adm_pass);
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		
		
		$this->form_validation->set_rules('adm_id', '아이디', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('adm_pass', '비밀번호', 'required|min_length[1]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			$data = array(
				'ret_url' => $ret_url,
			);
			return $this->load->view('mh_admin/staff/login',$data);
		}	
		
		$adm_row = $this->staff_m->select_by_m_id($adm_id);
		
		if(!$adm_row){
			return $this->login_process_end(true,'해당 회원 정보가 없습니다.');
		}
		if($adm_row['adm_pass'] != $adm_pass && $adm_row['adm_pass'] != $enc_m_pass){
			return $this->login_process_end(true,'해당 회원 정보를 찾을 수 없습니다.');
		}
		//-- 로그인 처리
		$this->common->set_login($adm_row);
		$this->staff_m->set_m_login_date($adm_row['adm_idx']);
		return $this->login_process_end(false,'로그인에 성공하였습니다.',$ret_url);
	}
	
	private function relogin($adm_idx){
		//-- 로그인 처리
		$adm_row = $this->staff_m->select_by_m_idx($adm_idx);
		$r = $this->common->set_login($adm_row);
		$this->staff_m->set_m_login_date($adm_idx);
		return $r;
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
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]|is_unique[mh_member.m_nick]');
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
		$adm_idx = $this->staff_m->join($this->input->post());
		
		$adm_row = $this->staff_m->select_by_m_idx($adm_idx);
		if(!$adm_row){
			return $this->login_process_end(true,'해당 회원 정보가 없습니다.');
		}
		//-- 로그인 처리
		$this->common->set_login($adm_row);
		$this->staff_m->set_m_login_date($adm_row['m_idx']);
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		
		return $this->login_process_end(true,'회원 가입 완료',$ret_url);
	}

	public function modify(){
		if(!$this->common->required_login()){
			return false;
		}
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','회원정보수정');
		
		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}

		$data = array(
			'ret_url' => $ret_url,
			'm_row' =>$this->common->get_login(),
		);
		
		
		$process = $this->input->post('process');
		if($process=='modify'){
			$this->modify_process();
		}else{
			if($this->required_password()){
				$this->load->view('mh/member/modify',$data);
			}
		}
	}

	private function modify_process(){
		$adm_idx = $this->common->get_login('m_idx');
		if($this->staff_m->is_duplicate_m_nick($this->input->post('m_nick'),$adm_idx)){
			$this->common->redirect('이미 사용중인 닉네임입니다.','');
		}
		$sets = array(
		'm_nick'=>$this->input->post('m_nick'),
		);
		if(!$this->staff_m->modify($adm_idx,$sets)){
			$this->common->redirect($this->staff_m->msg,'');
		}
		$this->db->last_query();
		$this->relogin($adm_idx);
		$this->common->redirect('정보를 수정하였습니다.','');
	}
	
	public function required_password(){
		$data = array('error_msg'=>'');
		$error = false;
		$adm_idx = $this->common->get_login('m_idx');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$error = true;
		}else if(!$this->staff_m->check_m_pass_with_m_idx($adm_idx,$this->input->post('m_pass'))){
			$error = true;
			$data['error_msg'] = '<div class="text-danger">비밀번호를 확인해주세요.</div>';
		}
		if($error){
			$this->config->set_item('layout_hide',false);
			$this->load->view('mh/member/required_password',$data);
			return false;
		}
		return true;
	}
	public function search_id(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','아이디 찾기');
	
		$data = array('error_msg'=>'');
		$error = false;
		
		$this->form_validation->set_rules('m_id_part', '아이디 부분', 'required|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]');
		
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view('mh/member/search_id',$data);
		}

		$process = $this->input->post('process');
		if($process=='search_id'){
			$this->search_id_process();
		}else{
			show_error('이상접근');
		}
	}
	
	private function search_id_process(){
		$adm_nick = $this->input->post('m_nick');
		$adm_id_part = $this->input->post('m_id_part');
		$adm_id = $this->staff_m->search_m_id($adm_nick,$adm_id_part);
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$adm_id,
		);
		
		$this->load->view('mh/member/search_id_process',$data);
		//$this->load->view('mh/member/search_id',$data);
	}
	
	public function search_pw(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 찾기');
	
		$data = array('error_msg'=>'');
		$error = false;
		
		$this->form_validation->set_rules('m_id', '아이디', 'required|valid_email|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]');
		
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view('mh/member/search_pw',$data);
		}

		$process = $this->input->post('process');
		if($process=='search_pw'){
			$this->search_pw_process();
		}else if($process=='search_pw_send_mail'){
			$this->search_pw_send_mail();
		}else{
			show_error('이상접근');
		}
	}
	private function search_pw_process(){
		$adm_nick = $this->input->post('m_nick');
		$adm_id = $this->input->post('m_id');
		$adm_id = $this->staff_m->search_m_id($adm_nick,$adm_id);
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$adm_id,
			'm_nick'=>$adm_nick,
		);
		
		$this->load->view('mh/member/search_pw_process',$data);
		//$this->load->view('mh/member/search_id',$data);
	}
	private function search_pw_send_mail(){
		$this->form_validation->set_rules('m_id', '아이디', 'required|valid_email|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]');
		
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			show_error('잘못된 접근');
		}
		
		
		$adm_nick = $this->input->post('m_nick');
		$adm_id = $this->input->post('m_id');
		$adm_row = $this->staff_m->select_by_m_id($adm_id);
		
		if($adm_row['m_nick']!=$adm_nick){
			show_error('잘못된 접근.');
		}
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$adm_id,
			'm_nick'=>$adm_nick,
		);

		$adm_key_arr = array(
			'm_idx'=>$adm_row['m_idx'],
			'm_pass_md5'=>md5($adm_row['m_pass']),
			'm_update_date'=>$adm_row['m_update_date'],
		);
		$adm_key = $this->common->enc_str($adm_key_arr);
		//echo $adm_key,'<br>';
		$reset_pw_url = base_url('reset_pw').'?m_key='.urlencode($adm_key);
		$data['reset_pw_url'] = $reset_pw_url;
		
		$message = file_get_contents(_FORM_DIR.'/mail/reset_pw.html');
		$binds = array(
			'href'=>$reset_pw_url,
		);
		$result = $this->common->send_mail($adm_id,'비밀번호 변경 안내 메일',$message,$binds);
		
		$data['result'] = $result;
		//var_dump($this->email);
		
		$this->load->view('mh/member/search_pw_send_mail',$data);
		//$this->load->view('mh/member/search_id',$data);
	}
	public function reset_pw(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 재설정');
	
		$data = array('error_msg'=>'');
		$error = false;
		$adm_key = $this->input->post_get('m_key',true);
		$data['m_key'] = $adm_key;
		if(!$adm_key){
			show_error('잘못된 접근입니다.');
		}
		$tmp = $this->common->dec_str($adm_key);
		if(!isset($tmp['m_idx']) || !isset($tmp['m_pass_md5']) || !isset($tmp['m_update_date'])){
			show_error('잘못된 접근입니다..');
		}
		$adm_row = $this->staff_m->select_by_m_idx($tmp['m_idx']);

		if(!isset($adm_row['m_idx']) || !isset($adm_row['m_update_date'])){
			show_error('잘못된 접근입니다...');
		}
		if($tmp['m_pass_md5'] != md5($adm_row['m_pass']) || $adm_row['m_update_date'] != $tmp['m_update_date']){
			show_error('잘못된 접근입니다....');
		}
		
		$this->form_validation->set_rules('m_id', '아이디', 'required|valid_email|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]|matches[m_pass_re]');
		$this->form_validation->set_rules('m_pass_re', '비밀번호 확인', 'required|min_length[4]|max_length[40]');
		
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view('mh/member/reset_pw',$data);
		}

		$process = $this->input->post('process');
		if($process=='reset_pw'){
			$this->reset_pw_process($adm_row);
		}else{
			show_error('이상접근');
		}
	}
	public function reset_pw_process($adm_row){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 재설정');
		$adm_id = $this->input->post('m_id');
		$adm_pass = $this->input->post('m_pass');
		$adm_pass_re = $this->input->post('m_pass_re');
		
		if($adm_id != $adm_row['m_id']){
			$this->config->set_item('layout_hide',true);
			return $this->common->history_back('잘못된 아이디입니다');
		}
		$sets = array(
			'm_pass'=>$adm_pass,
		);
		$this->staff_m->update_row($adm_row['m_idx'],$sets);
		$data = array();
		return $this->load->view('mh/member/reset_pw_ok',$data);
		
	}
	
}






