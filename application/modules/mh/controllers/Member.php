<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Member extends MX_Controller {
	public $conf = array();
	public $params = array();
	private $view_dir = 'mh/member/';
	public function __construct()
	{
		if(MEMBER_ONLY_HTTPS){
			only_https();
		}
		$this->load->model('mh/member_model','member_m');
		$this->load->module('mh/common');
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
	}
	public function init_as_front($conf,$params){ //동작 초기화
		$this->conf = $conf;
		$this->params = $params;
	}
	public function index(){
		if(!$this->common->logedin){
			$this->common->redirect('회원가입으로 변경합니다.',SITE_URI_MEMBER_PREFIX.'modify');
			return;
		}else{
			return $this->login();	
		}
		
	}
	public function login(){
		header("HTTP/1.1 401 Unauthorized");
		$process = $this->input->post_get('process');
		if($process && $process=='login'){
			return $this->login_process();
		}

		$ret_url = $this->input->post_get('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'][0])?$_SERVER['HTTP_REFERER']:base_url();
		}
		$data = array(
			'ret_url' => $ret_url,
		);

		// $this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','로그인');

		$this->load->view($this->view_dir.'login',$data);
	}

	private function login_process(){

		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','로그인 처리');


		$login_failed_count = $this->session->userdata('login_failed_count'); //로그인 실패 횟수
		if (!$login_failed_count) { $login_failed_count = 0; }
		if($login_failed_count>5){
			$login_failed_time = $this->session->userdata('login_failed_time'); //마지막 실패 시간
			if(time()-60*5 < $login_failed_time){
				show_error("Too many login attempts. Please try again later.", 403, "Login Blocked");
			}
		}


		$m_id = $this->input->post('m_id');
		$m_pass = $this->input->post('m_pass');

		$ret_url = $this->input->post_get('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		$this->form_validation->set_rules('m_id', '아이디', 'required|min_length[1]|max_length[100]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[1]|max_length[100]');
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			$data = array(
				'ret_url' => $ret_url,
			);
			return $this->load->view($this->view_dir.'login',$data);
		}

		$res = $this->process_login_process($m_id,$m_pass);
		if($res['is_error']){
			$login_failed_count++;
			$this->session->set_userdata('login_failed_count', $login_failed_count); // 실패 횟수 증가
			$this->session->set_userdata('login_failed_time', time()); // 실패 횟수 증가

			$fail_url = '?ret_url='.urlencode($ret_url);
			$this->login_process_end($res['is_error'],$res['msg'],$fail_url);
		}else{
			$this->session->set_userdata('login_failed_count', 0); // 실패 횟수 초기화
			$m_row = $res['m_row'];
			$this->common->set_login($m_row);
			$this->member_m->set_m_login_date($m_row['m_idx']);
			$this->login_process_end($res['is_error'],$res['msg'],$ret_url);
		}
	}

	public function json_login_process(){
		$m_id = $this->input->post_get('m_id');
		$m_pass = $this->input->post_get('m_pass');

		$res = $this->process_login_process($m_id,$m_pass);
		$res['post'] = $_POST;
		$res['get'] = $_GET;
		$res['m_nick'] = isset($res['m_row']['m_nick'])?$res['m_row']['m_nick']:null;
		if(isset($res['m_row'])){
			$res['enc_m_row'] = $this->common->enc_str($this->common->filter_login_from_m_row($res['m_row']));
		}
		unset($res['m_row']);
		exit_json($res);

	}

	/**
	* 로그인 처리결과 및 사용자 정보를 준다.
	*/
	private function process_login_process($m_id,$m_pass){
		$res = array('is_error'=>true,'msg'=>'처리 시작','m_row'=>null);

		if(!isset($m_id)){
			$res['msg'] = '아이디를 입력해주세요.';return $res;
		}else if(!isset($m_id[0])){
			$res['msg'] = '아이디가 너무 짧습니다.';return $res;
		}else if(isset($m_id[1000])){
			$res['msg'] = '아이디가 너무 깁니다.';return $res;
		}
		if(!isset($m_pass)){
			$res['msg'] = '비밀번호를 입력해주세요.';return $res;
		}else if(!isset($m_pass[0])){
			$res['msg'] = '비밀번호가 너무 짧습니다.';return $res;
		}else if(isset($m_pass[100])){
			$res['msg'] = '비밀번호가 너무 깁니다.';return $res;
		}

		$enc_m_pass = $this->member_m->hash($m_pass);

		$m_row = $this->member_m->select_by_m_id($m_id);

		if(!$m_row){
			$this->mh_log->error(array(
				'title'=>__METHOD__,
				'msg'=>'로그인',
				'result'=>'실패1',
				'm_row'=>@$m_row,
			));
			$res['msg'] = '해당 회원 정보가 없습니다.';
			return $res;
		}
		if($m_row['m_pass'] != $m_pass && $m_row['m_pass'] != $enc_m_pass){
			unset($m_row['m_pass']);
			$this->mh_log->error(array(
				'title'=>__METHOD__,
				'msg'=>'로그인',
				'result'=>'실패2',
				'm_row'=>@$m_row,
			));
			$res['msg'] = '해당 회원 정보를 찾을 수 없습니다.';
			return $res;
		}
		//-- 로그인정보 필터 처리
		//$m_row = $this->common->filter_login_from_m_row($m_row);
		unset($m_row['m_pass']);

		$res['m_row'] = $m_row;
		$this->mh_log->info(array(
			'title'=>__METHOD__,
			'msg'=>'로그인',
			'result'=>'성공',
			'm_row'=>@$m_row,
		));
		$res['is_error'] = false;
		$res['msg'] = '로그인에 성공하였습니다.';
		return $res;
	}

	private function relogin($m_idx){
		//-- 로그인 처리
		$m_row = $this->member_m->select_by_m_idx($m_idx);
		$r = $this->common->set_login($m_row);
		$this->member_m->set_m_login_date($m_idx);
		unset($m_row['m_pass']);
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'재로그인',
		'result'=>'성공',
		'm_row'=>@$m_row,
		));
		return $r;
	}

	private function login_process_end($error,$msg,$ret_url=null){
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
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'로그아웃',
		'result'=>'성공',
		'm_row'=>$this->common->get_login(),
		));
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

		$this->form_validation->set_rules('m_id', '아이디', 'required|min_length[4]|max_length[40]|alpha_dash|is_unique[mh_member.m_id]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]|is_unique[mh_member.m_nick]');
		$this->form_validation->set_rules('m_email', '이메일', 'required|valid_email|min_length[5]|max_length[200]|is_unique[mh_member.m_email]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]|matches[m_pass_re]');
		$this->form_validation->set_rules('m_pass_re', '비밀번호 확인', 'required|min_length[4]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view($this->view_dir.'join',$data);
		}
		$process = $this->input->post('process');
		if($process=='join'){
			$this->join_process();
		}else{
			show_error('이상접근');
		}
	}
	private function join_process(){
		$this->config->set_item('layout_hide',true);
		$this->config->set_item('layout_title','회원가입 처리');
		$m_idx = $this->member_m->join($this->input->post());

		$m_row = $this->member_m->select_by_m_idx($m_idx);
		if(!$m_row){
			return $this->login_process_end(true,'해당 회원 정보가 없습니다.');
		}
		//-- 로그인 처리
		$this->common->set_login($m_row);
		$this->member_m->set_m_login_date($m_row['m_idx']);

		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}
		unset($m_row['m_pass']);
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'회원가입',
		'result'=>'성공',
		'm_row'=>@$m_row,
		));
		return $this->login_process_end(true,'회원 가입 완료',$ret_url);
	}
	public function user_info(){
		if(!$this->common->logedin){
			$this->common->redirect('회원가입으로 변경합니다.',SITE_URI_MEMBER_PREFIX.'join');
			return;
		}
		$this->modify();
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
				$this->load->view($this->view_dir.'modify',$data);
			}
		}
	}

	private function modify_process(){
		$m_idx = $this->common->get_login('m_idx');
		if($this->member_m->is_duplicate_m_nick($this->input->post('m_nick'),$m_idx)){
			$this->common->redirect('이미 사용중인 닉네임입니다.','');
			return;
		}
		if($this->member_m->is_duplicate_m_email($this->input->post('m_email'),$m_idx)){
			$this->common->redirect('이미 사용중인 이메일입니다.','');
			return;
		}
		$sets = array(
		'm_nick'=>$this->input->post('m_nick'),
		'm_email'=>$this->input->post('m_email'),
		);
		if(!$this->member_m->modify($m_idx,$sets)){
			$this->common->redirect($this->member_m->msg,'');
		}
		//$this->db->last_query();
		$this->relogin($m_idx);

		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'회원정보수정',
		'result'=>'성공',
		'm_row'=>@$sets,
		));
		$this->common->redirect('정보를 수정하였습니다.','');
		return;
	}

	public function password(){
		if(!$this->common->required_login()){
			return false;
		}
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','회원 비밀번호 수정');

		$ret_url = $this->input->post('ret_url');
		if(!$ret_url){
			$ret_url = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:base_url();
		}

		$data = array(
			'ret_url' => $ret_url,
			'm_row' =>$this->common->get_login(),
		);


		$process = $this->input->post('process');
		if($process=='password'){
			$this->password_process();
		}else{
			if($this->required_password()){
				$this->load->view($this->view_dir.'modify_pass',$data);
			}
		}
	}

	private function password_process(){
		$m_idx = $this->common->get_login('m_idx');
		if(!$m_idx){
			$this->common->redirect('로그인 후 사용하실 수 있습니다.','');
			return false;
		}
		$m_pass = $this->input->post('m_pass');
		$m_pass_new = $this->input->post('m_pass_new');
		$m_pass_new_re = $this->input->post('m_pass_new_re');
		if($m_pass_new != $m_pass_new_re){
			$this->common->redirect('사용하실 비밀번호를 다시 확인해주세요.','');
			return;
		}
		$m_row = $this->member_m->select_by_m_idx($m_idx);
		if($m_row['m_pass']!=$m_pass && $m_row['m_pass']!=$this->member_m->hash($m_pass)){
			$this->common->redirect('현재 비밀번호를 다시 확인해주세요..','');
			return;
		}
		$sets = array();
		$sets['m_pass'] = $m_pass_new;
		if(!$this->member_m->modify($m_idx,$sets)){
			$this->common->redirect($this->member_m->msg,'');
		}
		$this->db->last_query();
		$this->relogin($m_idx);

		unset($m_row['b_pass']);
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'비밀번호수정',
		'result'=>'성공',
		'm_row'=>@$m_row,
		));

		$this->common->redirect('정보를 수정하였습니다.','');
		return;
	}

	public function required_password(){
		header("HTTP/1.1 401 Unauthorized");
		$data = array('error_msg'=>'');
		$error = false;
		$m_idx = $this->common->get_login('m_idx');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]');
		if ($this->form_validation->run() == FALSE){
			$error = true;
		}else if(!$this->member_m->check_m_pass_with_m_idx($m_idx,$this->input->post('m_pass'))){
			$error = true;
			$data['error_msg'] = '<div class="text-danger">비밀번호를 확인해주세요.</div>';
		}
		if($error){
			$this->config->set_item('layout_hide',false);
			$this->load->view($this->view_dir.'required_password',$data);
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
			return $this->load->view($this->view_dir.'search_id',$data);
		}

		$process = $this->input->post('process');
		if($process=='search_id'){
			$this->search_id_process();
		}else{
			show_error('이상접근');
		}
	}

	private function search_id_process(){
		$m_nick = $this->input->post('m_nick');
		$m_id_part = $this->input->post('m_id_part');
		$m_id = $this->member_m->search_m_id($m_nick,$m_id_part);
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$m_id,
		);
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'아이디찾기',
		'result'=>'성공',
		'm_id'=>@$m_id,
		));
		$this->load->view($this->view_dir.'search_id_process',$data);
		//$this->load->view($this->view_dir.'search_id',$data);
	}

	public function search_pw(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 찾기');

		$data = array('error_msg'=>'');
		$error = false;

		$this->form_validation->set_rules('m_id', '아이디', 'required|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]');

		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view($this->view_dir.'search_pw',$data);
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
		$m_nick = $this->input->post('m_nick');
		$m_id = $this->input->post('m_id');
		$m_id = $this->member_m->search_m_id($m_nick,$m_id);
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$m_id,
			'm_nick'=>$m_nick,
		);
		$this->mh_log->info(array(
		'title'=>__METHOD__,
		'msg'=>'비밀번호찾기',
		'result'=>'성공',
		'm_id'=>@$m_id,
		));
		$this->load->view($this->view_dir.'search_pw_process',$data);
		//$this->load->view($this->view_dir.'search_id',$data);
	}
	private function search_pw_send_mail(){
		$this->form_validation->set_rules('m_id', '아이디', 'required|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_nick', '닉네임', 'required|min_length[2]|max_length[40]');

		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			show_error('잘못된 접근');
		}


		$m_nick = $this->input->post('m_nick');
		$m_id = $this->input->post('m_id');
		$m_row = $this->member_m->select_by_m_id($m_id);

		if($m_row['m_nick']!=$m_nick){
			show_error('잘못된 접근.');
		}
		if(!isset($m_row['m_email'][0])){
			show_error('이메일이 등록되어있지 않음.');
		}
		//echo $this->db->last_query();
		$data = array(
			'm_id'=>$m_id,
			'm_nick'=>$m_nick,
		);

		$m_key_arr = array(
			'm_idx'=>$m_row['m_idx'],
			'm_pass_md5'=>md5($m_row['m_pass']),
			'm_update_date'=>$m_row['m_update_date'],
		);
		$m_key = $this->common->enc_str($m_key_arr);
		//echo $m_key,'<br>';
		$reset_pw_url = base_url('reset_pw').'?m_key='.urlencode($m_key);
		$data['reset_pw_url'] = $reset_pw_url;

		$message = file_get_contents(_FORM_DIR.'/mail/reset_pw.html');
		$binds = array(
			'href'=>$reset_pw_url,
		);
		$result = $this->common->send_mail($m_row['m_email'],'비밀번호 변경 안내 메일',$message,$binds);

		$data['result'] = $result;
		//var_dump($this->email);

		$this->load->view($this->view_dir.'search_pw_send_mail',$data);
		//$this->load->view($this->view_dir.'search_id',$data);
	}
	public function reset_pw(){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 재설정');

		$data = array('error_msg'=>'');
		$error = false;
		$m_key = $this->input->post_get('m_key',true);
		$data['m_key'] = $m_key;
		if(!$m_key){
			show_error('잘못된 접근입니다.');
		}
		$tmp = $this->common->dec_str($m_key);
		if(!isset($tmp['m_idx']) || !isset($tmp['m_pass_md5']) || !isset($tmp['m_update_date'])){
			show_error('잘못된 접근입니다..');
		}
		$m_row = $this->member_m->select_by_m_idx($tmp['m_idx']);

		if(!isset($m_row['m_idx']) || !isset($m_row['m_update_date'])){
			show_error('잘못된 접근입니다...');
		}
		if($tmp['m_pass_md5'] != md5($m_row['m_pass']) || $m_row['m_update_date'] != $tmp['m_update_date']){
			show_error('잘못된 접근입니다....');
		}

		$this->form_validation->set_rules('m_id', '아이디', 'required|valid_email|min_length[4]|max_length[40]');
		$this->form_validation->set_rules('m_pass', '비밀번호', 'required|min_length[4]|max_length[40]|matches[m_pass_re]');
		$this->form_validation->set_rules('m_pass_re', '비밀번호 확인', 'required|min_length[4]|max_length[40]');

		if ($this->form_validation->run() == FALSE){
			$this->config->set_item('layout_hide',false);
			return $this->load->view($this->view_dir.'reset_pw',$data);
		}

		$process = $this->input->post('process');
		if($process=='reset_pw'){
			$this->reset_pw_process($m_row);
		}else{
			show_error('이상접근');
		}
	}
	private function reset_pw_process($m_row){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','비밀번호 재설정');
		$m_id = $this->input->post('m_id');
		$m_pass = $this->input->post('m_pass');
		$m_pass_re = $this->input->post('m_pass_re');

		if($m_id != $m_row['m_id']){
			$this->config->set_item('layout_hide',true);
			return $this->common->history_back('잘못된 아이디입니다');
		}
		$sets = array(
			'm_pass'=>$m_pass,
		);
		$this->member_m->update_row($m_row['m_idx'],$sets);
		$data = array();
		return $this->load->view($this->view_dir.'reset_pw_ok',$data);

	}

}
