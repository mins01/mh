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
	public function select(){
		return $this->_select(null,'unit_idx DESC','*');
	}
	public function count(){
		$su_rows = $this->_select(null,null,'count(*) as CNT');
		return $su_rows[0]['CNT'];
	}
	private function _select($wheres=null,$order_by=null,$select='*'){
		$this->db->from('sdgn_unit su')->select($select);
		if(isset($wheres)){
			$this->db->where($wheres);
		}
		if(isset($order_by)){
			$this->db->order_by($order_by);
		}
		return $this->db->get()->result_array();
	}
}