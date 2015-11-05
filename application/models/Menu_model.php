<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== 메뉴 관리 모델

class Menu_model extends CI_Model {
	public $menu_rows = null;
	public $menu_root = null;
	
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	
	public function load_db($tbl_nm='menu'){
		$this->menu_rows = $this->_get_menu_rows($tbl_nm);
		$this->menu_tree = $this->_mapping_menu_tree($this->menu_rows);
	}
	
	public function & get_menu_rows(){
		return $this->menu_rows;
	}
	public function & get_menu_tree(){
		return $this->menu_tree;
	}
	
	private function _get_menu_rows($tbl_nm='menu'){
		$rows = array();
		$row = array();
		$q = $this->db->from(DB_PREFIX.$tbl_nm)->where('mn_is_use',1)
		->order_by('mn_parent_uri')->order_by('mn_sort')
		->get();
		foreach ($q->result_array() as $row)
		{
			$rows[$row['mn_uri']] = $row;
		}
		// echo $this->db->last_query();
		$this->extends_menu_rows($rows);
		return $rows;
	}
	private function extends_menu_rows(& $rows){
		foreach($rows as & $r){
			if(isset($r['mn_etc'][0])){
				if($r['mn_etc'][0]=='{'){
					$tr = json_decode($r['mn_etc'],true);
					if($tr){
						$r = array_merge($r,$tr);
					}
				}
				$r['child']=array();
				
				$r['url']=base_url().$r['mn_uri'];

			}
		}
	}
	
	public function _mapping_menu_tree(& $menu_rows){
		$menu_tree = null;
		foreach($menu_rows as & $r){
			if($r['mn_uri']=='' && $r['mn_parent_uri']==''){
				$r['child']=array();
				$menu_tree = & $r;
			}
			if($r['mn_uri'] == $r['mn_parent_uri']){
				
			}else{
				if(isset($menu_rows[$r['mn_parent_uri']])){
					if(!isset($menu_rows[$r['mn_parent_uri']]['child'])){
						$menu_rows[$r['mn_parent_uri']]['child'] = array();
					}
					$menu_rows[$r['mn_parent_uri']]['child'][] = & $r;
					
				}
			}
		}
		return $menu_tree;
	}
	

}