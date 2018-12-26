<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 태그 모델

class Bbs_tag_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	public $msg = '';
	public $msgs = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->config->load('bbs');
		$conf_bbs = $this->config->item('bbs');
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
		$this->tbl = DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_tag';
	}
	
	//=== select
	public function select_by_bt_idx($bt_idx)
	{
		$select = "bt.*";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('bt_idx',(int)$bt_idx)->get()->result_array();
	}
	public function select_by_bt_tag($bt_tag)
	{
		$select = "bt.b_idx";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('bt_tag',$bt_tag)->get()->result_array();
	}
	public function select_by_b_idx($b_idx)
	{
		$select = "bt.bt_tag";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('b_idx',(int)$b_idx)->get()->result_array();
	}
	//=== insert
	public function insert($b_idx,$bt_tag)
	{
		$this->db->from($this->tbl)
		->set('b_idx',(int)$b_idx)
		->set('bt_tag',$bt_tag)
		->set('bt_insert_date','now()',false);
		// ->insert();		
		$sql = $this->db->get_compiled_insert() . ' ON DUPLICATE KEY UPDATE bt_insert_date = now(),bt_is_del=0, bt_update_count = bt_update_count +1';
		$this->db->query($sql);
		return $this->db->insert_id();
	}	
	//=== delete
	public function delete_by_b_idx($b_idx,$bt_tags=array())
	{
		if(count($bt_tags)>0){
			$this->db->where_not_in('bt_tag',$bt_tags);
		}
		return $this->db->from($this->tbl)->set('bt_is_del',1)->set('bt_delete_date','now()',false)->where('b_idx',(int)$b_idx)->update();
	}
	//=== util 
	public function pickup_tags($str){
		$matched = array();
		preg_match_all('/(?:#)([^\s\n\x00-\x2C\x2E-\x2F\x3A-\x40\x5B-\x5E\x60\x7B~\x7F]{1,10})/u',$str,$matched);
		// print_r($matched);
		return isset($matched[1])?array_unique($matched[1]):array();
	}
}
