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
	public function tblname($tblname,$alias=''){
		//return DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_'.$tblname.(isset($alias[0])?' as '.$alias:'');
		if(isset($this->bm_row['tbl_'.$tblname])){
			return $this->bm_row['tbl_'.$tblname].(isset($alias[0])?' as '.$alias:'');
		}
		return null;
	}
	//=== select
	public function select_by_bt_idx($bt_idx)
	{
		$select = "bt.*";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('bt_idx',(int)$bt_idx)->where('bt_isdel',0)->get()->result_array();
	}
	public function select_by_bt_tag($bt_tag)
	{
		$select = "bt.b_idx";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('bt_tag',$bt_tag)->where('bt_isdel',0)->get()->result_array();
	}
	public function select_by_b_idx($b_idx)
	{
		$select = "bt.bt_tag";
		return $this->db->select($select)->from($this->tbl.'  bt')->where('b_idx',(int)$b_idx)->where('bt_isdel',0)->get()->result_array();
	}
	public function bt_tags_by_b_idx($b_idx)
	{
		$select = "bt.bt_tag";
		$rows = $this->db->select($select)->from($this->tbl.'  bt')->where('b_idx',(int)$b_idx)->where('bt_isdel',0)->get()->result_array();
		$rs = array();
		foreach($rows as $r){
			$rs[]=$r['bt_tag'];
		}
		return $rs;
	}
	public function bt_tags_by_b_id($b_id)
	{
		$tbl_b = $this->tblname('data');
		$v_b_id = $this->db->escape($b_id);
		$sql = "SELECT bt.bt_tag FROM `{$this->tbl}` bt JOIN `{$tbl_b}` b ON(bt.b_idx = b.b_idx AND b.b_id={$v_b_id})
						GROUP BY bt_tag
						ORDER BY bt_tag";
		return $this->db->query($sql)->result_array();
	}
	//=== insert
	public function insert($b_idx,$bt_tag)
	{
		$this->db->from($this->tbl)
		->set('b_idx',(int)$b_idx)
		->set('bt_tag',$bt_tag)
		->set('bt_update_date','now()',false);
		// ->insert();		
		$sql = $this->db->get_compiled_insert() . ' ON DUPLICATE KEY UPDATE bt_isdel=0';
		$this->db->query($sql);
		return $this->db->insert_id();
	}	
	//=== delete
	public function delete_by_b_idx($b_idx,$bt_tags=array())
	{
		if(count($bt_tags)>0){
			$this->db->where_not_in('bt_tag',$bt_tags);
		}
		return $this->db->from($this->tbl)->set('bt_isdel',1)->where('bt_isdel',0)->where('b_idx',(int)$b_idx)->update();
	}
	//=== util 
	/**
	 * pickup_tags 문자열에서 #~~~ 를 추출
	 * @param  [type] $str [description]
	 * @return [type]      [description]
	 */
	public function pickup_tags($str){
		$str = preg_replace ('/<[^>]*>/', ' ', $str);
		$matched = array();
		preg_match_all('/(?:#)([^\t\s\n\x00-\x2C\x2E-\x2F\x3A-\x40\x5B-\x5E\x60\x7B~\x7F]{1,30})/u',$str,$matched);
		// print_r($matched);
		return isset($matched[1])?array_map('strtolower',array_unique($matched[1])):array();
	}
	/**
	 * 문자열에서 빈칸이나 , 등을 기준으로 태그를 추출
	 * @param  [type] $bt_tags_string [description]
	 * @return [type]                 [description]
	 */
	public function split_tags_string($bt_tags_string){
		$matched = array();
		preg_match_all('/([^#\t\s\n\x00-\x2C\x2E-\x2F\x3A-\x40\x5B-\x5E\x60\x7B~\x7F]{1,30})/u',strtolower($bt_tags_string),$matched);
		return isset($matched[1])?array_unique($matched[1]):array();
	}
}
