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
		$this->load->library('mh_cache');
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
		$max = -1;
		$curr_r = null;
		$pu0 = parse_url($uri);
		$pu0parr = explode('/',isset($pu0['path'][0])?$pu0['path']:'');
		// print_r($pu0parr);
		foreach($this->menu_rows as & $r){

			// echo "{$uri}!={$r['mn_uri']} \n";
			$len_uri = strlen($uri);
			$len_mn_uri = strlen($r['mn_uri']);

			$pu1 = parse_url($r['mn_uri']);
			// var_dump($pu1['path']);
			$pu1parr = explode('/',isset($pu1['path'][0])?$pu1['path']:'');
			// print_r($pu1parr);
			$skip = false;
			for($i1=0,$m1=count($pu1parr);$i1<$m1;$i1++){
				if(!isset($pu0parr[$i1]) || $pu1parr[$i1] !== $pu0parr[$i1]){
					$skip = true;
					continue;
				}
				// echo $pu1parr[$i1],' ',$pu0parr[$i1],"\n";
			}
			if($skip){
				continue;
			}
			//
			// if($len_uri==$len_mn_uri && $uri===$r['mn_uri']){
			//
			// }else if($len_uri > $len_mn_uri && strpos($uri,$r['mn_uri'].'/')===0){
			//
			// }else if(empty($r['mn_uri']) || strpos($uri,$r['mn_uri'])!==0){
			// 	continue;
			// }
			// echo $uri;
			// print_r($r);

			if(strlen($r['mn_uri'])>$max){
				$max = strlen($r['mn_uri']);
				$curr_r= &$r;
			}
		}
		// print_r($r);
		if(isset($curr_r)){
			foreach($curr_r['breadcrumbs'] as $mn_id){
				if(isset($this->menu_rows[$mn_id])){
					$this->menu_rows[$mn_id]['active']=true;
				}

			}
			return $curr_r;
		}
		return null;
	}
	private function cache_key(){
		return __CLASS__.'.'.DB_PREFIX.$this->tbl_nm;
	}
	private function cache_clear(){
		$key = $this->cache_key();
		$this->mh_cache->delete($key);
	}
	private function _get_menu_rows(){
		$key = $this->cache_key();
		$rows = $this->mh_cache->get($key);

		if(!$rows){
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
			$this->mh_cache->save($key,$rows,60*60); //1시간
		}


		return $rows;
	}
	private function extends_menu_rows(& $rows){
		foreach($rows as & $r){
			if(strpos($r['mn_url'],'/')===0){
				$r['url']=$r['mn_url'];
			}else{
				$r['url']=str_replace('//','/',$this->pre_uri.$r['mn_url']);
			}

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
				if(!isset($r['child'])) $r['child']=array();
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

	public function select(){
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
		return array_values($rows);
	}

	public function insert($sets){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->set($sets)
		->set('mn_insert_date','now()',false)
		->set('mn_update_date','now()',false)
		->insert();
		//return $this->db->affected_rows();
		$this->cache_clear();
		return $this->db->insert_id();
	}
	public function update($wheres,$sets){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->set($sets)
		->set('mn_update_date','now()',false)
		->where($wheres)
		->update();
		$this->cache_clear();
		return $this->db->affected_rows();
	}
	public function delete($wheres){
		$this->db->from(DB_PREFIX.$this->tbl_nm)
		->where($wheres)
		->delete();
		$this->cache_clear();
		return $this->db->affected_rows();
	}
	public function count($wheres){
		return $this->db->from(DB_PREFIX.$this->tbl_nm)->where($wheres)->count_all_results();
	}

}
