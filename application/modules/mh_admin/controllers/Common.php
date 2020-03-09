<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MX_Controller {

	public $logedin = false;
	private $m_row = array();
	private $enc_key = '';

	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->helper('cookie');

		//--- old
		/*
		$this->load->library('encrypt');
		//$this->encrypt->set_cipher(MCRYPT_RIJNDAEL_128);
		$this->encrypt->set_cipher(MCRYPT_RIJNDAEL_256);
		//MCRYPT_RIJNDAEL_128(key:16byte),MCRYPT_RIJNDAEL_192(key:24byte),MCRYPT_RIJNDAEL_256(key:32byte)
		$this->encrypt->set_mode(MCRYPT_MODE_CBC);//MCRYPT_MODE_CBC , MCRYPT_MODE_CFB
		//$this->enc_key = substr(md5(ENCRYPTION_KEY_PREFIX.__CLASS__),0,16);
		*/
		$this->enc_key = substr(md5(ENCRYPTION_KEY_PREFIX.__CLASS__),0,32);
		$enc_conf = array(
			'driver' => 'openssl', //가능하면 openssl 모듈을 사용한다
			// 'driver' => 'mcrypt', //가능하면 mcrypt 모듈을 사용한다
			'cipher' => 'aes-256',
			'mode' => 'CBC',
			'key' => $this->enc_key,
			'hmac' => false,
			'hmac_digest' => 'sha256',
			'hmac_key' => false
		);
		if (version_compare(PHP_VERSION, '5.3.0','<')) {
			$enc_conf['driver']='mcrypt';
		}

		$this->load->library('encryption',$enc_conf);

		$this->load->model('mh/menu_model','menu_m');
		$this->menu_m->load_db('admin_menu',ADMIN_URI_PREFIX);
		$this->config->set_item('menu_rows', $this->menu_m->get_menu_rows());
		$this->config->set_item('menu_tree', $this->menu_m->get_menu_tree());
		//$this->config->set_item('current_menu', $this->menu_m->get_current_menu($_SERVER['REQUEST_URI']));

		$this->init_login();
		$t = $this->get_login('m_id');

		$this->logedin = isset($t[0]);
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
			$ret_url = ADMIN_URI_PREFIX.'login';
			$this->redirect('로그인이 필요합니다.',$ret_url);
			$this->config->set_item('layout_hide',true);
			return false;
		}
		return true;
	}

	private function init_login(){
		switch(ADMIN_LOGIN_TYPE){
			case 'cookie':
				$v = $this->input->cookie(ADMIN_LOGIN_NAME);
			break;
			case 'session':
				// $this->load->library('session');
				$v = $this->session->userdata(ADMIN_LOGIN_NAME);
			break;
		}

		if(isset($v)){
			$this->m_row = $this->dec_str($v);
		}
	}
	public function set_login($m_row){
		$m_row['m_level'] = (int)$m_row['m_level'];
		$m_row['is_admin'] = @$m_row['m_level']>=90;//관리자 유무
		if(!$m_row['is_admin']){ //관리자만 로그인 시킨다.
			return false;
		}

		unset($m_row['m_pass']);
		switch(ADMIN_LOGIN_TYPE){
			case 'cookie':
				$this->set_login_at_cookie($this->enc_str($m_row));
			break;
			case 'session':
				// $this->load->library('session');
				$name = $this->set_login_at_session($this->enc_str($m_row));
			break;
		}
	}
	public function set_logout(){
		switch(ADMIN_LOGIN_TYPE){
			case 'cookie':
				$this->set_login_at_cookie('',-100);
			break;
			case 'session':
				// $this->load->library('session');
				$this->delete_login_at_session();
			break;
		}
	}

	public function enc_str($plain_text){
		$cipher_text = $this->encryption->encrypt(@serialize($plain_text));
		return $cipher_text;
		// return @$this->encrypt->encode(@serialize( $plain_text),$this->enc_key); //old
		//return (@serialize( $plain_text));
	}
	public function dec_str($cipher_text){
		$plain_text = @unserialize($this->encryption->decrypt($cipher_text));
		return $plain_text;
		// return @unserialize(@$this->encrypt->decode($ciphertext,$this->enc_key)); //old
		//return @unserialize(($ciphertext));
	}
	//-- 로그인 세션 설정
	public function set_login_at_session($str,$expire=null){
		$this->session->set_userdata(ADMIN_LOGIN_NAME,$str);
	}
	public function delete_login_at_session(){
		$this->session->set_userdata(ADMIN_LOGIN_NAME,'');
	}
	//-- 로그인 쿠키 설정
	public function set_login_at_cookie($str,$expire=null){
		if(!isset($expire)){
			$expire = ADMIN_LOGIN_EXPIRE;
		}
		$data = array(
				'name'   => ADMIN_LOGIN_NAME,
				'value'  => $str,
				'expire' => $expire,
				'domain' => ADMIN_LOGIN_DOAMIN,
				'path'   => ADMIN_LOGIN_PATH,
				'prefix' => ADMIN_LOGIN_PREFIX,
				'secure' => ADMIN_LOGIN_SECURE
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
