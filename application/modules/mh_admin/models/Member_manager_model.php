<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Staff_model extends CI_Model {
	public $msg = '';
	public $tbl_member = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
		$this->tbl_member = 'mh_admin';

		if(!defined('HASH_KEY')){
			show_error('암호용 키가 설정되지 않았습니다!');
			exit;
		}
		$this->hash_key = HASH_KEY;
	}
	
	public function hash($str){
		return hash('sha256',$this->hash_key.$str);
	}
	public function check_m_pass_with_m_idx($adm_idx,$adm_pass){
		$adm_row = $this->select_by_m_idx($adm_idx);
		return ($adm_row['adm_pass']==$adm_pass || $adm_row['adm_pass']==$this->hash($adm_pass));
	}
	public function select_by_m_id($adm_id){
		return $this->db->from($this->tbl_member)
			->where('adm_id',$adm_id)
			->where('adm_isdel',0)
			->get()->row_array();
	}
	public function select_by_m_idx($adm_idx){
		return $this->db->from($this->tbl_member)
			->where('adm_idx',$adm_idx)
			->where('adm_isdel',0)
			->get()->row_array();
	}
	public function set_m_login_date($adm_idx){
		$this->db->from($this->tbl_member)
			->where('adm_idx',$adm_idx)
			->where('adm_isdel',0)
			->set('adm_login_date','now()',false)
			->update();
		return $this->db->affected_rows();
	}
	public function join($row){
		return $this->insert_row($row);
	}
	public function insert_row($row){
		$set = array(
			'adm_id'=>$row['adm_id'],
			'adm_pass'=>$this->hash($row['adm_pass']),
			'adm_nick'=>$row['adm_nick'],
		);
		$this->db->from($this->tbl_member)->set($set)->insert();
		return $this->db->insert_id();
	}
	public function is_duplicate_m_nick($adm_nick,$adm_idx=null){
		$this->db->from($this->tbl_member)
			->where('adm_nick',$adm_nick);
		if($adm_idx){
			$this->db->where('adm_idx !=',(int)$adm_idx);
		}
		
		return !!$this->db->count_all_results();
	}
	public function update_row($adm_idx,$sets){
		if(isset($sets['adm_pass'])){
			$sets['adm_pass'] = $this->hash($sets['adm_pass']);
		}
		$this->db->from($this->tbl_member)
			->where('adm_idx',$adm_idx)
			->where('adm_isdel',0)
			->set($sets)
			->set('adm_update_date','now()',false)
			->update();
		return $this->db->affected_rows();
	}
	public function modify($adm_idx,$sets){
		if(!isset($adm_idx)){
			$this->msg = '필수값이 없습니다';
			return false;
		}
		$this->update_row($adm_idx,$sets);
		return true;
	}
	
	public function search_m_id($adm_nick,$adm_id_part){
		$row = $this->db->from($this->tbl_member)
			->where('adm_nick',$adm_nick)
			->like('adm_id',$adm_id_part)
			->where('adm_isdel',0)
			->select('adm_id')
			->get()->row_array();
		return isset($row['adm_id'])?$row['adm_id']:null;
	}
	
}





















