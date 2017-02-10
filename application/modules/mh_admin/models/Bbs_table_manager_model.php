<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_table_manager_model extends CI_Model {
	public $msg = '';
	public $_tables = null;
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
		$this->config->load('bbs');
		$conf_bbs = $this->config->item('bbs');
		$this->file_dir = $conf_bbs['file_dir'];
	}
	public function load_data($force=false){
		if($force || !isset($this->tables)){
			$this->_tables = $this->db->list_tables();
		}
		return $this->_tables;
	}
	public function select_for_lists(){
		$tables = $this->load_data();
		$btm_rows = array();
		//DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_data'
		
		foreach($tables as $table){
			$match = array();
			if(preg_match('/^'.DB_PREFIX.'bbs_(.+)_data$/',$table,$match)){
				$btm_rows[] = $this->select_by_tbl_id($match[1]);
			}
		}
		return $btm_rows;
	}
	public function get_tbl_name($tbl_id,$tbl_type){
		return DB_PREFIX.'bbs_'.$tbl_id.'_'.$tbl_type;
	}
	public function get_file_dir($tbl_id){
		$t = $this->file_dir .'/'.$tbl_id;
		return str_replace('\\','/',$t);
		
	}
	
	public function select_by_tbl_id($tbl_id){
		$tables = $this->load_data();
		$btm_row = array();
		$btm_row['tbl_id'] = $tbl_id;
		$btm_row['tbl_data'] ='';
		$btm_row['tbl_comment'] ='';
		$btm_row['tbl_file'] ='';
		$btm_row['tbl_hit'] ='';
		foreach($tables as $table){
			$match = array();
			if(preg_match('/^'.DB_PREFIX.'bbs_'.$tbl_id.'_(.+)$/',$table,$match)){
				$btm_row['tbl_'.$match[1]] = $match[0];
			}
		}
		$btm_row['file_dir'] = $this->get_file_dir($tbl_id);
		$btm_row['file_dir_exists'] = is_dir($btm_row['file_dir']);
		
		if(
		isset($btm_row['tbl_id'][0])
		&& isset($btm_row['tbl_data'][0])
		&& isset($btm_row['tbl_comment'][0])
		&& isset($btm_row['tbl_file'][0])
		&& isset($btm_row['tbl_hit'][0])
		&& $btm_row['file_dir_exists']
		){
			$btm_row['status']='ok';
		}else{
			$btm_row['status']='error';
		}
		return $btm_row;
	}
	
	public function copy_tables($tbl_id, $to_tbl_id){
		if(preg_match('/[^a-zA-Z0-9]/',$to_tbl_id)){
			$this->msg='허용되지 않는 tbl_id';
			return false;
		}
		$btm_row = $this->select_by_tbl_id($tbl_id);
		$arr = array('data','comment','file','hit');
		foreach($arr as $tbl_type){
			if(!isset($btm_row['tbl_'.$tbl_type][0])){
				$this->msg='대상 테이블명 없음';
				//return false;
				continue;
			}
			$from_tbl_name = $btm_row['tbl_'.$tbl_type];
			$to_tbl_name = $this->get_tbl_name($to_tbl_id,$tbl_type);

			
			$sql ="CREATE TABLE {$to_tbl_name} LIKE {$from_tbl_name}";
			$r = $this->db->query($sql);
			if(!$r){
				$this->msg = $this->db->error();
				return false;
			}
		}
		//첨부파일 폴더 생성
		$file_dir = $this->get_file_dir($to_tbl_id);
		$r = @mkdir($file_dir,077,true);
		if(!$r){
			$this->msg = '첨부파일 폴더 생성 실패';
			return false;
		}
		return true;
	}
	
	public function drop_tables($tbl_id){
		$btm_row = $this->select_by_tbl_id($tbl_id);
		$arr = array('data','comment','file','hit');
		foreach($arr as $tbl_type){
			if(!isset($btm_row['tbl_'.$tbl_type][0])){
				//$this->msg='대상 테이블명 없음';
				continue;
			}
			$from_tbl_name = $btm_row['tbl_'.$tbl_type];
			
			$sql ="DROP  TABLE IF EXISTS {$from_tbl_name}";
			$r = $this->db->query($sql);
			if(!$r){
				$this->msg = $this->db->error();
				return false;
			}
		}
		//첨부파일 폴더 생성
		$file_dir = $btm_row['file_dir'];
		if(is_dir($file_dir)){
			$r = rmdir($file_dir);
			if(!$r){
				$this->msg = '첨부파일 폴더 삭제 실패';
				return false;
			}
		}
		
		return true;
	}
}





















