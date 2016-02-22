<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 유닛

class Sdgn_etc_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	
	public function select_comment_for_main(){
		$sql = "SELECT bc_name,COUNT(*) cnt FROM mh_bbs_sdgnunits_comment bc
				WHERE bc_isdel = 0 AND bc_number > 0
				GROUP BY bc_name
				ORDER BY cnt DESC
				LIMIT 10
			";
		return $this->db->query($sql)->result_array();
		
	}
	public function select_units_for_main(){
		$select_avg_star = "(SELECT AVG(bc_number) avg_star FROM mh_bbs_sdgnunits_comment bc WHERE b_idx= su.unit_idx and bc.bc_isdel =0 AND bc_number>0)";
		$sql = "	SELECT 
				*, {$select_avg_star} as avg_star
				FROM(SELECT b_idx unit_idx,max(bc_insert_date) max_bc_insert_date,COUNT(*) cnt FROM mh_bbs_sdgnunits_comment bc
				WHERE bc_isdel = 0 AND bc_number > 0
				GROUP BY b_idx
				ORDER BY max_bc_insert_date DESC , cnt DESC
				LIMIT 10) bc
				JOIN sdgn_units su USING(unit_idx)
			";
		return $this->db->query($sql)->result_array();
	}
	
	public function select_last_comment_for_main(){
		$sql = "	SELECT 
				*
				FROM(SELECT *,b_idx unit_idx FROM mh_bbs_sdgnunits_comment
				WHERE bc_isdel = 0
				ORDER BY bc_idx DESC
				LIMIT 10) bc
				JOIN sdgn_units su USING(unit_idx)
			";
		return $this->db->query($sql)->result_array();
	}
	
	public function count_units(){
		$sql ="select count(*) CNT from sdgn_units";
		$rows = $this->db->query($sql)->result_array();
		return $rows[0]['CNT'];
	}
	
	public function count_skills(){
		$sql ="SELECT COUNT(DISTINCT nn) CNT FROM (SELECT unit_skill1 nn FROM `sdgn_units`
				UNION ALL
				SELECT unit_skill2 FROM `sdgn_units`
				UNION ALL
				SELECT unit_skill3 FROM `sdgn_units`) A";
		$rows = $this->db->query($sql)->result_array();
		return $rows[0]['CNT'];
	}
	
	public function count_comments(){
		$sql ="SELECT COUNT(*) CNT FROM mh_bbs_sdgnunits_comment bc
		where bc.bc_isdel=0";
		$rows = $this->db->query($sql)->result_array();
		return $rows[0]['CNT'];
	}
	public function count_comment_users(){
		$sql ="SELECT COUNT(DISTINCT m_idx) CNT FROM mh_bbs_sdgnunits_comment bc
		where bc.bc_isdel=0";
		$rows = $this->db->query($sql)->result_array();
		return $rows[0]['CNT'];
	}
	


	
}