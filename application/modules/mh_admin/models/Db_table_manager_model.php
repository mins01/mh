<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Db_table_manager_model extends CI_Model {
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
		$this->config->load('bbs');
		$conf_bbs = $this->config->item('bbs');
		$this->file_dir = $conf_bbs['file_dir'];
	}
	
	public function show_tables(){
		$rows = $this->db->query('show tables')->result_array();
		foreach($rows as & $r){
			$r = current($r);
		}
		sort($rows);
		return $rows;
	}
	
	public function select($from,$wheres=array(),$order_by='',$limit=100,$offset=0){
		return $this->db->from($from)->where($wheres)->order_by($order_by)->limit($limit,$offset)->get()->result_array();
	}
	public function update($from,$wheres=array(),$sets=array()){
		$this->db->from($from)
		->where($wheres)
		->set($sets)->update();
		return $this->db->affected_rows();
	}
	
	public function show_columns($form){
		$sql = "SHOW FULL COLUMNS FROM `{$form}`";
		return $this->db->query($sql)->result_array();
	}
}





















