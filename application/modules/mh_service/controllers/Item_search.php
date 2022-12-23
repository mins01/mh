<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Item_search extends MX_Controller {
	private $base_url = null;
	private $view_dir = 'mh_service/item_search/';
	// private $conf_searchad_naver = null;
	//
	public $cids = array(
		'50000000'=>'패션의류',
		'50000001'=>'패션잡화',
		'50000002'=>'화장품/미용',
		'50000003'=>'디지털/가전',
		'50000004'=>'가구/인테리어',
		'50000005'=>'출산/육아',
		'50000006'=>'식품',
		'50000007'=>'스포츠/레저',
		'50000008'=>'생활/건강',
		// '50000009'=>'여가/생활편의',
		// '50000010'=>'면세점',
	);
	public $nsc_rows = array();
	public $group_types = array('day'=>'일별','week'=>'주별','month'=>'월별');
	public function __construct()
	{
		parent::__construct();
		$this->load->library('Mh_cache');
		$this->load->library('mheader');
		$this->mh_cache->use_log_header = true;
		$this->mh_cache->use_cache = true;

		$this->load->library('Mproxy');
		$this->config->load('api_searchad_naver');
		$this->config->load('openapi_naver_com');
		$conf_api_searchad_naver = $this->config->item('api_searchad_naver');
		$conf_openapi_naver_com = $this->config->item('openapi_naver_com');
		$this->load->library('ApiSearchadNaver');
		$this->load->library('ApiOpenApiNaverCom');
		$this->load->library('CrawlingNaver');
		$this->apisearchadnaver->set_account($conf_api_searchad_naver['accounts']['default']);
		$this->apisearchadnaver->set_mproxy($this->mproxy);
		$this->apisearchadnaver->set_mh_cache($this->mh_cache);
		$this->apiopenapinavercom->set_account($conf_openapi_naver_com['accounts']['default']);
		$this->apiopenapinavercom->set_mproxy($this->mproxy);
		$this->apiopenapinavercom->set_mh_cache($this->mh_cache);
		$this->crawlingnaver->set_mproxy($this->mproxy);
		$this->crawlingnaver->set_mh_cache($this->mh_cache);

		$this->load->model('mh_service/keyword_rank_naver_model','kr_m');
		$this->nsc_rows = $this->kr_m->select_naver_shop_category(array('nsc_depth<='=>2),'nsc.*','nsc_id_1,nsc_id_2');
		$this->cids = $this->kr_m->kv_rows_naver_shop_category($this->nsc_rows);

		$this->load->model('mh_service/Keyword_rank_score_naver_model','krs_m');


	}
	public function set_mproxy($mproxy){
		$this->mproxy = $mproxy;
	}
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		// print_r($param);exit;
		$method = isset($param[0][0])?$param[0]:'index';
		if(!method_exists($this,$method)){
			// show_error("지정 메소드({$method})가 없습니다.");
			$method = 'index';
		}
		$this->{$method}($conf,$param);
	}
	public function index($conf,$param){
		// $this->test($conf,$param);
		// $this->keyword($conf,$param);
		// $this->category($conf,$param);
		// header('Location: '.$conf['base_url'].'/category');
		header('Location: '.$conf['base_url'].'/keyword');
		exit;
	}
	public function keyword($conf,$param){
		$keyword = $this->input->get('keyword');
		if(!isset($keyword)){ $keyword = '';}
		$keyword = preg_replace('/[\s\t\r\n]/','',$keyword);

		$search_totals = null;
		$managedKeyword = null;
		$keywordstool = null;
		$datalab_search = null;
		$datalab_shops = null;
		$search_shop_catetories = null;
		if(isset($keyword[0])){
			$search_totals = $this->apiopenapinavercom->v1_search_totals($keyword,'1','1','sim');
			$search_shop = $this->apiopenapinavercom->v1_search_shop_json($keyword,'100','1','sim');
			$search_shop_catetories = $this->apiopenapinavercom->categories_v1_search_shop_json($search_shop);
			// print_r($search_shop_catetories);

			$managedKeywords = $this->apisearchadnaver->ncc_managedKeyword($keyword);
			sleep(2);
			$keywordstool = null;
			$search_totals['search'] = 0;
			if(isset($managedKeywords[0])){
				$managedKeyword = $managedKeywords[0]['managedKeyword'];
				$keywordstool = $this->apisearchadnaver->keywordstool($keyword);
				// print_r($keywordstool);exit;
				$search_totals['search'] = $keywordstool['keywordList'][0]['monthlyTotalQcCnt']; //검색수 추가
			}


			if(!$search_totals['search']){
				$search_totals['competitive_strength'] = 0;
			}else{
				$search_totals['competitive_strength'] = $search_totals['shop']/$search_totals['search']; //경쟁강도 추가
			}
			//상위 5개 뽑기
			$keywordstool_topN = array();
			if(isset($keywordstool['keywordList'])){
				for($i=0,$m=5;$i<$m;$i++){
					if(!isset($keywordstool['keywordList'][$i]['relKeyword'])){ break;}
					$keywordstool_topN[] = $keywordstool['keywordList'][$i]['relKeyword'];
				}
			}

			//--- 네이버 검색 데이터 랩용
			$tm = time();
			$startDate=date('Y-m-01',$tm-86400*365*2);
			$endDate=date('Y-m-d',$tm);
			$timeUnit='month'; // date,week,month

			$keywordGroups=array();
			// $keywordGroups=array(array('groupName'=>$keyword,'keywords'=>array($keyword)));
			// print_r($keywordstool_topN);
			// exit;
			foreach ($keywordstool_topN as $v) {
				$keywordGroups[]=array('groupName'=>$v,'keywords'=>array($v));
			}
			if(isset($keywordGroups[0])){
				$device=null;
				$gender=null;
				$ages=null;
				$datalab_search = $this->apiopenapinavercom->v1_datalab_search($startDate,$endDate,$timeUnit,$keywordGroups,$device,$gender,$ages);
				// var_dump($datalab_search);exit;
				$datalab_search = $this->apiopenapinavercom->extend_results($datalab_search);
			}


			// var_dump($datalab_search);exit;
			//--- 네이버 데이터랩 쇼핑
			// $startDate='2019-11-01';
			// $endDate='2020-10-31';
			// $timeUnit='month';
			$category=(string)$search_shop_catetories[0]['cid1'];
			// $keyword='가습기';
			$device=null;
			$gender=null;
			$ages=null;
			if(isset($category[0])){
				$datalab_shops = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_all($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
			}else{
				// $datalab_shops = null;
			}
			// print_r($datalab_shops);
			// exit;
		}


		$this->load->view(
			$this->view_dir.'keyword',
			array(
				'conf'=>$conf,
				'param'=>$param,
				'keyword'=>$keyword,
				'search_totals'=>$search_totals,
				'managedKeyword'=>$managedKeyword,
				'keywordstool'=>$keywordstool,
				'datalab_search'=>$datalab_search,
				'datalab_shops'=>$datalab_shops,
				'search_shop_catetories'=>$search_shop_catetories,
			)
		);
	}
	public function category($conf,$param){

		$cid = $this->input->get('cid');

		// $date_st = $this->input->get('date_st');
		// if(!$date_st) $date_st = date('Y-m-d',time()-60*60*24*31);
		// $date_ed = $this->input->get('date_ed');
		// if(!$date_ed) $date_ed = date('Y-m-d',time()-60*60*24);

		$date_period = $this->input->get('date_period');
		// if(!$date_period) $date_period = 365;
		if(!$date_period) $date_period = 30;

		$date_st = date('Y-m-d',time()-60*60*24*$date_period);
		$date_ed = date('Y-m-d',time()-60*60*24);

		$shw = $this->input->get('shw');
		if(!$shw) $shw = '';
		$shw = trim(preg_replace('/^,+|,+$/','',$shw));
		$group_type = $this->input->get('group_type');
		// if(!$group_type) $group_type = 'day';
		// if(!$group_type) $group_type = 'month';
		if(!$group_type) $group_type = 'day';
		if($date_period>=365){
			$group_type = 'month';
		}

		$width_dome = 1;

		$period = (strtotime($date_ed) - strtotime($date_st)) / 86400 +1;

		if($period>1000){
			show_error('기간은 최대 1000일까지 설정이 가능합니다.');
		}


		$rowss = null;
		$def_date_array = null;
		$shws = array();
		if($cid){
			if(isset($shw[0])){
				$shws = preg_split('/[\t,]+/',$shw);
				$shws = array_unique($shws);
				$shw = implode(',',$shws);
				// $rowss = $this->kr_m->rows_per_days_extended($this->kr_m->rows_per_days_by_keywords($cid,$date_st,$date_ed,$shws),$date_st,$date_ed);
				$rowss = $this->kr_m->rows_4_group_extended($this->kr_m->rows_4_group_by_keywords($group_type,$cid,$date_st,$date_ed,$shws,$width_dome),$group_type,$date_st,$date_ed);

			}else{
				// $rowss = $this->kr_m->rows_per_days_extended($this->kr_m->rows_per_days($cid,$date_st,$date_ed),$date_st,$date_ed);
				$rowss = $this->kr_m->rows_4_group_extended($this->kr_m->rows_4_group($group_type,$cid,$date_st,$date_ed,$width_dome),$group_type,$date_st,$date_ed);

			}
			$def_date_array = $this->kr_m->array_date_key($group_type,$date_st,$date_ed,'text');


		}
		$this->load->view($this->view_dir.'category',array(
			'conf'=>$conf,
			'param'=>$param,
			'cids'=>$this->cids,
			'nsc_rows'=>$this->nsc_rows,
			'cid'=>$cid,
			// 'gdk_date'=>$gdk_date,
			'date_st'=>$date_st,
			'date_ed'=>$date_ed,
			'rowss'=>$rowss,
			'period'=>$period,
			'shw'=>$shw,
			'shws'=>$shws,
			'group_type'=>$group_type,
			'group_types'=>$this->group_types,
			'def_date_array'=>$def_date_array,
			'date_period'=>$date_period,
		));
	}
	public function naver_category($conf,$param){
		$ca_rows = $this->kr_m->select_naver_shop_category(array(),'*','nsc_id_1,nsc_id_2,nsc_id_3,nsc_id_4');
		// print_r($ca_rows);
		$this->load->view($this->view_dir.'naver_category',array(
			'conf'=>$conf,
			'param'=>$param,
			'ca_rows'=>$ca_rows,
		));
	}

	public function cat_keyword($conf,$param){
		// $cat_tree = $this->kr_m->tree_naver_shop_category();
		// print_r($ca_rows);
		$cid = $this->input->get('cid');
		$this->load->view($this->view_dir.'cat_keyword',array(
			'conf'=>$conf,
			'param'=>$param,
			'cid'=>$cid
		));
	}
	public function js_cat_rows($conf,$param){
		$sec = 60*60*24; //하루. 더 길게해도 문제 없다.(파일 수정 기능이 없기 때문에)
		$etag = date('Hi').ceil(date('s')/$sec).substr(md5('js_cat_tree'),0,6);

		//$msgs = array();
		if( MHeader::etag($etag)){ //etag는 사용하지 말자.
		//$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('etag 동작');
		}else if(MHeader::lastModified($sec)){
		//$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('lastModified 동작');
		}
		MHeader::expires($sec);

		// $cat_tree = $this->kr_m->tree_naver_shop_category();
		$cat_rows = $this->kr_m->select_tree_naver_shop_category();

		header('Content-Type: text/javascript; charset=utf-8');
		echo 'var cat_rows = ';
		echo pretty_json_encode($cat_rows);
		echo ";\n";
		// echo json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK);
		exit;
	}
	public function rel_keyword($conf,$param){
		$keyword = $this->input->get('keyword');
		if(!isset($keyword[0])) $keyword = '';
		$rs_keywords = null;

		if(isset($keyword[0])){
			$keywordstool = $this->apisearchadnaver->keywordstool($keyword);

			if(isset($keywordstool['keywordList'])){
				$keywordList = array_slice($keywordstool['keywordList'],0,1000);
				$rs_keywords = $this->kr_m->select_rel_keyword_by_keywordList($keywordList);
				unset($keywordstool,$keywordList);
			}
		}
		// $ca_tree = $this->kr_m->tree_naver_shop_category();
		// print_r($ca_rows);
		$this->load->view($this->view_dir.'rel_keyword',array(
			'conf'=>$conf,
			'param'=>$param,
			'keyword'=>$keyword,
			// 'keywordstool'=>$keywordstool,
			'rs_keywords'=>$rs_keywords,
		));
	}
	public function ajax_cat_keyword($conf,$param){
		$cid = $this->input->get('cid');

		$sec = 60*60*24; //하루. 더 길게해도 문제 없다.(파일 수정 기능이 없기 때문에)
		$etag = date('Hi').ceil(date('s')/$sec).substr(md5($cid),0,6);

		//$msgs = array();
		if( MHeader::etag($etag)){ //etag는 사용하지 말자.
		//$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('etag 동작');
		}else if(MHeader::lastModified($sec)){
		//$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('lastModified 동작');
		}
		MHeader::expires($sec);

		if(!isset($cid[0])){
			show_error('cid');
		}
		$rows = $this->kr_m->select_cat_keyword_by_cid($cid);
		header('Content-Type: application/json');
		echo pretty_json_encode($rows,JSON_NUMERIC_CHECK);
		// echo json_encode($rows,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT|JSON_NUMERIC_CHECK);
		exit;
	}


	public function parse_keyword($conf,$param)
	{
		$keyword_str = $this->input->get('keyword_str');
		if(!isset($keyword_str[0])){
			$keyword_str = '';
		}
		$this->load->view($this->view_dir.'parse_keyword',array(
			'conf'=>$conf,
			'param'=>$param,
			'keyword_str'=>$keyword_str,
		));
	}


	//====================
	public function get_searchad_info($keyword){
		$keyword = strtoupper($keyword);
		$rows = array();
		$keywordstool = $this->apisearchadnaver->keywordstool($keyword);
		if(!isset($keywordstool['keywordList'][0])){
			return  null;
		}
		foreach ($keywordstool['keywordList'] as $r) {
			// print_r($r);exit;
			$row = array();
			$row['relKeyword'] = preg_replace('/[\s\t\r\n]/','',$r['relKeyword']);
			$row['kr_monthlyPcQcCnt'] =  preg_replace('/[^\d]/','',$r['monthlyPcQcCnt']);
			$row['kr_monthlyMobileQcCnt'] = preg_replace('/[^\d]/','',$r['monthlyMobileQcCnt']);
			$row['kr_monthlyAvePcClkCnt'] =$r['monthlyAvePcClkCnt'];
			$row['kr_monthlyAveMobileClkCnt'] = $r['monthlyAveMobileClkCnt'];
			$row['kr_monthlyAvePcCtr'] = $r['monthlyAvePcCtr'];
			$row['kr_monthlyAveMobileCtr'] = $r['monthlyAveMobileCtr'];
			$row['kr_plAvgDepth'] = $r['plAvgDepth'];
			$row['kr_compIdx'] = $r['compIdx'];
			$rows[]=$row;
		}
		if($rows[0]['relKeyword'] != $keyword){
			$row = array();
			$row['relKeyword'] = $keyword;
			$row['kr_monthlyPcQcCnt'] =  0;
			$row['kr_monthlyMobileQcCnt'] = 0;
			$row['kr_monthlyAvePcClkCnt'] = 0;
			$row['kr_monthlyAveMobileClkCnt'] = 0;
			$row['kr_monthlyAvePcCtr'] = 0;
			$row['kr_monthlyAveMobileCtr'] = 0;
			$row['kr_plAvgDepth'] = 0;
			// $row['kr_compIdx'] = $r['compIdx'];
			$rows[]=$row;
		}
		// print_r($rows);exit;
		return $rows;
	}
	public function get_openapi_info($keyword){
		if(!isset($keyword[0])){
			show_error('keyword 가 필요합니다.');
		}
		$row = array();

		//-- API 사용시
		// $this->apiopenapinavercom->error_exit = false;
		// $t = $this->apiopenapinavercom->v1_search_call_json('shop',$keyword,'1','1','sim');
		// $this->apiopenapinavercom->error_exit = true;
		// if($t==null){
		// 	return null;
		// }
		// $row['kr_search_total_shop'] = $t['total'];

		//-- 크롤링 사용시
		$this->crawlingnaver->error_exit = false;
		$row['kr_search_total_shop'] = $this->crawlingnaver->crawling_shop_by_keyword($keyword);
		$this->crawlingnaver->error_exit = true;
		if($row['kr_search_total_shop']==0){
			return null;
		}
		return $row;
	}
	public function get_keyword_info($keyword,$getOpenAPI = true,$getSearchad = true){
		if(!isset($keyword[0])){
			show_error('keyword 가 필요합니다.');
		}
		$row = array();

		//-- API 사용시
		// $this->apiopenapinavercom->error_exit = false;
		// $t = $this->apiopenapinavercom->v1_search_call_json('shop',$keyword,'1','1','sim');
		// $this->apiopenapinavercom->error_exit = true;
		// if($t==null){
		// 	return null;
		// }
		// $row['kr_search_total_shop'] = $t['total'];

		//-- 크롤링 사용시
		$this->crawlingnaver->error_exit = false;
		if($getOpenAPI){
			$row['kr_search_total_shop'] = $this->crawlingnaver->crawling_shop_by_keyword($keyword);
			$this->crawlingnaver->error_exit = true;
			if($row['kr_search_total_shop']==0){
				return null;
			}
			return $row;
		}


		if($getSearchad){
			$keywordstool = $this->apisearchadnaver->keywordstool($keyword);
			if(!isset($keywordstool['keywordList'][0])){
				return  $row;
				// show_error('네이버 검색광고에서 키워드 정보가 없습니다.');

			}
			$t = $keywordstool['keywordList'][0];
			$row['kr_monthlyPcQcCnt'] =  preg_replace('/[^\d]/','',$t['monthlyPcQcCnt']);
			$row['kr_monthlyMobileQcCnt'] = preg_replace('/[^\d]/','',$t['monthlyMobileQcCnt']);
			$row['kr_monthlyAvePcClkCnt'] =$t['monthlyAvePcClkCnt'];
			$row['kr_monthlyAveMobileClkCnt'] = $t['monthlyAveMobileClkCnt'];
			$row['kr_monthlyAvePcCtr'] = $t['monthlyAvePcCtr'];
			$row['kr_monthlyAveMobileCtr'] = $t['monthlyAveMobileCtr'];
			$row['kr_plAvgDepth'] = $t['plAvgDepth'];
			$row['kr_compIdx'] = $t['compIdx'];
			$row['kr_relKeywordCount'] = count($keywordstool['keywordList']);
		}

		// $row['kr_competitive_strength']= $row['kr_search_total_shop']/($row['kr_monthlyPcQcCnt']+$row['kr_monthlyMobileQcCnt']);
		// unset($keywordstool);
		// print_r($row);exit;
		return $row;
	}

	public function keyword_info($conf,$param){ //테스트용
		$keyword = $this->input->get('keyword');
		$row = $this->get_keyword_info($keyword);
		if($row){
			$this->krs_m->update_row_by_keyword($keyword,$row);
		}
		// $this->krs_m();
	}
	//  php ../index_cli.php  mh_service/item_search cli_update_keyword_rank_score 20000 0 0 3  // 로컬 컴퓨터 사용
	//  php ../index_cli.php  mh_service/item_search cli_update_keyword_rank_score 20000 1 1 3  // 프록시-1 사용
	//  php ../index_cli.php  mh_service/item_search cli_update_keyword_rank_score 20000 2 2 3  // 프록시-2 사용
	//
	//  php ../index_cli.php  mh_service/item_search cli_update_keyword_rank_score 20000 0 0 2  // 로컬 컴퓨터 사용
	//  php ../index_cli.php  mh_service/item_search cli_update_keyword_rank_score 20000 1 1 2  // 프록시 사용
	public function cli_update_keyword_rank_score($limit=10000,$use_proxy=0,$modN=0,$divN=0){
		if(!is_cli()){
			show_error('Only For CLI');
			return false;
		}
		$this->crawlingnaver->use_proxy = $use_proxy;
		$this->apisearchadnaver->use_proxy = $use_proxy;

		if($use_proxy==1){
			$conf_api_searchad_naver = $this->config->item('api_searchad_naver');
			$this->apisearchadnaver->set_account($conf_api_searchad_naver['accounts']['ac_1']);
		}


		$this->mh_cache->use_cache = false;
		$mtm0 = microtime(1);
		echo "[cli_update_keyword_rank_score]\n";
		$krs_rows = $this->krs_m->select_target_rows($limit,$modN,$divN);

		echo "[COUNT] ".count($krs_rows)."\n";
		$i=0;
		$err_cnt = 0;
		foreach ($krs_rows as $krs_row) {
			$i++;
			// $mtm1 = round(microtime(1)-$mtm0,2);
			if($err_cnt>50){exit('err_cnt > 50');}
			echo " [RUN][proxy:{$use_proxy}] {$i} / {$krs_row['kr_kwid']} / {$krs_row['kr_keyword']}\n";
			$keyword = $krs_row['kr_keyword'];
			if($keyword[0]=='%'){
				$mtm1 = round(microtime(1)-$mtm0,2);
				echo "[SKIP][{$mtm1}] start with %\n";
				$ki_row = array('kr_iserror'=>1);
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				// usleep(rand(2000000,3000000));
				$err_cnt++;
				continue;
			}
			$ki_row = $this->get_keyword_info($keyword);
			if($ki_row==null){
				$mtm1 = round(microtime(1)-$mtm0,2);
				echo " [ERROR][{$mtm1}]\n";
				$ki_row = array('kr_iserror'=>1);
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				$err_cnt++;
			}else{
				$ki_row['kr_iserror'] = 0;
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				$err_cnt = 0;
			}
			$mtm1 = round(microtime(1)-$mtm0,2);
			echo " [RESULT][{$mtm1}] {$r}\n";
			usleep(rand(2000000,3000000));
			// usleep(rand(4000000,5000000));
		}
		$mtm1 = round(microtime(1)-$mtm0,2);
		echo "[END] [{$mtm1}]\n";
		$this->mh_cache->use_cache = true;

	}


	// php ../index_cli.php  mh_service/item_search cli_update_openapi_keyword_rank_score 100000 0 0 3
	// php ../index_cli.php  mh_service/item_search cli_update_openapi_keyword_rank_score 100000 1 1 3
	// php ../index_cli.php  mh_service/item_search cli_update_openapi_keyword_rank_score 100000 2 2 3
	public function cli_update_openapi_keyword_rank_score($limit=10000,$use_proxy=0,$modN=0,$divN=0){
		if(!is_cli()){
			show_error('Only For CLI');
			return false;
		}
		$this->db->save_queries = false;

		$this->crawlingnaver->use_proxy = $use_proxy;

		$this->mh_cache->use_cache = false;
		$mtm0 = microtime(1);
		echo "[cli_update_openapi_keyword_rank_score]\n";
		$krs_rows = $this->krs_m->select_openapi_target_rows($limit,$modN,$divN);
		if(count($krs_rows)==0){
			exit("krs_rows count is ZERO.\n");
		}
		echo "[COUNT] ".count($krs_rows)."\n";
		$i=0;
		$err_cnt = 0;
		foreach ($krs_rows as $krs_row) {
			$i++;
			// $mtm1 = round(microtime(1)-$mtm0,2);
			if($err_cnt>50){exit('err_cnt > 50');}
			echo " [RUN:openapi][proxy:{$use_proxy}] {$i} / {$krs_row['kr_kwid']} / {$krs_row['kr_keyword']}\n";
			$keyword = $krs_row['kr_keyword'];
			if($keyword[0]=='%'){
				$mtm1 = round(microtime(1)-$mtm0,2);
				echo "[SKIP][{$mtm1}] start with %\n";
				$ki_row = array('kr_iserror'=>1);
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				// usleep(rand(2000000,3000000));
				$err_cnt++;
				continue;
			}
			$ki_row = $this->get_openapi_info($keyword);
			// var_dump($ki_row);exit;
			if($ki_row==null){
				$mtm1 = round(microtime(1)-$mtm0,2);
				echo " [ERROR][{$mtm1}]\n";
				$ki_row = array('kr_iserror'=>1);
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				$err_cnt++;
			}else{
				$ki_row['kr_iserror'] = 0;
				$r = $this->krs_m->update_row_by_keyword($keyword,$ki_row);
				$err_cnt = 0;

				$mtm1 = round(microtime(1)-$mtm0,2);
				echo " [RESULT][{$mtm1}] {$r} / {$ki_row['kr_search_total_shop']}\n";
			}
			// usleep(rand(1000000,1500000));
			usleep(rand(700000,1500000));
			// usleep(rand(4000000,5000000));
		}
		$mtm1 = round(microtime(1)-$mtm0,2);
		echo "[END] [{$mtm1}]\n";
		$this->mh_cache->use_cache = true;

	}


	// php ../index_cli.php  mh_service/item_search cli_update_searchad_keyword_rank_score 100000 0 0 3
	// php ../index_cli.php  mh_service/item_search cli_update_searchad_keyword_rank_score 100000 1 1 3
	// php ../index_cli.php  mh_service/item_search cli_update_searchad_keyword_rank_score 100000 2 2 3
	public function cli_update_searchad_keyword_rank_score($limit=10000,$use_proxy=0,$modN=0,$divN=0){
		if(!is_cli()){
			show_error('Only For CLI');
			return false;
		}
		$this->db->save_queries = false;

		$this->apisearchadnaver->use_proxy = $use_proxy;

		if($use_proxy > 0){
			$conf_api_searchad_naver = $this->config->item('api_searchad_naver');
			if(!isset($conf_api_searchad_naver['accounts']['ac_'.$use_proxy])){
				exit("check conf_api_searchad_naver['accounts']['ac_{$use_proxy}']");
			}
			$this->apisearchadnaver->set_account($conf_api_searchad_naver['accounts']['ac_'.$use_proxy]);
		}

		$this->mh_cache->use_cache = false;
		$mtm0 = microtime(1);
		echo "[cli_update_openapi_keyword_rank_score]\n";
		echo "[COUNT] {$limit}\n";
		$updated_cnt = 0;
		while($limit--){
			$krs_rows = $this->krs_m->select_searchad_target_rows(1,$modN,$divN);
			if(count($krs_rows)==0){
				exit("krs_rows count is ZERO.\n");
			}
			$i=0;
			$err_cnt = 0;
			foreach ($krs_rows as $krs_row) {
				$i++;
				// $mtm1 = round(microtime(1)-$mtm0,2);
				if($err_cnt>50){exit('err_cnt > 50');}
				echo " [RUN:openapi][proxy:{$use_proxy}] {$i} / {$krs_row['kr_kwid']} / {$krs_row['kr_keyword']}\n";
				$keyword = $krs_row['kr_keyword'];
				if($keyword[0]=='%'){
					$mtm1 = round(microtime(1)-$mtm0,2);
					echo "[SKIP][{$mtm1}] start with %\n";
					$si_row = array('kr_iserror'=>1);
					$r = $this->krs_m->update_row_by_keyword($keyword,$si_row);
					// usleep(rand(2000000,3000000));
					$err_cnt++;
					unset($si_row);
					continue;
				}
				$si_rows = $this->get_searchad_info($keyword,1,0);
				// var_dump($si_rows);exit;
				if($si_rows==null){
					$mtm1 = round(microtime(1)-$mtm0,2);
					echo " [ERROR][{$mtm1}]\n";
					$si_row = array('kr_iserror'=>1);
					$r = $this->krs_m->update_row_by_keyword($keyword,$si_row);
					$err_cnt++;
					unset($si_row);
				}else{
					foreach ($si_rows as $key => $si_row) {
						$relKeyword = $si_row['relKeyword'];
						unset($si_row['relKeyword']);
						$si_row['kr_iserror'] = 0;
						$r = $this->krs_m->update_row_by_keyword($relKeyword,$si_row);
						$updated_cnt+=$r;
						echo " [UPDATE] {$r} / {$relKeyword} / {$si_row['kr_monthlyPcQcCnt']}\n";
						unset($si_row);
					}
					$err_cnt = 0;
				}
				$mtm1 = round(microtime(1)-$mtm0,2);
				echo " [RESULT][{$mtm1}] {$updated_cnt} / remain loop {$limit} / ".memory_get_usage().'/'.memory_get_peak_usage()."\n";
				usleep(rand(2000000,3000000));
				// usleep(rand(4000000,5000000));
			}
			unset($krs_row);
		}
		$mtm1 = round(microtime(1)-$mtm0,2);
		echo "[END] [{$mtm1}]\n";
		$this->mh_cache->use_cache = true;

	}

	public function test_crawling($conf,$param){
		$r = $this->crawlingnaver->crawling_shop_by_keyword('%ec%95%84%ed%86%a0%ed%8c%9c%20%ec%88%98%eb%94%a9%20%ec%a0%a4%20%eb%a1%9c%ec%85%98%20120ml');
		print_r($r);
	}
	public function test($conf,$param){
		// $keywords = '가습기,히터';
		// $res = $this->apisearchadnaver->ncc_managedKeyword($keywords);
		// var_dump($res);
		// $res = $this->apisearchadnaver->keywordstool($keywords,'','','','',1);
		// var_dump($res);
		// var_dump($this->conf_searchad_naver);
		// -------------------------
		$startDate='2019-11-01';
		$endDate='2020-10-31';
		$timeUnit='month';
		$category='50000003';
		$keyword='가습기';
		$device=null;
		$gender=null;
		$ages=null;
		$res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_all($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		var_dump($res);exit;
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword=array(array('name'=>'가습기','param'=>array('가습기')),array('name'=>'히터','param'=>array('히터')));
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keywords($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_device($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_gender($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// // -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $category='50000008';
		// $keyword='가습기';
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_shopping_category_keyword_age($startDate,$endDate,$timeUnit,$category,$keyword,$device,$gender,$ages);
		// var_dump($res);
		// exit;
		// -------------------------
		// $startDate='2020-01-01';
		// $endDate='2020-10-31';
		// $timeUnit='month';
		// $keywordGroups=array(array('groupName'=>'가습기','keywords'=>array('가습기')),array('groupName'=>'히터','keywords'=>array('히터')));
		// $device=null;
		// $gender=null;
		// $ages=null;
		// $res = $this->apiopenapinavercom->v1_datalab_search($startDate,$endDate,$timeUnit,$keywordGroups,$device,$gender,$ages);
		// var_dump($res);
		// ------------------------- // 네이버 검색
		$query = '가습기';
		// $res = $this->apiopenapinavercom->v1_search_blog_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_cafearticle_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_kin_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_webkr_json($query,'1','1','sim');
		// var_dump($res);
		// $res = $this->apiopenapinavercom->v1_search_shop_json($query,'1','1','sim');
		// var_dump($res);
		$res = $this->apiopenapinavercom->v1_search_totals($query,'1','1','sim');
		var_dump($res);

		exit;
	}
}
