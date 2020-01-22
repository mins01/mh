<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 배너 모델

class Banner_admin_model extends CI_Model {
	public $msg = '';
	public $tbl = '';
	public $fileds = array();
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();

		$this->tbl = 'mh_banners';

		$this->fileds = array(
			// 'bn_idx',
			'bn_title',
			'bn_base_node',
			'bn_left',
			'bn_top',
			'bn_width',
			'bn_height',
			'bn_z_index',
			'bn_isuse',
			'bn_type',
			'bn_content',
			'bn_date_st',
			'bn_date_ed',
			// 'bn_insert_date',
			// 'bn_update_date',
			// 'bn_isdel',
		);
	}

	public function insert_row($row){
		foreach($row as $k => $v){
			if(!in_array($k,$this->fileds)){
				unset($row[$k]);
			}
		}
		$sets = array(
			'bn_title'=>$row['bn_title'],
			'bn_base_node'=>$row['bn_base_node'],
			'bn_left'=>(int)$row['bn_left'],
			'bn_top'=>(int)$row['bn_top'],
			'bn_width'=>(int)$row['bn_width'],
			'bn_height'=>(int)$row['bn_height'],
			'bn_z_index'=>(int)$row['bn_z_index'],
			'bn_isuse'=>(int)$row['bn_isuse'],
			'bn_type'=>$row['bn_type'],
			'bn_content'=>$row['bn_content'],
			'bn_date_st'=>$row['bn_date_st'],
			'bn_date_ed'=>$row['bn_date_ed'],
		);
		$this->db->from($this->tbl)->set($sets)
			->set('bn_insert_date','now()',false)
			->set('bn_update_date','now()',false)
			->insert();
		return $this->db->insert_id();
	}
	public function update_row($bn_idx,$sets){
		foreach($sets as $k => $v){
			if(!in_array($k,$this->fileds)){
				unset($sets[$k]);
			}
		}
		$this->db->from($this->tbl)
			->where('bn_idx',$bn_idx)
			->where('bn_isdel',0)
			->set($sets)
			->set('bn_update_date','now()',false)
			->update();
		return $this->db->affected_rows();
	}
	public function select($wheres,$order_by='bn_idx desc, bn_z_index asc',$limit=20,$offset=0){
		$row = $this->db->from($this->tbl)
			->where($wheres)
			->where('bn_isdel',0)
			->order_by($order_by)
			->limit($limit,$offset)
			->get()->result_array();
		return $row;
	}
	public function empty_row(){
		return array(
		  'bn_idx'=>'',
		  'bn_title'=>'',
		  'bn_base_node'=>'',
		  'bn_left'=>'',
		  'bn_top'=>'',
		  'bn_width'=>'',
		  'bn_height'=>'',
		  'bn_z_index'=>'',
		  'bn_isuse'=>'',
		  'bn_type'=>'',
		  'bn_content'=>'',
		  'bn_date_st'=>'',
		  'bn_date_ed'=>'',
		  'bn_insert_date'=>'',
		  'bn_update_date'=>'',
		  'bn_isdel'=>'',
		);
	}

}
