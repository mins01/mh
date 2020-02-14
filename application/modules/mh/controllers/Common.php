<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MX_Controller {

	public $logedin = false;
	private $m_row = array();
	private $enc_key = '';

	public function __construct($bbs_conf=array())
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->helper('cookie');

		$this->load->library('encrypt');
		//$this->encrypt->set_cipher(MCRYPT_RIJNDAEL_128);
		$this->encrypt->set_cipher(MCRYPT_RIJNDAEL_256);
		//MCRYPT_RIJNDAEL_128(key:16byte),MCRYPT_RIJNDAEL_192(key:24byte),MCRYPT_RIJNDAEL_256(key:32byte)
		$this->encrypt->set_mode(MCRYPT_MODE_CBC);//MCRYPT_MODE_CBC , MCRYPT_MODE_CFB
		//$this->enc_key = substr(md5(ENCRYPTION_KEY_PREFIX.__CLASS__),0,16);
		$this->enc_key = substr(md5(ENCRYPTION_KEY_PREFIX.__CLASS__),0,32);


		$this->load->model('mh/menu_model','menu_m');
		$this->menu_m->load_db('menu',SITE_URI_PREFIX);
		$this->config->set_item('menu_rows', $this->menu_m->get_menu_rows());
		$this->config->set_item('menu_tree', $this->menu_m->get_menu_tree());
		$this->init_login();
		$t = $this->get_login('m_id');

		$this->logedin = isset($t[0]);
		$this->is_admin = $this->get_login('is_admin');
		$this->config->set_item('layout_logedin',$this->logedin);
	}

	public function redirect($msg,$ret_url){
		//$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',$msg);
		$this->load->view('mh/redirect.php',array('msg'=>$msg,'ret_url'=>$ret_url));
	}
	public function history_back($msg){
		$ret_url = -1;
		$this->redirect($msg,$ret_url);
	}
	public function required_login(){
		if(!$this->logedin){
			header("HTTP/1.1 401 Unauthorized");
			$ret_url = SITE_URI_PREFIX.'login';
			$this->redirect('로그인이 필요합니다.',$ret_url);
			return false;
		}
		return true;
	}
	public function get_verify_key($server){
		if(!isset($server['HTTP_USER_AGENT']) || !isset($server['HTTP_ACCEPT_ENCODING']) || !isset($server['HTTP_ACCEPT_LANGUAGE'])){
			return ''; //빈 문자열은 인증키로 인증되지 않는다.
		}
		$key = md5($server['HTTP_HOST'].
		$server['HTTP_USER_AGENT'].
		$server['HTTP_ACCEPT_ENCODING'].
		$server['HTTP_ACCEPT_LANGUAGE']);
		// echo $server['HTTP_HOST'].
		// $server['HTTP_USER_AGENT'].
		// $server['HTTP_ACCEPT_ENCODING'].
		// $server['HTTP_ACCEPT_LANGUAGE'];
		// echo $key;
		return $key;
	}
	private function init_login(){
		$v = $this->input->post_get('enc_m_row'); //json 호출등에서 값이 있다면 자동으로 로그인 된 것으로 처리한다.
		if(isset($v)){
			unset($_POST['enc_m_row'],$_GET['enc_m_row'],$_REQUEST['enc_m_row']);
		}else{
			switch(LOGIN_TYPE){
				case 'cookie':
					$v = $this->input->cookie(LOGIN_NAME);
					break;
			}
		}


		if(isset($v)){
			$m_row = $this->dec_str($v);

			if(!$this->verigy_login($m_row)){
				return false;
			}
			$this->m_row = $m_row;
		}
		// print_r($m_row);
		// print_r($v);exit;

		// print_r($m_row);		exit;
	}
	public function verigy_login($m_row){
		// $m_row['tm'] = time()-60*11;
		// print_r($_SERVER);		exit;
		if(!isset($m_row['tm'])){ $m_row['tm'] = -1; }
		if(!isset($m_row['vk'][0])){ //인증키 체크
			header('X-Invalid-Verify-key: 0');
			return false;
		}else if($m_row['vk'] != $this->get_verify_key($_SERVER)){
			header('X-Invalid-Verify-key: 0');
			return false;
		}else if(time()-$m_row['tm'] > 60*60*2 ){
			header('X-Old-Session: '.$m_row['tm']);
			return false; // 오래된 로그인 정보
		}else if(time()-$m_row['tm'] > 60*10 ){
			//-- 60분을 넘기면 로그인 정보 새로구움.
			header('X-Reflesh-Session: '.$m_row['tm']);
			$m_row['tm'] = time();
			$this->set_login($m_row);
		}
		// header('X-Ok-Session: '.$m_row['tm']);

		return true;
	}
	//m_row에서 로그인할 때 쓸 필드만 추려낸다.
	public function filter_login_from_m_row($m_row){
		$m_row['m_level'] = (int)$m_row['m_level'];
		$m_row['is_admin'] = @$m_row['m_level']==99;//관리자 유무
		unset($m_row['m_pass'],$m_row['m_isdel'],$m_row['m_isout'],
		$m_row['m_ip'],
		$m_row['m_login_date'],$m_row['m_insert_date'],$m_row['m_update_date'],$m_row['m_pass_update_date']);
		$m_row['tm'] = time();
		return $m_row;
	}
	public function set_login($m_row){
		$m_row = $this->filter_login_from_m_row($m_row);
		$m_row['vk'] = $this->get_verify_key($_SERVER);
		// $m_row['tm'] = time();
		switch(LOGIN_TYPE){
			case 'cookie':
				$this->set_login_at_cookie($this->enc_str($m_row));
				break;
		}
	}
	public function set_logout(){
		switch(LOGIN_TYPE){
			case 'cookie':
				$this->delete_login_at_cookie('');
				break;
		}
	}

	public function enc_str($plain_text){
		return @$this->encrypt->encode(@serialize( $plain_text),$this->enc_key);
		//return (@serialize( $plain_text));
	}
	public function dec_str($ciphertext){
		return @unserialize(@$this->encrypt->decode($ciphertext,$this->enc_key));
		//return @unserialize(($ciphertext));
	}

	//-- 로그인 쿠키 설정
	public function set_login_at_cookie($str,$expire=null){
		if(!isset($expire)){
			$expire = LOGIN_EXPIRE;
		}
		$data = array(
				'name'   => LOGIN_NAME,
				'value'  => $str,
				'expire' => $expire,
				'domain' => LOGIN_DOAMIN,
				'path'   => LOGIN_PATH,
				'prefix' => LOGIN_PREFIX,
				'secure' => LOGIN_SECURE
		);
		$this->input->set_cookie($data);
	}
	public function delete_login_at_cookie(){
		$data = array(
				'name'   => LOGIN_NAME,
				'value'  => '',
				'expire' => -1,
				'domain' => LOGIN_DOAMIN,
				'path'   => '/mh',
				'prefix' => LOGIN_PREFIX,
				'secure' => LOGIN_SECURE
		);
		$this->input->set_cookie($data);

		$data = array(
				'name'   => LOGIN_NAME,
				'value'  => '',
				'expire' => -1,
				'domain' => LOGIN_DOAMIN,
				'path'   => LOGIN_PATH,
				'prefix' => LOGIN_PREFIX,
				'secure' => LOGIN_SECURE
		);
		$this->input->set_cookie($data);
	}
	public function get_login($key=NULL){
		if(isset($key)){
			if($key=='m_level'){
				if(!isset($this->m_row[$key]))return 0;
				else return $this->m_row[$key];
			}
			return isset($this->m_row[$key])?$this->m_row[$key]:null;
		}
		return $this->m_row;
	}

	public function send_mail($to,$subject,$message,$binds){
		$keys = array_keys($binds);
		foreach($keys as & $v){
			$v = '{{'.$v.'}}';
		}
		$message = str_replace($keys,$binds,$message);

		$this->load->library('email');
		$this->config->load('mail'); // 프론트 사이트 설정
		$mail_conf = $this->config->item('mail');

		$this->email->initialize($mail_conf);
		$this->email->set_newline("\r\n");

		$this->email->from(SITE_ADMIN_MAIL);
		$this->email->to($to);

		$this->email->subject($subject);
		$this->email->message($message);

		return $this->email->send();
	}


}
