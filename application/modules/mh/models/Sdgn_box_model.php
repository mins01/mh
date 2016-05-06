<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 럭키상자

class Sdgn_box_model extends CI_Model {
	
	public function get_box_lists(){
		$sql="SELECT * FROM sdgn_box
		where sb_isdel=0
		ORDER BY sb_type desc,sb_sort desc";
		return $this->db->query($sql)->result_array();
	}
	
	public function get_box_by_unit_idx($unit_idx){
		$v_unit_idx = $this->db->escape((int)$unit_idx);
		$sql="SELECT *,
		CASE sb_type WHEN 1 THEN '럭키상자' WHEN 10 THEN '기타' ELSE '-' END AS sb_type_label
		FROM sdgn_units_in_box suib
		JOIN sdgn_box sb USING(sb_idx)
		WHERE suib.unit_idx = {$v_unit_idx}
		ORDER BY sb_type desc,sb_sort desc
		";
		return $this->db->query($sql)->result_array();
	}
	
	public function save_box($in_data){
		if(isset($in_data['sb_idx']) && !empty($in_data['sb_idx'])){
			return $this->update_box($in_data['sb_idx'],$in_data);
		}else{
			return $this->insert_box($in_data);
		}
	}
	public function update_box($sb_idx,$in_data){
		unset($in_data['sb_idx']);
		$this->db->from('sdgn_box')->where('sb_idx',(int)$sb_idx);
		$this->db->set($in_data);
		$this->db->update();
		return $sb_idx;
	}
	public function insert_box($in_data){
		unset($in_data['sb_idx']);
		$this->db->from('sdgn_box');
		$this->db->set($in_data);
		$this->db->insert();
		return $this->db->insert_id();
	}
	public function delete_box($sb_idx){
		$this->db->from('sdgn_box')->where('sb_idx',(int)$sb_idx);
		$this->db->set('sb_isdel',1);
		$this->db->update();
		return $sb_idx;
	}
	
	//-- 유닛 추가 관련
	public function save_unit($in_data){
		if(isset($in_data['suib_idx']) && !empty($in_data['suib_idx'])){
			return $this->update_unit($in_data['suib_idx'],$in_data);
		}else{
			return $this->insert_unit($in_data);
		}
	}
	public function update_unit($suib_idx,$in_data){
		unset($in_data['suib_idx']);
		$this->db->from('sdgn_units_in_box')->where('suib_idx',(int)$suib_idx);
		$this->db->set($in_data);
		$this->db->update();
		return $suib_idx;
	}
	public function insert_unit($in_data){
		unset($in_data['suib_idx']);
		$this->db->from('sdgn_units_in_box');
		$this->db->set($in_data);
		$this->db->insert();
		return $this->db->insert_id();
	}
	public function delete_unit($suib_idx){
		$this->db->from('sdgn_units_in_box')->where('suib_idx',(int)$suib_idx);
		$this->db->set('suib_isdel',1);
		$this->db->update();
		return $suib_idx;
	}
}