<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 유닛

class Sdgn_unit_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
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
	public function select_for_lists(){
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
		$select_avg_star = "(SELECT AVG(bc_number) avg_star FROM mh_bbs_sdgnunits_comment bc WHERE b_idx= su.unit_idx and bc.bc_isdel =0 AND bc_number>0)";
		$select .=",{$select_avg_star} as avg_star";
		$this->db->from('sdgn_unit su')->select($select,false);
		if(isset($wheres)){
			$this->db->where($wheres);
		}
		if(isset($order_by)){
			$this->db->order_by($order_by);
		}
		return $this->db->get()->result_array();
	}
}