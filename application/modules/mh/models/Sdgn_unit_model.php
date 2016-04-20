<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 유닛

class Sdgn_unit_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	private $select_avg_star = "IFNULL((SELECT AVG(bc_number) avg_star FROM mh_bbs_sdgnunits_comment bc WHERE b_idx= su.unit_idx and bc.bc_isdel =0 AND bc_number>0),0)";
	private $fields= array(
		'b_idx','b_id','b_gidx','b_gpos','b_pidx',
		//'b_insert_date','b_update_date',
		//'b_isdel',
		'm_idx','b_name','b_pass',
		//'b_ip',
		'b_notice','b_secret','b_html','b_link','b_category',
		'b_title','b_text',
		'b_etc_0','b_etc_1','b_etc_2','b_etc_3','b_etc_4',
		'b_num_0','b_num_1','b_num_2','b_num_3','b_num_4',
	);
	private function attach_where($sh){
		if(isset($sh['unit_name'][0])){
			$this->db->like('unit_name',$sh['unit_name']);
		}
		if(isset($sh['unit_ranks'][0])){
			$this->db->where_in('unit_rank',$sh['unit_ranks']);
		}
		
		if(isset($sh['unit_properties_nums'][0])){
			$this->db->where_in('unit_properties_num',$sh['unit_properties_nums']);
		}
		if(isset($sh['unit_is_weapon_change'][0])){
			$this->db->where('unit_is_weapon_change',(int)$sh['unit_is_weapon_change']);
		}
		if(isset($sh['unit_is_transform'][0])){
			$this->db->where('unit_is_transform',(int)$sh['unit_is_transform']);
		}
	}
	public function count_for_lists($sh=array()){
		$this->attach_where($sh);
		$rows = $this->_select(null,'unit_idx DESC','count(*) CNT');
		return $rows[0]['CNT'];
	}
	public function select_for_lists($sh=array()){
		$this->attach_where($sh);
		return $this->_select(null,'unit_idx DESC','su.*');
	}
	public function select_by_unit_idx($unit_idx){
		$su_rows = $this->_select(array('unit_idx'=>$unit_idx),null,'*');
		return isset($su_rows[0])?$su_rows[0]:null;
	}
	public function count(){
		$su_rows = $this->_select(null,null,'count(*) as CNT');
		return $su_rows[0]['CNT'];
	}
	private function _select($wheres=null,$order_by=null,$select='*'){
		$select .=",{$this->select_avg_star} as avg_star";
		$this->db->from('sdgn_units su')->select($select,false);
		if(isset($wheres)){
			$this->db->where($wheres);
		}
		$this->db->where('unit_isdel',0);
		if(isset($order_by)){
			$this->db->order_by($order_by);
		}
		return $this->db->get()->result_array();
	}
	
}