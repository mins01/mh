<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Crlud_model extends CI_Model {
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	
	public function create($from,$sets){
		return $this->db->from($from)->set($sets)->insert();
	}
	public function read($from,$select='*',$wheres=array(),$group_by='',$order=''){
		
	}
	public function lists($from,$select='*',$wheres=array(),$group_by='',$order='',$limit=10,$offset=0){
		return $this->db->from($from)->select($select)->where($wheres)->group_by($group_by)->order_by($order)->limit($limit,$offset)->get()->result_array();
	}
	public function update($from,$wheres,$sets){
		if(count($wheres)==0){
			return false;
		}
		if(count($sets)==0){
			return false;
		}
		return $this->db->from($from)->where($wheres)->set($sets)->update();
	}
	public function delete($from,$wheres){
		
	}
	public function show_columns($from){
		$sql = "SHOW FULL COLUMNS FROM `{$from}`";
		$rows = $this->db->query($sql)->result_array();
		$r_rows = array();
		foreach ($rows as $row) {
			$r_rows[$row['Field']]=$row;
		}
		return $r_rows;
	}
	
}
