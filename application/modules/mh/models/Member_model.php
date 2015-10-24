<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Member_model extends CI_Model {
	public $msg = '';
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
	public function check_m_pass_with_m_idx($m_idx,$m_pass){
		$m_row = $this->select_by_m_idx($m_idx);
		return ($m_row['m_pass']==$m_pass || $m_row['m_pass']==$this->hash($m_pass));
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
	public function join($row){
		return $this->insert_row($row);
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
	public function is_duplicate_m_nick($m_nick,$m_idx=null){
		$this->db->from($this->tbl_member)
			->where('m_nick',$m_nick);
		if($m_idx){
			$this->db->where('m_idx !=',(int)$m_idx);
		}
		
		return !!$this->db->count_all_results();
	}
	public function update_row($m_idx,$sets){
		$this->db->from($this->tbl_member)
			->where('m_idx',$m_idx)
			->where('m_isdel',0)
			->set($sets)->update();
		return $this->db->affected_rows();
	}
	public function modify($m_idx,$sets){
		if(!isset($m_idx)){
			$this->msg = '필수값이 없습니다';
			return false;
		}
		$this->update_row($m_idx,$sets);
		return true;
	}
}





















