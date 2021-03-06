<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 회원 모델

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
		$select = 'm_id,m_idx,m_pass,m_insert_date,m_ip,m_isdel,m_level,m_login_date,m_email,m_name,m_nick,m_isout,m_update_date';
		return $this->db->from($this->tbl_member)
			->where('m_idx',$m_idx)
			->where('m_isdel',0)
			->select($select)
			->get()->row_array();
	}
	public function set_m_login_date($m_idx){
		$this->db->from($this->tbl_member)
			->where('m_idx',$m_idx)
			->where('m_isdel',0)
			->set('m_login_date','now()',false)
			->update();
		return $this->db->affected_rows();
	}
	public function join($row){
		return $this->insert_row($row);
	}
	public function insert_row($row){
		$set = array(
			'm_id'=>$row['m_id'],
			'm_pass'=>$this->hash($row['m_pass']),
			'm_nick'=>$row['m_nick'],
			'm_email'=>$row['m_email'],
		);
		$this->db->from($this->tbl_member)->set($set)
		->set('m_insert_date','now()',false)
		->set('m_pass_update_date','now()',false)
		->insert();
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
	public function is_duplicate_m_email($m_email,$m_idx=null){
		$this->db->from($this->tbl_member)
			->where('m_email',$m_email);
		if($m_idx){
			$this->db->where('m_idx !=',(int)$m_idx);
		}
		
		return !!$this->db->count_all_results();
	}
	public function update($where,$sets){
		if(isset($sets['m_pass'][0])){
			$sets['m_pass'] = $this->hash($sets['m_pass']);
		}
		if(isset($sets['m_nick'])){
			if($this->is_duplicate_m_nick($sets['m_nick'],$where['m_idx'])){
				$this->msg = '중복 m_nick';
				return false;
			}
		}
		$this->db->from($this->tbl_member)
			->where($where)
			->where('m_isdel',0)
			->set($sets)
			->set('m_update_date','now()',false);
		if(isset($sets['m_pass'])){
			$this->db->set('m_pass_update_date','now()',false);
		}	
		$this->db->update();
			//print_r($sets);
			//echo "// ",$this->db->last_query(),"\n";
		return $this->db->affected_rows();
	}
	public function update_row($m_idx,$sets){
		$where = array('m_idx'=>$m_idx);
		return $this->update($where,$sets);
	}
	public function modify($m_idx,$sets){
		if(!isset($m_idx)){
			$this->msg = '필수값이 없습니다';
			return false;
		}
		$this->update_row($m_idx,$sets);
		return true;
	}
	
	public function search_m_id($m_nick,$m_id_part){
		$row = $this->db->from($this->tbl_member)
			->where('m_nick',$m_nick)
			->like('m_id',$m_id_part)
			->where('m_isdel',0)
			->select('m_id')
			->get()->row_array();
		return isset($row['m_id'])?$row['m_id']:null;
	}

	//-- 관리용
	public function count($wheres=array()){
		return $this->db->from($this->tbl_member)->where('m_isdel',0)->where($wheres)->count_all_results();
	}
	private function _where_for_lists($dbobj,$sh){
		$dbobj->where('m_isdel',0);
		if(isset($sh['wheres'])){
			$dbobj->where($sh['wheres']);
		}
		if(isset($sh['likes'])){
			$dbobj->like($sh['likes']);
		}
		if(!empty($sh['or_likes'])){
			$this->db->group_start();
			$dbobj->or_like($sh['or_likes']);
			$this->db->group_end();
		}
		
	}
	public function count_for_lists($sh=array()){
		$this->db->from($this->tbl_member);
		$this->_where_for_lists($this->db,$sh);
		return $this->db->count_all_results();
	}
	public function select_for_lists($sh=array()){
		$this->db->from($this->tbl_member);
		$this->_where_for_lists($this->db,$sh);
		
		$select = 'm_id,m_idx,m_insert_date,m_ip,m_isdel,m_level,m_login_date,m_name,m_nick,m_isout,m_update_date';
		if(isset($sh['select'])){
			$select = $sh['select'];
		}
		
		if(isset($sh['order_by'])){
			$this->db->order_by($sh['order_by']);
		}
		
		if(isset($sh['limit'])){
			if(isset($sh['offset'])){
					$this->db->limit($sh['limit'],$sh['offset']);
			}else{
				$this->db->limit($sh['limit']);
			}
		}
		return $this->db->select($select)->get()->result_array();
	}
	
}





















