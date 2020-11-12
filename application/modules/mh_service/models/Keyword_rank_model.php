<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== GA 도매국 키워드(검색어) 모델

class Keyword_rank_model extends CI_Model {

	public $tbl='keyword_rank_????';
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
	/**
	 * [statistics description]
	 * @param  [type] $kr_cid  [description]
	 * @param  [type] $kr_date 이 날짜의 하루전 부터 데이터를 가져온다!
	 * @return [type]             [description]
	 */
	public function bak_statistics($kr_cid,$kr_date,$period){
		// $v_kr_date = $this->db->escape($kr_date);
		$v_kr_cid = $this->db->escape($kr_cid);
		$v_kr_date = $this->db->escape($kr_date);

		$ts1 = array();
		$ts2 = array();
		for($i=2,$m=$period;$i<=$m;$i++){
			// $ti =sprintf('%02d',$i);
			$i_1 = $i-1;
			$ts1[]="IFNULL(D{$i}.kr_view,0) view_d{$i} ,IFNULL(D{$i}.kr_rank,999) rank_d{$i} ,CASE WHEN D{$i_1}.kr_rank IS NULL THEN 'OUT' WHEN D{$i}.kr_rank IS NULL THEN 'NEW' ELSE D{$i_1}.kr_rank - D{$i}.kr_rank END AS step_d{$i} ";
			$ts2[]="LEFT JOIN `{$this->tbl}` D{$i} ON(D{$i}.kr_cid = D1.kr_cid AND D{$i}.kr_date = DATE_SUB({$v_kr_date},INTERVAL {$i} DAY) AND D{$i}.kr_keyword = D1.kr_keyword)";
		}

		$ts1_str = implode(",\n",$ts1);
		$ts2_str = implode("\n",$ts2);

		$sql = "SELECT
		D1.kr_cid, D1.kr_keyword as 'keyword'
		,IFNULL(D1.kr_view,0) view_d1 ,IFNULL(D1.kr_rank,999) rank_d1
		,{$ts1_str}
		FROM `{$this->tbl}` D1
		{$ts2_str}
		WHERE D1.kr_date = DATE_SUB({$v_kr_date},INTERVAL 1 DAY) AND D1.kr_cid = {$v_kr_cid} and D1.kr_rank <= 50
		ORDER BY D1.kr_rank
		";
		// echo $sql;exit;
		return $this->db->query($sql)->result_array();
	}

	public function bak_statistics_ym($kr_cid,$d_Y,$d_m){
		if(!is_numeric($d_m) || !is_numeric($d_Y)){
			show_error("잘못된 날자 설정입니다.");
		}
		$v_kr_cid = $this->db->escape($kr_cid);
		$v_d_Y = (int)$d_Y;
		$v_d_m = $d_m;

		$ts1 = array();
		$ts2 = array();
		for($i=1,$m=12;$i<=$m;$i++){
			$ti =sprintf('%02d',$i);
			$ts1[]=" IFNULL(M{$ti}.sum_view_m,0) AS 'sum_view_m{$ti}',IFNULL(M{$ti}.rank_m,999) AS 'rank_m{$ti}' ";
			$ts2[]="LEFT JOIN (
				SELECT A.kr_keyword , sum_view AS 'sum_view_m',@rank{$ti}:=@rank{$ti}+1 AS 'rank_m' FROM (SELECT M.kr_keyword, SUM(kr_view) AS 'sum_view'
					FROM `{$this->tbl}` M
					WHERE M.kr_date BETWEEN '{$v_d_Y}-{$ti}-01' AND  LAST_DAY('{$v_d_Y}-{$ti}-01') AND M.kr_cid = {$v_kr_cid}
					GROUP BY M.kr_keyword
					ORDER BY SUM(M.kr_view) DESC
					) A, (SELECT @rank{$ti}:=0) xx
				) M{$ti} USING(kr_keyword)
				";
		}

		$ts1_str = implode(",\n",$ts1);
		$ts2_str = implode("\n",$ts2);

		$sql = "SELECT
			kr_keyword as 'keyword',
			IFNULL(M00.sum_view_m,0) AS 'sum_view_m00', IFNULL(M00.rank_m,999) AS 'rank_m00',
			{$ts1_str}

			FROM (
				SELECT A.kr_keyword , sum_view AS 'sum_view_m',@rank00:=@rank00+1 AS 'rank_m' FROM (SELECT M.kr_keyword, SUM(kr_view) AS 'sum_view'
				FROM `{$this->tbl}` M
				WHERE M.kr_date BETWEEN '{$v_d_Y}-{$v_d_m}-01' AND  LAST_DAY('{$v_d_Y}-{$v_d_m}-01') AND M.kr_cid = {$v_kr_cid}
				GROUP BY M.kr_keyword
				ORDER BY SUM(M.kr_view) DESC
				LIMIT 100) A, (SELECT @rank00:=0) xx
			) M00
			{$ts2_str}
		";

		// echo $sql;exit;
		return $this->db->query($sql)->result_array();

	}
	// 가공
	public function rows_per_days_extended($rows,$date_st,$date_ed){
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
	public function rows_per_days($kr_cid,$date_st,$date_ed){
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
	public function rows_per_days_by_keywords($kr_cid,$date_st,$date_ed,$keywords){
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
			$width_dome_subq = ", (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D1 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeggook_pc','domeggook_mo') AND ga_searchkeyword = D0.kr_keyword) AS 'sum_upv_domeggook'
					, (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D2 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeme','domemedb') AND ga_searchkeyword = D0.kr_keyword) AS 'sum_upv_domeme'
					";
		}


		if($group_type=='day'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				DD.kr_date as 'date',
				DD.kr_view as 'view',
				DD.kr_rank as 'rank'
				{$width_dome_subq}
				FROM `{$this->tbl}` D0
				JOIN `{$this->tbl}` DD ON(D0.kr_keyword = DD.kr_keyword AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='week'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%x-%v') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				{$width_dome_select}
				# FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
					{$width_dome_subq}
					FROM (
						SELECT kr_keyword,MAX(kr_cid) as 'kr_cid',SUM(kr_view) as 'kr_view'
						FROM `{$this->tbl}` D0
						WHERE  D0.kr_date BETWEEN DATE_SUB({$v_date_ed},INTERVAL DATE_FORMAT({$v_date_ed},'%w')-1 DAY) AND DATE_ADD({$v_date_ed},INTERVAL 6-DATE_FORMAT({$v_date_ed},'%w') DAY) AND D0.kr_cid={$v_cid}
						GROUP BY D0.kr_keyword
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				JOIN `{$this->tbl}` DD ON(D0.kr_keyword = DD.kr_keyword AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				#WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%x-%v')
				ORDER BY D0.kr_rank
			";
		}else if($group_type=='month'){
			$sql = "SELECT
				D0.kr_keyword as 'keyword',
				D0.kr_rank as 'def_rank',
				D0.kr_view as 'def_view',
				#DD.kr_date as 'date',
				DATE_FORMAT(DD.kr_date,'%Y-%m') AS 'date',
				SUM(DD.kr_view) as 'view',
				round(AVG(DD.kr_rank),1) as 'rank'
				{$width_dome_select}
				#FROM `{$this->tbl}` D0
				FROM (
					SELECT D0.*,@RANK_D0:=@RANK_D0+1 AS 'kr_rank'
					{$width_dome_subq}
					FROM (
						SELECT kr_keyword,MAX(kr_cid) as 'kr_cid',SUM(kr_view) as 'kr_view'
						FROM `{$this->tbl}` D0
						WHERE  D0.kr_date BETWEEN DATE_FORMAT({$v_date_ed},'%Y-%m-01') AND LAST_DAY({$v_date_ed}) AND D0.kr_cid={$v_cid}
						GROUP BY D0.kr_keyword
						ORDER BY SUM(kr_view) DESC
						LIMIT 50
					)D0 , (SELECT @RANK_D0:=0) xx
				) D0
				JOIN `{$this->tbl}` DD ON(D0.kr_keyword = DD.kr_keyword AND D0.kr_cid = DD.kr_cid AND DD.kr_date BETWEEN {$v_date_st} AND {$v_date_ed})
				#WHERE D0.kr_date = {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_rank <= 50
				GROUP BY D0.kr_keyword, DATE_FORMAT(DD.kr_date,'%Y-%m')
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
			$width_dome_subq = ", (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D1 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeggook_pc','domeggook_mo') AND ga_searchkeyword = D0.kr_keyword) AS 'sum_upv_domeggook'
					, (SELECT IFNULL(SUM(ga_searchuniques),0) FROM ga_search D2 WHERE ga_date BETWEEN '2020-10-01' AND '2020-10-14' AND gs_cid IN ('domeme','domemedb') AND ga_searchkeyword = D0.kr_keyword) AS 'sum_upv_domeme'
					";
		}

		if($group_type=='day'){
			$sql = "SELECT
						D0.kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						D0.kr_date AS 'date',
						D0.kr_view AS 'view',
						D0.kr_rank AS 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl}` D0
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_keyword IN ({$v_keywords})
			";
		}else if($group_type=='week'){
			$sql = "SELECT
						D0.kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						DATE_FORMAT(D0.kr_date,'%x-%v') AS 'date',
						SUM(D0.kr_view) as 'view',
						round(AVG(D0.kr_rank),1) as 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl}` D0
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_keyword IN ({$v_keywords})
						GROUP BY D0.kr_keyword, DATE_FORMAT(D0.kr_date,'%x-%v')
			";
		}else if($group_type=='month'){
			$sql = "SELECT
						D0.kr_keyword AS 'keyword',
						NULL AS 'def_rank',
						NULL as 'def_view',
						DATE_FORMAT(D0.kr_date,'%Y-%m') AS 'date',
						SUM(D0.kr_view) as 'view',
						round(AVG(D0.kr_rank),1) as 'rank'
						{$width_dome_subq}
						FROM `{$this->tbl}` D0
						WHERE D0.kr_date BETWEEN {$v_date_st} AND {$v_date_ed} AND D0.kr_cid={$v_cid} AND D0.kr_keyword IN ({$v_keywords})
						GROUP BY D0.kr_keyword, DATE_FORMAT(D0.kr_date,'%Y-%m')
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
}
