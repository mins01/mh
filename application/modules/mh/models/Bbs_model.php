<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_model extends CI_Model {
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
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	public function hash($str){
		return md5($str);
	}
	public function tblname($tblname,$alias=''){
		//return DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_'.$tblname.(isset($alias[0])?' as '.$alias:'');
		if(isset($this->bm_row['tbl_'.$tblname])){
			return $this->bm_row['tbl_'.$tblname].(isset($alias[0])?' as '.$alias:'');
		}
		return null;
	}
	public function set_bm_row($bm_row){
		$this->bm_row = $bm_row;
		//-- 테이블
		if(!isset($this->bm_row['bm_table'])){
			$this->error = '게시판 테이블 정보가 없습니다.';
			return false;
		}
		//$this->tbl = DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_data';
		$this->tbl = $this->tblname('data');
	}
	//-- post에서 필요 값만 가져옴. (insert,update 할때 꼭 체크)
	//b_ip 등은 자동으로 값을 채워준다.
	public function filter_vals(& $post){
		foreach($post as $k => $v){
			if(!in_array($k,$this->fields)){
				unset($post[$k]);
			}
		}
		$post['b_ip'] = $this->input->server('REMOTE_ADDR');
	}
	//-- bm_row에 따른 값에 따라서 목록 쿼리 부분을 변경시킨다.
	public function _apply_list_bm_row($bm_row,$select='',$no_bh_hit_cnt=false){
		// select 부분 설정.
		if(!isset($select[0])){
			$select = 'b.b_idx,b_id,b_gidx,b_gpos,b_pidx,b_insert_date,b_update_date,b_isdel
			,b.m_idx
			,b_name,b_ip,b_notice,b_secret,b_html,b_link,b_category,b_title
			,b_etc_0,b_etc_1,b_etc_2,b_etc_3,b_etc_4
			,b_num_0,b_num_1,b_num_2,b_num_3,b_num_4';
		}
		// switch($bm_row['bm_list_type']){
			// case '0': //일반 게시물

			// break;
			// case '1': //전체 본문 포함 게시물
				// $select.=',b_text';
			// break;
			// case '2': //부분 본문 포함 게시물
				// $select.=',substr(b_text,1,100) b_text';
			// break;
		// }
		// 첨부파일 사용중인가?
		if($bm_row['bm_use_file']=='1'){
			$select.=',(select count(*) from '.$this->tblname('file','bf2').' where bf2.b_idx=b.b_idx and bf_isdel=0) as bf_cnt';
		}
		// 리플 사용중인가?
		if($bm_row['bm_use_comment']=='1'){
			$select.=',(select count(*) from '.$this->tblname('comment','bc2').' where bc2.b_idx=b.b_idx and bc_isdel=0) as bc_cnt';
		}
		// 조인 부분
		if($bm_row['bm_use_thumbnail']=='1'){
			$this->db->join($this->tblname('file','bf'),'bf.b_idx=b.b_idx and bf_isdel=0 and bf_represent = 1','left');
			$select.=',bf.bf_idx,bf.bf_name,bf.bf_save,bf.bf_size,bf.bf_type,bf.bf_represent';
		}
		// 조회수
		if(!$no_bh_hit_cnt){
			$select.=",(select IFNULL(SUM(bh_hit_cnt),0) from ".$this->tblname('hit','bh')." where bh.bh_parent_idx=b.b_idx and bh.bh_parent_table='data') as bh_cnt";
		}
		// 코멘트 별점을 사용할 경우
		if($bm_row['bm_use_commnet_number']=='1'){
			$select.=",(select IFNULL(AVG(bc_number),0) from ".$this->tblname('comment','bc')." where bc.b_idx=b.b_idx and bc.bc_isdel=0 and bc.bc_number>0) as avg_bc_number";
		}
		
		//-- 마지막 처리
		$this->db->select($select);
	}	
	
	//-- 목록과 카운팅용
	private function _apply_list_where($get){
		$this->db->from($this->tbl.' as b');
		
		//-- 게시판 아이디
		if(!isset($this->bm_row['b_id'])){
			$this->error = '게시판 아이디가 없습니다.';
			return false;
		}
		$this->db->where('b_id',$this->bm_row['b_id']);
		//-- 필수 where절
		$this->db->where('b_isdel','0');
		
		//-- 검색어
		
		if(isset($get['q'][0]) && strlen(trim($get['q']))>0){
			$get['q'] = trim($get['q']);
			$v_q = preg_split('/\s+/',$get['q']);
			switch($get['tq']){
				case 'title':
					$this->db->group_start();
					foreach($v_q as $v){
						$this->db->like('b_title',$v, 'both');
					}
					$this->db->group_end();
				break;
				case 'text':
					$this->db->group_start();
					foreach($v_q as $v){
						$this->db->like('b_text',$v, 'both');
					}
					$this->db->group_end();
				break;
				case 'title_or_text':
					$this->db->group_start();
						$this->db->or_group_start();
						foreach($v_q as $v){
							$this->db->like('b_title',$v, 'both');
						}
						$this->db->group_end();
						$this->db->or_group_start();
						foreach($v_q as $v){
							$this->db->like('b_text',$v, 'both');
						}
						$this->db->group_end();
					$this->db->group_end();
				break;
			}
		}
		//-- 카테고리
		if(isset($get['ct'][0])){
			$this->db->where('b_category',$get['ct']);
		}
		return true;

	}
	
	//페이지 값으로 limit와 offset 계산
	public function get_limit_offset($page){
		if(!isset($page) || !is_numeric($page) || $page < 0){
				$page = 1;
		}
		$page = (int)$page;
		$limit = $this->bm_row['bm_page_limit'];
		$offset = ($page-1)*$limit;
		return array($limit,$offset);
	}
	
	//일반 목록용
	public function select_for_list($get,$order_by=null){
		
		if(!$this->_apply_list_where($get)){
			return false;
		}
		$this->_apply_list_bm_row($this->bm_row);

		//-- 정렬
		if(!isset($order_by[0])){
			switch($this->bm_row['bm_list_type']){
				case '0':$this->db->order_by('b_gidx,b_gpos');break;
				case '1':$this->db->order_by('b.b_idx desc');break;
			}
		}else{
			$this->db->order_by($order_by);
		}
		list($limit,$offset) = $this->get_limit_offset($get['page']);
		$this->db->limit($limit,$offset);

		$b_rows = $this->db->get()->result_array();
		$this->extends_b_rows($b_rows);
		return $b_rows;
	}
	
	//-- 목록 갯수
	public function count_for_calendar($get){
		if(!$this->_apply_list_where($get)){
			return false;
		}
		if(!isset($get['date_ed']) || !isset($get['date_st'])){
			return false;
		}
		$this->_apply_list_bm_row($this->bm_row);
		$this->db->where('b_etc_0 <=',$get['date_ed'])->where('b_etc_1 >=',$get['date_st']);
		return $this->db->count_all_results();
	}
	//-- 목록 갯수
	public function count_per_month_for_calendar($get){

		if(!$this->_apply_list_where($get)){
			return false;
		}
		if(!isset($get['date_ed']) || !isset($get['date_st'])){
			return false;
		}
		$this->_apply_list_bm_row($this->bm_row,'substr(b_etc_0,1,7) as yyyymm,count(*) as cnt',true);
		$this->db->where('b_etc_0 <=',$get['date_ed'])
		->where('b_etc_1 >=',$get['date_st'])
		->group_by('substr(b_etc_0,1,7)');
		$rows = $this->db->get()->result_array();
		$rowss = array();
		foreach($rows as $r){
			$rowss[$r['yyyymm']] = $r['cnt'];
		}
		return $rowss;
	}
	//달력 목록용
	public function select_for_calendar($get){
		if(!$this->_apply_list_where($get)){
			return false;
		}
		if(!isset($get['date_ed']) || !isset($get['date_st'])){
			return false;
		}
		
		$this->_apply_list_bm_row($this->bm_row);

		
		$this->db->where('b_etc_0 <=',$get['date_ed'])->where('b_etc_1 >=',$get['date_st']);
		$this->db->order_by('b.b_etc_0,b.b_etc_1');
		//list($limit,$offset) = $this->get_limit_offset($get['page']);
		//$this->db->limit($limit,$offset);

		$b_rows = $this->db->get()->result_array();
		$this->extends_b_rows($b_rows);
		return $b_rows;
	}
	public function exnteds_b_rows_for_calendar(& $b_rows,$date_st,$date_ed){
		$b_rowss = array();
		$b_rowss['maxlength'] = 0;
		$orders = array();
		$b_etc_1s = array();
		$time_st = strtotime($date_st);
		$time_ed = strtotime($date_ed);
		$w_st = date('w',$time_st);
		$time_st = $time_st-$w_st*86400;
		while($time_st<=$time_ed){
			$t_a = date('Y-m-d',$time_st);
			$t_b = date('Y-m-d',$time_st+86400*6);
			$time_st+=86400*7;
			//$b_rowss[$t_a]= array();
			foreach($b_rows as & $b_row){
				if($b_row['b_etc_1']>=$t_a && $b_row['b_etc_0']<=$t_b){
					
					if($t_a<$b_row['b_etc_0']){
						$v_dt_st = $b_row['b_etc_0'];
					}else{
						$v_dt_st = $t_a;
					}
					
					// 기간 지난 것 삭제
					unset($orders[$b_row['b_idx']]);
					foreach($orders as $v_b_idx => $v_order){
						if($b_etc_1s[$v_b_idx]<$v_dt_st){
							unset($orders[$v_b_idx]);
						}
					}
					//순서 찾기
					if(!isset($orders[$b_row['b_idx']])){
						if(count($orders)==0){
							$orders[$b_row['b_idx']] = 0;
						}else{
							$t = max($orders)+1;;
							for($i=0,$m=max($orders);$i<$m;$i++){
								if(!in_array($i,$orders)){
									$t = $i;
									break;
								}
							}
							$orders[$b_row['b_idx']] = $t;
						}
						$b_rowss['maxlength'] = max($b_rowss['maxlength'],count($orders));
					}
					
					if(!isset($b_rowss[$v_dt_st])){
						$b_rowss[$v_dt_st] = array();
					}
					$v_dt_ed = min($t_b,$b_row['b_etc_1']);
					$v_len = floor((strtotime($v_dt_ed)-strtotime($v_dt_st))/86400)+1;
					
					$b_rowss[$v_dt_st][] = array('b_row'=>&$b_row,'len'=>$v_len,'order'=>$orders[$b_row['b_idx']]);
					$b_etc_1s[$b_row['b_idx']] = $b_row['b_etc_1'];
				}else{
					unset($orders[$b_row['b_idx']]);
				}
			}
		}
		
		return $b_rowss;
	}
	
	//공지 목록용
	public function select_for_notice_list($get=array()){
		
		if(!$this->_apply_list_where(array())){
			return false;
		}
		$this->_apply_list_bm_row($this->bm_row);
		
		$this->db->order_by('b_notice desc');
		$this->db->where('b_notice>',0); //공지만
	
		$b_rows = $this->db->get()->result_array();
		$this->extends_b_rows($b_rows);
		return $b_rows;
	}
	
	private function extends_b_rows(& $b_rows){
		
		foreach($b_rows as & $r){
			$this->extends_b_row($r);
		}
	}
	private function extends_b_row(& $b_row){
		$b_row['depth']= min(strlen($b_row['b_gpos'])/2,10);
	}
	//-- 빈 게시물 만들기
	public function generate_empty_b_row(){
		// $sql="DESC {$this->tbl}";
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
			'b_html'=>'h',
			'b_link'=>'',
			'b_category'=>'',
			'b_title'=>'',
			'b_text'=>'',
			'b_etc_0'=>'',
			'b_etc_1'=>'',
			'b_etc_2'=>'',
			'b_etc_3'=>'',
			'b_etc_4'=>'',
			'b_num_0'=>'',
			'b_num_1'=>'',
			'b_num_2'=>'',
			'b_num_3'=>'',
			'b_num_4'=>'',
		);
		return $b_row;
	}
	//-- 게시물 하나 b_idx로 가져오기
	public function select_by_b_idx($b_idx){
		$this->db->from($this->tblname('data','b'));
		//-- 게시판 아이디
		if(!isset($this->bm_row['b_id'])){
			$this->error = '게시판 아이디가 없습니다.';
			return false;
		}
		$this->_apply_list_bm_row($this->bm_row,'b.*');
		$this->db->where('b_id',$this->bm_row['b_id']);
		//-- 필수 where절
		return $this->db->where('b_isdel','0')->where('b.b_idx',$b_idx)->get()->row_array();
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
	public function update_b_row($b_idx,$sets){
		return $this->update_b_row_as_where(array('b_idx'=>$b_idx),$sets);
	}
	public function update_b_row_as_where($where,$sets){
		unset($sets['b_idx'],$sets['b_id']);
		$this->filter_vals($sets);
		$this->db->from($this->tbl)
		->where($where)
		->where('b_isdel',0)
		->set($sets)->set('b_update_date','now()',false)->update();
		return $this->db->affected_rows();
	}
	//-- 글 작성
	public function insert_b_row($sets){
		unset($sets['b_idx']);
		$sets['b_id'] = $this->bm_row['b_id'];
		if(isset($sets['b_pass'][0])){
			$sets['b_pass'] = $this->hash($sets['b_pass']);
		}
		
		$this->filter_vals($sets);
		
		$this->db->from($this->tbl)
		->set($sets)
		->set('b_insert_date','now()',false)
		->set('b_update_date','now()',false)->insert();
		$b_idx = $this->db->insert_id();
		if($b_idx){
			$this->update_b_row($b_idx,array('b_gidx'=>-1*$b_idx/100,'b_pidx'=>$b_idx));
		}
		return $b_idx;
	}
	//-- 글 삭제
	public function delete_b_row($b_idx){
		$this->db->from($this->tbl)
		->set('b_isdel',1)
		->where('b_idx',$b_idx)
		->where('b_isdel',0)
		->set('b_update_date','now()',false)->update();
		return $this->db->affected_rows();
	}
	public function delete_b_row_as_where($where){
		return $this->update_b_row_as_where($where,array('b_isdel'=>1));
	}
	//-- 답변 글 작성
	public function insert_answer_b_row($b_idx,$sets){
		unset($sets['b_idx']);
		$sets['b_id'] = $this->bm_row['b_id'];
		if(isset($sets['b_pass'][0])){
			$sets['b_pass'] = $this->hash($sets['b_pass']);
		}
		$this->filter_vals($sets);
		
		$v_b_idx = $this->db->escape((int)$b_idx);
		$sql_b_gidx = "(SELECT b_gidx from {$this->tbl} bbsd1 WHERE bbsd1.b_idx = {$v_b_idx})";
		$sql_b_gpos =
"
CONCAT(
(SELECT bbsd1.b_gpos FROM {$this->tbl}  bbsd1 WHERE bbsd1.b_idx = {$v_b_idx})
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
FROM {$this->tbl}  bbsd1
JOIN {$this->tbl}  bbsd2 ON(bbsd2.b_gpos LIKE CONCAT(bbsd1.b_gpos,'__') AND bbsd2.b_gidx = bbsd1.b_gidx) 
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
		
		
		$this->db->from($this->tbl)
		->set($sets)
		->set('b_gidx',$sql_b_gidx,false)
		->set('b_gpos',$sql_b_gpos,false)
		->set('b_pidx',$b_idx)
		->set('b_insert_date','now()',false)
		->set('b_update_date','now()',false)->insert();

		$b_idx = $this->db->insert_id();
		

		return $b_idx;
	}
	//조회수 증가. (하루 한번 증가 시킴)
	public function hitup($b_idx,$ip,$m_idx=0){
		$tbl = $this->bm_row['tbl_hit'];
		$bh_parent_table ="'data'";
		$bh_parent_idx = $this->db->escape((int)$b_idx);
		$bh_m_idx = $this->db->escape((int)$m_idx);
		$v_ip = $this->db->escape($ip);
		$bh_ip_number = "inet_aton({$v_ip})";
		$bh_insert_date ='now()';
		$bh_update_date ='now()';
		$bh_hit_cnt = 1;
		
		$v_bh_update_date = $this->db->escape(date('Y-m-d 00:00:00'));
		
		$sql = "INSERT INTO {$tbl} (bh_parent_table,bh_parent_idx,bh_m_idx,bh_ip_number,bh_insert_date,bh_update_date,bh_hit_cnt)
		values({$bh_parent_table},{$bh_parent_idx},{$bh_m_idx},{$bh_ip_number},{$bh_insert_date},{$bh_update_date},{$bh_hit_cnt})
		ON DUPLICATE KEY UPDATE 
			bh_hit_cnt = IF(bh_update_date < {$v_bh_update_date},bh_hit_cnt+1,bh_hit_cnt), 
			bh_update_date = IF(bh_update_date < {$v_bh_update_date},now(),bh_update_date)  
		";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}