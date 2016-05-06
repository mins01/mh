<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 유닛

class Sdgn_etc_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	private $select_avg_star = "(SELECT AVG(bc_number) avg_star FROM mh_bbs_sdgnunits_comment bc WHERE b_idx= su.unit_idx and bc.bc_isdel =0 AND bc_number>0)";
	
	public function select_comment_for_main(){
		$sql = "SELECT bc_name,COUNT(*) cnt FROM mh_bbs_sdgnunits_comment bc
				JOIN sdgn_units su on(su.unit_idx=bc.b_idx and su.unit_isdel=0)
				WHERE bc_isdel = 0 
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
				WHERE bc_isdel = 0 
				GROUP BY b_idx
				ORDER BY max_bc_insert_date DESC , cnt DESC
				LIMIT 20) bc
				JOIN sdgn_units su on(su.unit_idx=bc.unit_idx and su.unit_isdel=0)
				LIMIT 10
			";
		return $this->db->query($sql)->result_array();
	}
	
	public function select_last_comment_for_main(){
		$sql = "	SELECT 
				*
				FROM(SELECT *,b_idx unit_idx FROM mh_bbs_sdgnunits_comment
				WHERE bc_isdel = 0
				ORDER BY bc_idx DESC
				LIMIT 20) bc
				JOIN sdgn_units su on(su.unit_idx=bc.unit_idx and su.unit_isdel=0)
				LIMIT 10
			";
		return $this->db->query($sql)->result_array();
	}
	
	public function count_units(){
		$sql ="select count(*) CNT from sdgn_units where unit_isdel=0";
		$rows = $this->db->query($sql)->result_array();
		return $rows[0]['CNT'];
	}
	
	public function count_skills(){
		$sql ="SELECT COUNT(DISTINCT nn) CNT FROM (SELECT unit_skill1 nn FROM `sdgn_units` su where su.unit_isdel=0
				UNION ALL
				SELECT unit_skill2 FROM `sdgn_units` su where su.unit_isdel=0
				UNION ALL
				SELECT unit_skill3 FROM `sdgn_units` su where su.unit_isdel=0) A";
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
	public function select_for_last_comments($limit=10){
		$sql = "SELECT *,{$this->select_avg_star} as avg_star FROM (SELECT * FROM mh_bbs_sdgnunits_comment bc
						WHERE bc.bc_isdel = 0
						ORDER BY bc_insert_date desc
						LIMIT {$limit}) bc
						JOIN sdgn_units su on(su.unit_idx=bc.b_idx and su.unit_isdel=0)
						LIMIT {$limit}
		";
		return $this->db->query($sql)->result_array();
	}
	
	public function select_for_last_update_weapon($limit=10){
		$sql = "SELECT svw.* , su.unit_name,unit_properties_num , m_nick FROM `sdgn_view_weapons` svw
						JOIN sdgn_units su USING(unit_idx)
						LEFT JOIN mh_member m using(m_idx)
						where swa_update_date is not null
						ORDER BY swa_update_date DESC
						LIMIT {$limit}
		";
		return $this->db->query($sql)->result_array();
	}
	
	public function select_for_plan($plan_dt_st,$plan_dt_ed){
		$sql = "SELECT b_idx,b_category,b_title,b_etc_0,b_etc_1 FROM mh_bbs_sdgn_data
						WHERE 
						b_etc_0 <= '{$plan_dt_ed}' AND b_etc_1 >= '{$plan_dt_st}'
						AND b_id = 'sdgn_plan'
						AND b_isdel = 0
						ORDER BY b_etc_1
		";
		return $this->db->query($sql)->result_array();
	}


	
}