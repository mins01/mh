<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_comment_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl_bbs_comment = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	public function hash($str){
		return md5($str);
	}
	public function set_bm_row($bm_row){
		$this->bm_row = $bm_row;
		//-- 테이블
		if(!isset($this->bm_row['bm_table'])){
			$this->error = '게시판 테이블 정보가 없습니다.';
			return false;
		}
		$this->tbl_bbs_comment = DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_comment';
	}
	//-- 목록과 카운팅용
	private function _apply_list_where($get){
		$this->db->from($this->tbl_bbs_comment);
		
		//-- 게시판 아이디
		if(!isset($get['b_idx'])){
			$this->error = '게시물 관리번호가 없습니다.';
			return false;
		}
		$this->db->where('b_idx',$get['b_idx']);
		
		//-- 필수 where절
		$this->db->where('bc_isdel','0');
		
		return true;

	}
	
	//페이지 값으로 limit와 offset 계산
	public function get_limit_offset($page){
		if(!isset($page) || !is_numeric($page) || $page < 0){
				$page = 1;
		}
		$page = (int)$page;
		//$limit = $this->bm_row['bm_page_limit'];
		$limit = 5;
		$offset = ($page-1)*$limit;
		return array($limit,$offset);
	}
	
	//목록용
	public function select_for_list($get){
		
		if(!$this->_apply_list_where($get)){
			return false;
		}

		//$this->db->order_by('bc_gidx,bc_gpos');
		$this->db->order_by('bc_idx');
		
		//list($limit,$offset) = $this->get_limit_offset($get['page']);
		//$this->db->limit($limit,$offset);

		$bc_rows = $this->db->get()->result_array();
		//echo $this->db->last_query();
		$this->extends_bc_rows($bc_rows);
		return $bc_rows;
	}
	
	private function extends_bc_rows(& $bc_rows){
		
		foreach($bc_rows as & $r){
			$this->extends_bc_row($r);
		}
	}
	private function extends_bc_row(& $bc_row){
		$b_row['depth']= min(strlen($bc_row['bc_gpos'])/2,10);
	}
	//-- 빈 게시물 만들기
	public function generate_empty_b_row(){
		// $sql="DESC {$this->tbl_bbs_comment}";
		// $rows = $this->db->query($sql)->result_array();
		// foreach($rows as $r){
		// echo "'{$r['Field']}'=>'',\n";
		// }
		// print_r($rows);
		$b_row = array(
			'b_idx'=>'',
			'b_id'=>$this->bm_row['b_id'],
			'b_gidx'=>'',
			'b_gpos'=>'',
			//'b_insert_date'=>'',
			//'b_update_date'=>'',
			'b_isdel'=>'0',
			'm_idx'=>'',
			'm_id'=>'',
			'b_name'=>'',
			'b_pass'=>'',
			'b_ip'=>$this->input->server('REMOTE_ADDR'),
			'b_notice'=>'0',
			'b_secret'=>'0',
			'b_html'=>'html',
			'b_link'=>'',
			'b_category'=>'',
			'b_title'=>'',
			'b_text'=>'',
			'b_etc_0'=>'',
			'b_etc_1'=>'',
			'b_etc_2'=>'',
			'b_etc_3'=>'',
			'b_etc_4'=>'',
		);
		return $b_row;
	}
	//-- 게시물 하나 b_idx로 가져오기
	public function select_by_b_idx($b_idx){
		$this->db->from($this->tbl_bbs_comment);
		
		//-- 게시판 아이디
		if(!isset($this->bm_row['b_id'])){
			$this->error = '게시판 아이디가 없습니다.';
			return false;
		}
		$this->db->where('b_id',$this->bm_row['b_id']);
		//-- 필수 where절
		return $this->db->where('b_isdel','0')->where('b_idx',$b_idx)->get()->row_array();
	}
	//-- 목록 갯수
	public function count($get){
		if(!$this->_apply_list_where($get)){
			return false;
		}

		return $this->db->count_all_results();
	}
	//-- 시작 번호 계산
	public function get_start_num($cnt,$get){
		list($limit,$offset) = $this->get_limit_offset($get['page']);
		return $cnt - $offset;
	}
	//-- 글 수정
	public function update_bc_row($bc_idx,$sets){
		unset($sets['bc_idx']);
		$this->db->from($this->tbl_bbs_comment)
		->where('bc_idx',$bc_idx)
		->where('bc_isdel',0)
		->set($sets)->set('bc_update_date','now()',false)->update();
		return $this->db->affected_rows();
	}
	//-- 글 작성
	public function insert_bc_row($sets){
		unset($sets['bc_idx']);
		if(isset($sets['bc_pass'][0])){
			$sets['bc_pass'] = $this->hash($sets['bc_pass']);
		}
		$this->db->from($this->tbl_bbs_comment)
		->set($sets)
		->set('bc_insert_date','now()',false)
		->set('bc_update_date','now()',false)->insert();
		$bc_idx = $this->db->insert_id();
		if($bc_idx){
			$this->update_bc_row($bc_idx,array('bc_gidx'=>-1*$bc_idx/100,'bc_pidx'=>$bc_idx));
		}
		
		return $bc_idx;
	}
	//-- 글 삭제
	public function delete_b_row($b_idx){
		return $this->update_b_row($b_idx,array('b_isdel'=>1));
	}
	//-- 답변 글 작성
	public function insert_answer_b_row($b_idx,$sets){
		unset($sets['b_idx']);
		$sets['b_id'] = $this->bm_row['b_id'];
		if(isset($sets['b_pass'][0])){
			$sets['b_pass'] = $this->hash($sets['b_pass']);
		}
		$v_b_idx = $this->db->escape((int)$b_idx);
		$sql_b_gidx = "(SELECT b_gidx from {$this->tbl_bbs_comment} bbsd1 WHERE bbsd1.b_idx = {$v_b_idx})";
		$sql_b_gpos =
"
CONCAT(
(SELECT bbsd1.b_gpos FROM {$this->tbl_bbs_comment}  bbsd1 WHERE bbsd1.b_idx = {$v_b_idx})
,
LPAD(
CONV(
IFNULL(
LEAST(36*36-1,

CAST(
CONV(
SUBSTR(

(SELECT 
bbsd2.b_gpos
FROM {$this->tbl_bbs_comment}  bbsd1
JOIN {$this->tbl_bbs_comment}  bbsd2 ON(bbsd2.b_gpos LIKE CONCAT(bbsd1.b_gpos,'__') AND bbsd2.b_gidx = bbsd1.b_gidx) 
WHERE bbsd1.b_idx = {$v_b_idx}
ORDER BY b_gpos DESC LIMIT 1)

,-2,2) 
,36,10)
AS SIGNED )+1

)#LEAST(36*36-1,
,'00')#IFNULL(
,10,36)
,2,0)
)
";
		
		
		$this->db->from($this->tbl_bbs_comment)
		->set($sets)
		->set('b_gidx',$sql_b_gidx,false)
		->set('b_gpos',$sql_b_gpos,false)
		->set('b_pidx',$b_idx)
		->set('b_insert_date','now()',false)
		->set('b_update_date','now()',false)->insert();

		$b_idx = $this->db->insert_id();
		

		return $b_idx;
	}
}