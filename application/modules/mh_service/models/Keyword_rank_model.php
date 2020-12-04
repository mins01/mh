<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== GA 도매국 키워드(검색어) 모델

class Keyword_rank_model extends CI_Model {

	public $tbl='keyword_rank_????';
	public $tbl_words='keyword_rank_????_words';
	public $tbl_data='keyword_rank_????_data';
	public $tbl_view='keyword_rank_????__vw';
	public $tbl_score='keyword_rank_????_score';
	public $fileds = array();

	public function __construct()
	{
		// $this->load->library('mh_cache');
		// Call the CI_Model constructor
		parent::__construct();
		$this->fileds = array(
			'kr_keyword',
			'kr_date',
			'kr_cid',
			'kr_view',
			'kr_rank',
		);
	}
	public function count_date_cid($kr_date,$kr_cid){
		$wheres['kr_date'] = $kr_date;
		$wheres['kr_cid'] = $kr_cid;
		return $this->count($wheres);
	}
	public function count($wheres,$order_by='',$limit=null,$offset=null){
		$select='count(*) CNT';
		$rows = $this->db->select($select)->from($this->tbl.'  gdk')->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
		return $rows[0]['CNT'];
	}
  public function select($wheres,$select='gdk.*',$order_by='',$limit=null,$offset=null){
    return $this->db->select($select)->from($this->tbl.'  gdk')->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
  }
	public function insert_row($row){
		foreach($row as $k => $v){
			if(!in_array($k,$this->fileds)){
				unset($row[$k]);
			}
		}
		$sets = array(
			'kr_keyword'=>$row['kr_keyword'],
			'kr_date'=>$row['kr_date'],
			'kr_cid'=>$row['kr_cid'],
			'kr_view'=>$row['kr_view'],
			'kr_rank'=>$row['kr_rank'],
		);
		// return $this->db->from($this->tbl)->set($sets)->insert();
		$sql = $this->db->from($this->tbl)->set($sets)->get_compiled_insert();
		$sql = str_replace('INSERT INTO','INSERT IGNORE INTO',$sql);
		return $this->db->query($sql);
		// return $this->db->insert_id();
	}
	public function insert_into_score_from_data_by_date($kr_date){
		// INSERT INTO `keyword_rank_naver_score` (kr_kwid,kr_update_at)
		// (
		// 	EXPLAIN SELECT kr_kwid,'2000-01-01 00:00:00'
		// 	FROM `keyword_rank_naveralldepth_data` krd
		// 	JOIN `keyword_rank_naver_words` krw USING(kr_kwid)
		// 	LEFT JOIN `keyword_rank_naver_score` krs USING(kr_kwid)
		// 	WHERE kr_date = '2020-11-22'
		// 	AND krs.kr_kwid IS NULL
		// 	GROUP BY kr_kwid
		// )
		$v_kr_date = $this->db->escape($kr_date);

		$sql ="INSERT INTO `{$this->tbl_score}` (kr_kwid,kr_update_at)
		(
			SELECT kr_kwid,'2000-01-01 00:00:00'
			FROM `{$this->tbl_data}` krd
			JOIN `{$this->tbl_words}` krw USING(kr_kwid)
			LEFT JOIN `{$this->tbl_score}` krs USING(kr_kwid)
			WHERE kr_date = {$v_kr_date}
			AND krs.kr_kwid IS NULL
			GROUP BY kr_kwid
		)
		";
		return $this->db->query($sql);
	}
	public function sync_rank($kr_date,$kr_cid){
		$sql = "SET @rank:=0";
		$this->db->query($sql);

		$sql="UPDATE `{$this->tbl}`
			SET kr_rank = (@rank:=@rank+1)
			WHERE kr_date = '{{kr_date}}' AND  kr_cid = '{{kr_cid}}'
			ORDER BY kr_view DESC;
		";
		$v_kr_date = $this->db->escape($kr_date);
		$v_kr_cid = $this->db->escape($kr_cid);
		$sql = str_replace("'{{kr_date}}'",$v_kr_date,$sql);
		$sql = str_replace("'{{kr_cid}}'",$v_kr_cid,$sql);
		return $this->db->query($sql);
	}
	public function sync_data($kr_date,$kr_cid){
		$v_kr_date = $this->db->escape($kr_date);
		$v_kr_cid = $this->db->escape((string)$kr_cid);
		//--- sync _words
		$sql="INSERT IGNORE INTO `{$this->tbl_words}` (kr_keyword)
			( SELECT kr_keyword FROM `{$this->tbl}`	WHERE kr_date ='{{kr_date}}' and kr_cid = '{{kr_cid}}' )
		";
		$sql = str_replace("'{{kr_date}}'",$v_kr_date,$sql);
		$sql = str_replace("'{{kr_cid}}'",$v_kr_cid,$sql);
		$this->db->query($sql);
		$n1 = $this->db->affected_rows();
		//--- delete _data
		$sql="DELETE FROM `{$this->tbl_data}` WHERE kr_date ='{{kr_date}}' and kr_cid = '{{kr_cid}}'	";
		$sql = str_replace("'{{kr_date}}'",$v_kr_date,$sql);
		$sql = str_replace("'{{kr_cid}}'",$v_kr_cid,$sql);
		$this->db->query($sql);
		$n2 = $this->db->affected_rows();
		//--- sync _data
		$sql="INSERT IGNORE INTO `{$this->tbl_data}` (kr_kwid,kr_date,kr_cid,kr_view,kr_rank)
			(
			SELECT kr_kwid,kr_date,kr_cid,kr_view,kr_rank
			FROM `{$this->tbl}` kr LEFT JOIN `{$this->tbl_words}` krd USING(kr_keyword)
			WHERE kr_date ='{{kr_date}}' and kr_cid = '{{kr_cid}}'
			)
		";
		$sql = str_replace("'{{kr_date}}'",$v_kr_date,$sql);
		$sql = str_replace("'{{kr_cid}}'",$v_kr_cid,$sql);
		$this->db->query($sql);
		$n3 = $this->db->affected_rows();
		return array('insert_words'=>$n1,'delete_data'=>$n2,'insert_data'=>$n3);
	}
	public function insert_rows($rows){
		$setss = array();
		$i_cnt = 0;
		// print_r($rows);exit;
		foreach ($rows as $row) {
			if($i_cnt===0){
				$setss = array();
				$sql = "INSERT IGNORE INTO {$this->tbl} (kr_keyword,kr_date,kr_cid,kr_view,kr_rank) values ";
			}

			foreach($row as $k => $v){
				if(!in_array($k,$this->fileds)){
					unset($row[$k]);
				}
			}
			$sets = array(
				$this->db->escape($row['kr_keyword']),
				$this->db->escape($row['kr_date']),
				$this->db->escape($row['kr_cid']),
				$this->db->escape((int)$row['kr_view']),
				$this->db->escape(isset($row['kr_rank'])?(int)$row['kr_rank']:0),
			);
			// $setss[] = '('.implode(',',$sets).')';
			// $sql.=implode(',',$setss);
			$t = '('.implode(',',$sets).')';
			$setss[] = $t;
			// echo $sql;
			$i_cnt+= strlen($t)+1;
			// echo ($sql)."\n";
			// echo strlen($sql)."\n";
			if($i_cnt >= 1024*1024){
				$sql .= implode(',',$setss);
				$i_cnt = 0;
				// echo $sql."\n";
				$this->db->query($sql);
			}
		}
		if($i_cnt > 0){
			$sql .= implode(',',$setss);
			// echo $sql."\n";exit;
			$this->db->query($sql);
		}

		// echo iconv_strlen($sql);
		// exit;
		return;
		// return $this->db->insert_id();
	}
	// 가공
	public function bak_rows_per_days_extended($rows,$date_st,$date_ed){
		// $rows = $this->rows_per_days($kr_cid,$kr_date_st,$kr_date_ed);

		$def_ranks_array = array();
		$tm1 = strtotime($date_st);
		$tm2 = strtotime($date_ed);
		$i_limit = 1200;
		while($tm1<=$tm2 && $i_limit-- > 0){
			$def_ranks_array[date('Y-m-d',$tm1)]=999;
			$def_views_array[date('Y-m-d',$tm1)]=0;
			$tm1 += 60*60*24;
		}
		// print_r(	$def_ranks_array);
		// print_r(	$def_views_array);
		$rowss = array();
		foreach($rows as $r){
			if(!isset($rowss[$r['keyword']])){
				$rowss[$r['keyword']] = array('keyword'=>$r['keyword'],'def_rank'=>$r['def_rank'],'ranks'=>$def_ranks_array,'views'=>$def_views_array,'count_rank'=>0);
			}
			$rowss[$r['keyword']]['ranks'][$r['date']]=$r['rank'];
			$rowss[$r['keyword']]['views'][$r['date']]=$r['view'];
			$rowss[$r['keyword']]['count_rank']++;
		}
		foreach ($rowss as $k => & $r) {
			$r['avg_rank'] = array_avg($r['ranks']);
			$r['dev_rank'] = array_dev($r['ranks'],$r['avg_rank']);
			$r['sum_view'] = array_sum($r['views']);
			if(!$r['def_rank']){
				$r['def_rank'] = end($r['ranks']);
			}
			$r['slope_rank'] = ($r['def_rank'] - $r['avg_rank'])/$r['avg_rank']*100;
		}

		return $rowss;
	}
	public function bak_rows_per_days($kr_cid,$date_st,$date_ed){
		$v_kr_cid = $this->db->escape($kr_cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);

		$sql = "SELECT
			D0.kr_keyword as 'keyword',
			D0.kr_rank as 'def_rank',
			DD.kr_date as 'date',
			DD.kr_view as 'view',
			DD.kr_rank as 'rank'
			FROM `{$this->tbl}` D0
			JOIN `{$this->tbl}` DD ON(D0.kr_keyword = DD.kr_keyword AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
			WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_kr_cid} AND D0.kr_rank <= 50
			ORDER BY D0.kr_rank
		";
		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}
	public function bak_rows_per_days_by_keywords($kr_cid,$date_st,$date_ed,$keywords){
		$v_kr_cid = $this->db->escape($kr_cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);
		$ts = array();
		foreach($keywords as $v){
			$ts[]=$this->db->escape($v);
		}
		$v_keywords = implode(',',$ts);

		$sql = "SELECT
					D0.kr_keyword AS 'keyword',
					NULL AS 'def_rank',
					D0.kr_date AS 'date',
					D0.kr_view AS 'view',
					D0.kr_rank AS 'rank'
					FROM `{$this->tbl}` D0
					WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_kr_cid} AND D0.kr_keyword IN ({$v_keywords})
		";
		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}

	public function array_date_key($group_type,$date_st,$date_ed,$v){
		return array_date_key($group_type,$date_st,$date_ed,$v);
	}
	public function rows_4_group_extended($rows,$group_type,$date_st,$date_ed){
		// $rows = $this->rows_per_days($kr_cid,$kr_date_st,$kr_date_ed);
		// print_r($rows);
		// exit;
		$def_ranks_array = array();
		$tm1 = strtotime($date_st);
		$tm2 = strtotime($date_ed);
		$def_ranks_array = $this->array_date_key($group_type,$date_st,$date_ed,999);
		$def_views_array = $this->array_date_key($group_type,$date_st,$date_ed,0);

		$rowss = array();
		foreach($rows as $r){
			if(!isset($rowss[$r['keyword']])){
				$rowss[$r['keyword']] = array(
					'keyword'=>$r['keyword'],
					'def_rank'=>$r['def_rank'],
					'def_view'=>$r['def_view'],
					'ranks'=>$def_ranks_array,
					'views'=>$def_views_array,
					'count_rank'=>0,
					'sum_upv_domeggook'=>isset($r['sum_upv_domeggook'])?$r['sum_upv_domeggook']:null,
					'sum_upv_domeme'=>isset($r['sum_upv_domeme'])?$r['sum_upv_domeme']:null,
				);
			}
			$rowss[$r['keyword']]['ranks'][$r['date']]=$r['rank'];
			$rowss[$r['keyword']]['views'][$r['date']]=$r['view'];
			$rowss[$r['keyword']]['count_rank']++;
		}

		foreach ($rowss as $k => & $r) {
			$r['avg_rank'] = array_avg($r['ranks']);
			$r['dev_rank'] = array_dev($r['ranks'],$r['avg_rank']);
			$r['sum_view'] = array_sum($r['views']);
			$r['avg_view'] = array_avg($r['views']);
			$r['dev_view'] = array_dev($r['views'],$r['avg_view']);
			if(!$r['def_rank']){
				$r['def_rank'] = end($r['ranks']);
			}
			if(!$r['def_view']){
				$r['def_view'] = end($r['views']);
			}
			$r['slope_rank'] = ($r['def_rank'] - $r['avg_rank'])/$r['avg_rank']*100;
			$r['slope_view'] = ($r['avg_view'] - $r['def_view'])/$r['avg_view']*100;
		}
		uasort($rowss, array($this, 'rows_4_group_extended_sort_fn')); //소팅
		//print_r($rowss); exit;
		return $rowss;
	}
	public function rows_4_group_extended_sort_fn($a,$b){
		if(end($b['views'])==end($a['views'])){
			return end($a['ranks'])-end($b['ranks']);
		}
		return end($b['views'])-end($a['views']);
	}

	public function rows_4_rank($group_type,$cid,$date_st,$date_ed,$width_dome=false){
	}
	public function rows_4_group($group_type,$cid,$date_st,$date_ed,$width_dome=false){
		$v_cid = $this->db->escape($cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);

		$width_dome_select = '';
		$width_dome_subq = '';
		if($width_dome){
			$width_dome_select = ',D0.sum_upv_domeggook ,D0.sum_upv_domeme';
			$width_dome_subq = ", (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D1 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeggook_pc','domeggook_mo') AND ga_searchkeyword = krw.kr_keyword) AS 'sum_upv_domeggook'
					, (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D2 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeme','domemedb') AND ga_searchkeyword = krw.kr_keyword) AS 'sum_upv_domeme'
					";
		}


		if($group_type=='day'){
			$sql = "SELECT
				krw.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				DD.kr_date as 'date',
				DD.kr_view as 'view',
				DD.kr_rank as 'rank'
				{$width_dome_subq}
				FROM `{$this->tbl_data}` D0
				JOIN `{$this->tbl_words}` krw USING(kr_kwid)
				JOIN `{$this->tbl_data}` DD ON(D0.kr_kwid = DD.kr_kwid AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='week'){
			$sql = "SELECT
				kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%x-%v') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				# {$width_dome_select}
				# FROM `{$this->tbl}` D0
				{$width_dome_subq}
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'

					FROM (
						SELECT kr_kwid,MAX(kr_cid) as 'kr_cid',SUM(kr_view) as 'kr_view'
						FROM `{$this->tbl_data}` D0
						WHERE  D0.kr_date BETWEEN DATE_SUB({$v_date_ed},INTERVAL DATE_FORMAT({$v_date_ed},'%w') DAY) AND DATE_ADD({$v_date_ed},INTERVAL 6-DATE_FORMAT({$v_date_ed},'%w') DAY) AND D0.kr_cid={$v_cid}
						GROUP BY kr_kwid
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0
					JOIN(SELECT @RANK_D0:=0) xx
				) D0
				JOIN `{$this->tbl_words}` krw USING(kr_kwid)
				JOIN `{$this->tbl_data}` DD ON(D0.kr_kwid = DD.kr_kwid AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				#WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				GROUP BY kr_keyword, DATE_FORMAT(DD.kr_date,'%x-%v')
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='month'){
			$sql = "SELECT
				kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%Y-%m') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				-- {$width_dome_select}
				{$width_dome_subq}
				#FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'

					FROM (
						SELECT kr_kwid,MAX(kr_cid) as 'kr_cid',SUM(kr_view) as 'kr_view'
						FROM `{$this->tbl_data}` D0
						WHERE  D0.kr_date BETWEEN DATE_FORMAT({$v_date_ed},'%Y-%m-01') AND LAST_DAY({$v_date_ed}) AND D0.kr_cid={$v_cid}
						GROUP BY kr_kwid
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				JOIN `{$this->tbl_words}` krw USING(kr_kwid)
				JOIN `{$this->tbl_data}` DD ON(D0.kr_kwid = DD.kr_kwid AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				#WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				GROUP BY kr_keyword, DATE_FORMAT(DD.kr_date,'%Y-%m')
				ORDER BY D0.kr_rank
			";
		}
		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}
	public function rows_4_group_by_keywords($group_type,$cid,$date_st,$date_ed,$keywords,$width_dome=false){
		$v_cid = $this->db->escape($cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);
		$ts = array();
		foreach($keywords as $v){
			$ts[]=$this->db->escape($v);
		}
		$v_keywords = implode(',',$ts);

		$width_dome_select = '';
		$width_dome_subq = '';
		if($width_dome){
			$width_dome_select = ',D0.sum_upv_domeggook ,D0.sum_upv_domeme';
			$width_dome_subq = ", (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D1 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeggook_pc','domeggook_mo') AND ga_searchkeyword = kr_keyword) AS 'sum_upv_domeggook'
					, (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D2 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeme','domemedb') AND ga_searchkeyword = kr_keyword) AS 'sum_upv_domeme'
					";
		}

		if($group_type=='day'){
			$sql = "SELECT
						kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						D0.kr_date AS 'date',
						D0.kr_view AS 'view',
						D0.kr_rank AS 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl_data}` D0
						JOIN `{$this->tbl_words}` krw USING(kr_kwid)
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND kr_keyword IN ({$v_keywords})
			";
		}else if($group_type=='week'){
			$sql = "SELECT
						kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						DATE_FORMAT(D0.kr_date,'%x-%v') AS 'date',
						SUM(D0.kr_view) as 'view',
						round(AVG(D0.kr_rank),1) as 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl_data}` D0
						JOIN `{$this->tbl_words}` krw USING(kr_kwid)
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND kr_keyword IN ({$v_keywords})
						GROUP BY kr_keyword, DATE_FORMAT(D0.kr_date,'%x-%v')
			";
		}else if($group_type=='month'){
			$sql = "SELECT
						kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						DATE_FORMAT(D0.kr_date,'%Y-%m') AS 'date',
						SUM(D0.kr_view) as 'view',
						round(AVG(D0.kr_rank),1) as 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl_data}` D0
						JOIN `{$this->tbl_words}` krw USING(kr_kwid)
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND kr_keyword IN ({$v_keywords})
						GROUP BY kr_keyword, DATE_FORMAT(D0.kr_date,'%Y-%m')
			";
		}

		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}


	public function rows_4_group_join_other($data_table,$group_type,$cid,$date_st,$date_ed){
		$v_cid = $this->db->escape($cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);
		if($group_type=='day'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				DD.kr_date AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				FROM `{$this->tbl}` D0
				LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				GROUP BY D0.kr_keyword, DD.kr_date
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='week'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%x-%v') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				# FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
					FROM (
						SELECT kr_keyword,kr_cid
						FROM `{$this->tbl}` D0
						WHERE  D0.kr_date BETWEEN DATE_SUB({$v_date_ed},INTERVAL DATE_FORMAT({$v_date_ed},'%w')-1 DAY) AND DATE_ADD({$v_date_ed},INTERVAL 6-DATE_FORMAT({$v_date_ed},'%w') DAY) AND D0.kr_cid={$v_cid}
						GROUP BY D0.kr_keyword
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%x-%v')
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='month'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%Y-%m') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				#FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
					FROM (
						SELECT kr_keyword,kr_cid
						FROM `{$this->tbl}` D0
						WHERE  D0.kr_date BETWEEN DATE_FORMAT({$v_date_ed},'%Y-%m-01') AND LAST_DAY({$v_date_ed}) AND D0.kr_cid={$v_cid}
						GROUP BY D0.kr_keyword
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%Y-%m')
				ORDER BY D0.kr_rank
			";
		}
		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}

	public function rows_4_group_join_other_by_keywords($data_table,$group_type,$cid,$date_st,$date_ed,$keywords){
		$v_cid = $this->db->escape($cid);
		$v_date_st = $this->db->escape($date_st);
		$v_date_ed = $this->db->escape($date_ed);
		$ts = array();
		foreach($keywords as $v){
			$ts[]=$this->db->escape($v);
		}
		$v_keywords = implode(',',$ts);
		if($group_type=='day'){
			$sql = "SELECT
					D0.kr_keyword as 'keyword',
					D0.kr_rank as 'def_rank',
					DD.kr_date AS 'date',
					SUM(DD.kr_view) as 'view',
					round(AVG(DD.kr_rank),1) as 'rank'
					FROM `{$this->tbl}` D0
					LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
					WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_keyword IN ({$v_keywords})
					GROUP BY D0.kr_keyword, DD.kr_date
					ORDER BY D0.kr_rank
			";
		}else if($group_type=='week'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%x-%v') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				# FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
					FROM (
						SELECT kr_keyword,kr_cid
						FROM `{$this->tbl}` D0
						WHERE  D0.kr_date BETWEEN DATE_SUB({$v_date_ed},INTERVAL DATE_FORMAT({$v_date_ed},'%w')-1 DAY) AND DATE_ADD({$v_date_ed},INTERVAL 6-DATE_FORMAT({$v_date_ed},'%w') DAY) AND D0.kr_cid={$v_cid}
							AND D0.kr_keyword IN ({$v_keywords})
						GROUP BY D0.kr_keyword
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%x-%v')
				ORDER BY D0.kr_rank

			";
		}else if($group_type=='month'){
			$sql = "SELECT
						D0.kr_keyword as 'keyword',
						D0.kr_rank as 'def_rank',
						#DD.kr_date as 'date',
						DATE_FORMAT(DD.kr_date,'%Y-%m') AS 'date',
						SUM(DD.kr_view) as 'view',
						round(AVG(DD.kr_rank),1) as 'rank'
						#FROM `{$this->tbl}` D0
						FROM (
							SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
							FROM (
								SELECT kr_keyword,kr_cid
								FROM `{$this->tbl}` D0
								WHERE  D0.kr_date BETWEEN DATE_FORMAT({$v_date_ed},'%Y-%m-01') AND LAST_DAY({$v_date_ed}) AND D0.kr_cid={$v_cid}
									AND D0.kr_keyword IN ({$v_keywords})
								GROUP BY D0.kr_keyword
								ORDER BY SUM(kr_view) DESC
								LIMIT 50
							)D0 , (SELECT @RANK_D0:=0) xx
						) D0
						LEFT JOIN `{$data_table}` DD ON(D0.kr_keyword = DD.kr_keyword  AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
						GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%Y-%m')
						ORDER BY D0.kr_rank
			";
		}

		// echo $sql; exit;
		return $this->db->query($sql)->result_array();
	}

	/**
	 * 네이버 케테고리 목록 가져오기
	 */
	 public function select_naver_shop_category($wheres,$select='nsc.*',$order_by='',$limit=null,$offset=null){
     return $this->db->select($select)->from('naver_shopping_category  nsc')->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
   }
	 public function kv_rows_naver_shop_category($rows){
		 $rowss = array();
		 foreach ($rows as $k => $r) {
		 	$rowss[$r['nsc_id']] = $r['nsc_name'];
		 }
		 return $rowss;
	 }
	 public function select_tree_naver_shop_category(){
		 $sql = "SELECT nsc_id,nsc_depth,nsc_name,
		 CASE nsc_depth
		 WHEN 1 THEN ''
		 WHEN 2 THEN nsc_id_1
		 WHEN 3 THEN nsc_id_2
		 WHEN 4 THEN nsc_id_3
		 END AS nsc_pid
		 FROM naver_shopping_category
		 ORDER BY nsc_depth,IF(nsc_depth=1,nsc_id,nsc_name);
		 ";
		 $rows = $this->db->query($sql)->result_array();
		 $rowss = array();
		 foreach($rows as $k=> &$r){
			 $rowss[$r['nsc_id']] = &$r;
		 }
		 return $rowss;
	 }
	 public function tree_naver_shop_category(){
		 $rows = $this->select_tree_naver_shop_category();
		 $rowss =  array();
		 $r_rows = array();
		 foreach ($rows as & $r) {
			 $rowss[$r['nsc_id']] = & $r;
			 if(isset($r['nsc_pid'][0])){
				 if(!isset($rowss[$r['nsc_pid']]['child'])){
					 $rowss[$r['nsc_pid']]['child'] = array();
				 }
				 $rowss[$r['nsc_pid']]['child'][] = & $r;
			 }
			 if($r['nsc_depth'] == 1){
				 $r_rows[] = & $rowss[$r['nsc_id']];
			 }
			 unset($r['nsc_pid'],$r['nsc_depth']);
		 }
		 // print_r($r_rows);
		 return $r_rows;
	 }

	 public function select_cat_keyword_by_cid($cid){
		 $v_cid = $this->db->escape($cid);
		 // $sql = "SELECT kr_keyword,kr_rank,kr_competitive_strength,kr_search_total_shop,kr_monthlyPcQcCnt,kr_monthlyMobileQcCnt,kr_update_at FROM `keyword_rank_naveralldepth_data` krd
		 $sql = "SELECT
		 kr_keyword,kr_rank,IFNULL(kr_search_total_shop/(kr_monthlyPcQcCnt+kr_monthlyMobileQcCnt),9999) as 'kr_competitive_strength',
		 kr_search_total_shop,IFNULL(kr_monthlyPcQcCnt+kr_monthlyMobileQcCnt,0) as kr_monthlyQcCnt,kr_openapi_update_at,kr_searchad_update_at,
		 GREATEST(kr_openapi_update_at,kr_searchad_update_at) AS 'kr_update_at'
		 FROM `keyword_rank_naveralldepth_data` krd
		 JOIN `keyword_rank_naveralldepth_words` krw USING(kr_kwid)
		 LEFT JOIN  `keyword_rank_naveralldepth_score` krs USING(kr_kwid)
		 WHERE kr_cid = {$v_cid} AND kr_date='2020-11-22'
		 ORDER BY kr_competitive_strength,kr_rank";
		 return $this->db->query($sql)->result_array();
	 }
	 public function select_rel_keyword_by_keywordList($keywordList){
		 $ins = array();
		 $kv = array();
		 $rowss = array();
		 foreach ($keywordList as $k => $r) {
			 $row = array();
			 $row['relKeyword'] = preg_replace('/[\s\t\r\n]/','',$r['relKeyword']);
			 $row['kr_keyword'] = $row['relKeyword'];
			 $row['kr_rank'] = $k;

			 $row['kr_monthlyPcQcCnt'] =  preg_replace('/[^\d]/','',$r['monthlyPcQcCnt']);
			 $row['kr_monthlyMobileQcCnt'] = preg_replace('/[^\d]/','',$r['monthlyMobileQcCnt']);
			 $row['kr_monthlyQcCnt'] = $row['kr_monthlyPcQcCnt'] + $row['kr_monthlyMobileQcCnt'];
			 $row['kr_monthlyAvePcClkCnt'] =$r['monthlyAvePcClkCnt'];
			 $row['kr_monthlyAveMobileClkCnt'] = $r['monthlyAveMobileClkCnt'];
			 $row['kr_monthlyAvePcCtr'] = $r['monthlyAvePcCtr'];
			 $row['kr_monthlyAveMobileCtr'] = $r['monthlyAveMobileCtr'];
			 $row['kr_plAvgDepth'] = $r['plAvgDepth'];
			 $row['kr_compIdx'] = $r['compIdx'];
			 $row['kr_competitive_strength'] = 9999;
			 $row['kr_search_total_shop'] = null;

			 $rowss[strtoupper($r['relKeyword'])]=$row;
			 $ins[]= $this->db->escape($r['relKeyword']);
		 }
		 unset($keywordList);
		 unset($row);
		 if(count($ins)==0){
			 return;
		 }
		 $where_in = "kr_keyword in (".implode(',',$ins).")";
		 $sql = "SELECT
		 kr_keyword,kr_search_total_shop,kr_openapi_update_at,kr_searchad_update_at,
		 GREATEST(kr_openapi_update_at,kr_searchad_update_at) AS 'kr_update_at'
		 FROM `keyword_rank_naveralldepth_words` krw
		 LEFT JOIN  `keyword_rank_naveralldepth_score` krs USING(kr_kwid)
		 WHERE {$where_in}";

		 // $rows = $this->db->query($sql)->result_array();
		 // foreach ($kv as $k => $r) {
		 //
		 // }
		 // echo $sql;exit;
		 // print_r($rowss);exit;
		 // print_r($rows);exit;
		 foreach($this->db->query($sql)->result_array() as $r1){
			 $k = strtoupper($r1['kr_keyword']);
			 if(!isset($rowss[$k])){
				 continue;
			 }
			 $r = & $rowss[$k];

			 $r['kr_search_total_shop'] = $r1['kr_search_total_shop'];
			 $r['kr_openapi_update_at'] = $r1['kr_openapi_update_at'];
			 $r['kr_searchad_update_at'] = $r1['kr_searchad_update_at'];
			 $r['kr_update_at'] = $r1['kr_update_at'];
			 if(is_numeric($r['kr_search_total_shop'])){
				 $r['kr_competitive_strength'] = $r['kr_search_total_shop']/$r['kr_monthlyQcCnt'];
			 }
			 unset($r);
		 }

		 $rows = array_values($rowss);
		 // var_dump($rows);exit;
		 usort($rows, array($this, 'select_rel_keyword_by_keywordList_sort_fn')); //소팅
		 // var_dump($rows);exit;
		 // array_values($rowss);
		 return $rows;
	 }
	 public function select_rel_keyword_by_keywordList_sort_fn($a,$b){
		 if($a['kr_competitive_strength']===$b['kr_competitive_strength']){
			 // return $b['kr_monthlyQcCnt']-$a['kr_monthlyQcCnt'];
			 return $a['kr_rank']-$b['kr_rank'];
		 }
		 if($a['kr_competitive_strength']>$b['kr_competitive_strength']){
			 return 1;
		 }else{
			 return -1;
		 }
	 }

}
