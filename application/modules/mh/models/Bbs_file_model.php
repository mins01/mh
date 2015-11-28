<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_file_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	public function hash($str){
		return md5($str);
	}
	public function set_bm_row($bm_row){
		$this->bm_row = $bm_row;
		//-- 테이블
		if(!isset($this->bm_row['bm_table'])){
			$this->error = '게시판 테이블 정보가 없습니다.';
			return false;
		}
		$this->tbl = DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_file';
	}
	
	public function select_for_list($b_idx){
		return $this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',(int)$b_idx)
		->get()->result_array();
	}
}