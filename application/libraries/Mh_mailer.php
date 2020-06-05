<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== mh 메일 발송

class Mh_mailer{
	private $ci = null;
	private $mail_accounts = null;
	public function __construct($enc_conf=array())
	{
		$this->ci = get_instance();
		// $this->ci->load->library('encryption',$enc_conf);
		$this->ci->load->library('email');
		$this->ci->load->library('mh_encryption');
	}
	public function get_mail_conf($mail_account){
		if($this->mail_accounts == null){
			$this->mail_accounts = $this->ci->config->load('mail_accounts'); // 이메일 설정
		}
		$mail_conf = $this->mail_accounts[$mail_account];
		if(!isset($this->mail_accounts[$mail_account])){
			show_error('존재하지 않는 mail_account입니다.');
		}
		if(isset($mail_conf['enc_smtp_pass'])){
			$mail_conf['smtp_pass'] = $this->dec($mail_conf['enc_smtp_pass']);
		}
		// print_r($mail_conf);

		return $mail_conf;
	}
	public function enc($plain_val){
		return $this->ci->mh_encryption->enc($plain_val);
	}
	public function dec($cipher_val){
		return $this->ci->mh_encryption->dec($cipher_val);
	}
	public function send_with_mail_account($send_data,$mail_account){
		return $this->send($send_data,$this->get_mail_conf($mail_account));
	}
	public function send($send_data,$mail_conf = array()){
    // http://www.ciboard.co.kr/user_guide/kr/libraries/email.html
    $send_data = array_merge(array(
			'from' =>  isset($mail_conf['from'])?$mail_conf['from']:'',
			'from_name' => isset($mail_conf['from_name'])?$mail_conf['from_name']:'',
			'return_path' => isset($mail_conf['return_path'])?$mail_conf['return_path']:null,
      'to'=>'',
      'subject'=>'',
      'message'=>'',
      'binds'=>'',
    ),$send_data);
    // print_r($send_data);exit;
     $send_data['from'] = trim($send_data['from']);
     if(!isset($send_data['from'][0])){
         $res = array('result'=>false,'to'=>$send_data['to'],'headers'=>'발송자 메일 주소가 없습니다.');
         return $res;
     }
    $send_data['to'] = trim($send_data['to']);
    if(!isset($send_data['to'][0])){
        $res = array('result'=>false,'to'=>$send_data['to'],'headers'=>'빈 주소로 메일을 발송 할 수 없습니다.');
        return $res;
    }
    if(count($send_data['binds'])>0){
      $keys = array_keys($send_data['binds']);
  		foreach($keys as & $v){
  			$v = '{{'.$v.'}}';
  		}
  		$message = str_replace($keys,array_values($send_data['binds']),$message);
    }
		$this->ci->config->load('mail'); // 이메일 설정
		$mail_conf = array_merge($this->ci->config->item('mail'),$mail_conf);
    $mail_conf['validate'] = true;
    // $mail_conf['smtp_user'] = $smtp_user;
    // $mail_conf['smtp_pass'] = $smtp_pass;

		$this->ci->email->initialize($mail_conf);
		$this->ci->email->set_newline("\r\n");
    // $this->ci->email->from(SITE_ADMIN_MAIL);
    if(!isset($send_data['from_name'])){
			$send_data['from_name'] = '';
		}
		if(!isset($send_data['return_path'])){
			$send_data['return_path'] = null;
		}

		$this->ci->email->from($send_data['from'],$send_data['from_name'],$send_data['return_path']);
		$this->ci->email->to($send_data['to']);
		$this->ci->email->subject($send_data['subject']);
		$this->ci->email->message($send_data['message']);

    $r = @$this->ci->email->send();
    $res = array('result'=>$r,'to'=>$send_data['to'],'headers'=>$this->ci->email->print_debugger(array('headers')));
    return $res;
	}
}
