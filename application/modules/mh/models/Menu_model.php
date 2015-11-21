<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== 메뉴 관리 모델

class Menu_model extends CI_Model {
	public $menu_rows = null;
	public $menu_root = null;
	public $menu_depth = array();
	public $tbl_nm='menu';
	public $pre_uri='';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	public function set_init_conf($tbl_nm='menu',$pre_uri=''){
		$this->tbl_nm = $tbl_nm;
		$this->pre_uri = $pre_uri;
	}
	public function load_db($tbl_nm='menu',$pre_uri=''){
		$this->set_init_conf($tbl_nm,$pre_uri);
		$this->menu_rows = $this->_get_menu_rows();
		$this->menu_tree = $this->_mapping_menu_tree($this->menu_rows);
	}
	
	public function & get_menu_rows(){
		return $this->menu_rows;
	}
	public function & get_menu_tree(){
		return $this->menu_tree;
	}
	
	public function get_current_menu($uri){

		foreach($this->menu_rows as & $r){
			if($r['mn_uri'] == $uri){
				$r['active']=true;
				foreach($r['breadcrumbs'] as $mn_id){
					if(isset($this->menu_rows[$mn_id])){
						$this->menu_rows[$mn_id]['active']=true;
					}
					
				}
				return $r;
			}
		}
		return null;
	}
	
	private function _get_menu_rows(){
		$rows = array();
		$row = array();
		$q = $this->db->from(DB_PREFIX.$this->tbl_nm)->where('mn_use',1)
		->order_by('mn_sort,mn_parent_id')
		->get();
		
		foreach ($q->result_array() as $row)
		{
			$rows[$row['mn_id']] = $row;
		}
		$this->extends_menu_rows($rows);
		// echo $this->db->last_query();
		return $rows;
	}
	private function extends_menu_rows(& $rows){
		foreach($rows as & $r){
			$r['url']=str_replace('//','/',$this->pre_uri.$r['mn_url']);
			$r['active']=false;
			$r['child']=array();
			$r['breadcrumbs']=array();
			if(isset($r['mn_etc'][0])){
				if($r['mn_etc'][0]=='{'){
					$tr = json_decode($r['mn_etc'],true);
					if($tr){
						$r = array_merge($r,$tr);
					}
				}
			}
		}
		unset($r);
		//menu-link
		foreach($rows as & $r){
			$tr = $r;
			$t = array();
			while(isset($tr)){
				$t[] = $tr['mn_id'];
				if($tr['mn_id'] == $tr['mn_parent_id']){
					$tr = null;
				}else if(isset($rows[$tr['mn_parent_id']])){
					$tr = $rows[$tr['mn_parent_id']];
				}else{
					$tr = null;
				}
				

			}
			$r['breadcrumbs'] = array_reverse($t);
		}
	}
	
	public function _mapping_menu_tree(& $menu_rows){
		$menu_tree = array();
		foreach($menu_rows as & $r){
			if($r['mn_id'] == $r['mn_parent_id']){
				$r['child']=array();
				$menu_tree[] = & $r;
			}
			if($r['mn_id'] == $r['mn_parent_id']){
				
			}else{
				if(isset($menu_rows[$r['mn_parent_id']])){
					if(!isset($menu_rows[$r['mn_parent_id']]['child'])){
						$menu_rows[$r['mn_parent_id']]['child'] = array();
					}
					$menu_rows[$r['mn_parent_id']]['child'][] = & $r;
					
				}
			}
		}
		return $menu_tree;
	}
	
	public function select($tbl_nm='menu',$pre_uri=''){
		$rows = array();
		$row = array();
		$q = $this->db->from(DB_PREFIX.$this->tbl_nm)
		->order_by('mn_sort,mn_parent_id')
		->get();
		
		foreach ($q->result_array() as $row)
		{
			$rows[$row['mn_id']] = $row;
		}
		$this->extends_menu_rows($rows);
		// echo $this->db->last_query();
		return $rows;
	}
	
	public function insert($sets){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->set($sets)
		->set('mn_insert_date','now()',false)
		->set('mn_update_date','now()',false)
		->insert();
		return $this->db->affected_rows();
	}
	public function update($wheres,$sets){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->set($sets)
		->set('mn_update_date','now()',false)
		->where($wheres)
		->update();
		return $this->db->affected_rows();
	}
	public function delete($wheres){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->where($wheres)
		->delete();
		return $this->db->affected_rows();
	}
	public function count($wheres){
		return $this->db->from(DB_PREFIX.$this->tbl_nm)->where($wheres)->count_all_results();
	}

}