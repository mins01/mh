<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== 로그 라이브러리

define('MH_LOG_STORE_FILE',1);
define('MH_LOG_STORE_DB',2);

if(!defined('MH_LOG_STORE')){
		define('MH_LOG_STORE',MH_LOG_USE_FILE|MH_LOG_USE_DB);
}
class Mh_log{
	private $log_gidx = '';
	protected $table = 'mh_log';
	private $ci = '';
	public $levels = array(
							'0'=>0,
							'1'=>1,
							'2'=>2,
							'3'=>3,
							'none' => 0,
							'error' => 1,
							'debug' => 2,
							'info' => 3,
							);
	public $level_2_str = array('none','error','debug','info');


	public function __construct()
	{
		$this->ci = get_instance();
		$this->log_gidx = $this->generate_log_gid();
		$this->table = DB_PREFIX.'log';
	}

	public function generate_log_gid(){
		$t = sprintf('%x',rand(0x1000,0xFFFF));
		return substr(uniqid($t),0,20);
	}
	public function info($input){
		return $this->log(1,$input);
	}
	public function debug($input){
		return $this->log(2,$input);
	}
	public function error($input){
		return $this->log(1,$input);
	}

	public function log($log_level,$input){
		$rs = array();
		if( (MH_LOG_STORE & MH_LOG_STORE_DB) == MH_LOG_STORE_DB){
			$rs[] = $this->save_log_to_db($log_level,$input);
		}
		if( (MH_LOG_STORE & MH_LOG_STORE_FILE) == MH_LOG_STORE_FILE){
			$rs[] = $this->save_log_to_file($log_level,$input);
		}

		return $rs;
	}
	public function parse_input($log_level,$input){
		$log_etc = array();
		$log_input = array();

		$rsv_fs = array(
			'title',
			'msg',
			'result',
			'val1',
			'val2',
			'num1',
			'num2',
			//'log_etc',
		);


		foreach($input as $k=>$v){
			if(in_array($k,$rsv_fs)!==false){
				$log_input['log_'.$k] = $v;
			}else{
				$log_etc[$k] = $v;
			}
		}
		$log_input['log_etc'] = serialize($log_etc);
		$log_input['log_level']=isset($this->levels[$log_level])?$this->levels[$log_level]:$log_level;
		$log_input['log_gidx']=$this->log_gidx;
		$log_input['log_insert_date']=date('Y-m-d H:i:s');
		$log_input['log_method']=isset($_SERVER['REQUEST_METHOD'])?$_SERVER['REQUEST_METHOD']:'CLI';
		$log_input['log_ip']=isset($_SERVER['REMOTE_ADDR'])?$_SERVER['REMOTE_ADDR']:'CLI';
		$log_input['log_svr_ip']=isset($_SERVER['SERVER_ADDR'])?$_SERVER['SERVER_ADDR']:'CLI';
		$log_input['log_domain']=isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
		$log_input['log_url']=isset($_SERVER['REQUEST_URI'])?strtok($_SERVER['REQUEST_URI'],'?'):'';
		$log_input['log_qstr']=isset($_SERVER['QUERY_STRING'])?$_SERVER['QUERY_STRING']:'';
		$log_input['log_referer']=isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		return $log_input;
	}
	public function save_log_to_file($log_level,$input){
		log_message($this->level_2_str[$log_level], serialize($this->parse_input($log_level,$input)));
	}
	public function save_log_to_db($log_level,$input){
		$this->ci->db->from($this->table)->set($this->parse_input($log_level,$input))->insert();
		return $this->ci->db->insert_id();
	}


	public function countFromLogIpForInit($log_ip,$fromDate=null){
		if(!$fromDate){$fromDate = date('Y-m-d 00:00:00');}
		return $this->ci->db->from($this->table)
			->where('log_insert_date >',$fromDate)
			->where('log_ip',$log_ip)
			// ->where('log_msg','첫방문')
			->where_in('log_msg',array('첫방문', '이상방문자', '접근차단'))
			->select('count(*) as cnt')
			->get()->row()->cnt;
	}



}
