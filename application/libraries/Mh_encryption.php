<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== mh 용 암호화/복호화 모델 관리 모델

class Mh_encryption{
	private $ci = null;
	public function __construct($enc_conf=array())
	{
		$this->ci = get_instance();
		$enc_conf = array_merge(array(
			'driver' => 'openssl', //가능하면 openssl 모듈을 사용한다
			// 'driver' => 'mcrypt', //가능하면 mcrypt 모듈을 사용한다
			'cipher' => 'aes-256',
			'mode' => 'CBC',
			'key' => '123456789012345678901234567890123456789012', //32길이 문자열 (32를 넘어가면 32길이까지만 사용된다.)
			'hmac' => false,
			'hmac_digest' => 'sha256',
			'hmac_key' => false
			)
		,$enc_conf);
		if (version_compare(PHP_VERSION, '5.3.0','<')) {
			$enc_conf['driver']='mcrypt';
		}
		// $enc_conf['key']='1234567890123456789012345678901234567890ss';
		// print_r($enc_conf);

		$this->ci->load->library('encryption',$enc_conf);
	}

	public function set_key($key){
		$this->initialize(array('key' => $key));
	}
	public function initialize($enc_conf){
		$this->ci->encryption->initialize($enc_conf);
	}

	public function enc($plain_val){
		$cipher_val = $this->ci->encryption->encrypt(@serialize($plain_val));
		return $cipher_val;
	}
	public function dec($cipher_val){
		$plain_val = @unserialize($this->ci->encryption->decrypt($cipher_val));
		return $plain_val;
	}
}
