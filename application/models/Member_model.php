<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Member_model extends CI_Model {

	public $tbl_member = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
		$this->tbl_member = 'mh_member';

		if(!defined('HASH_KEY')){
			show_error('암호용 키가 설정되지 않았습니다!');
			exit;
		}
		$this->hash_key = HASH_KEY;
	}
	
	public function hash($str){
		return hash('sha256',$this->hash_key.$str);
	}
	public function select_by_m_id($m_id){
		return $this->db->from($this->tbl_member)
			->where('m_id',$m_id)
			->where('m_isdel',0)
			->get()->row_array();
	}
	public function select_by_m_idx($m_idx){
		return $this->db->from($this->tbl_member)
			->where('m_idx',$m_idx)
			->where('m_isdel',0)
			->get()->row_array();
	}
	public function insert_row($row){
		$set = array(
			'm_id'=>$row['m_id'],
			'm_pass'=>$this->hash($row['m_pass']),
			'm_nick'=>$row['m_nick'],
		);
		$this->db->from($this->tbl_member)->set($set)->insert();
		return $this->db->insert_id();
	}
}