<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Ga_dashboard extends MX_Controller {
	private $client = null;
	private $profileId = null;
	public function __construct($conf=array())
	{

		$this->load->library('Mh_cache');
		$this->load->library('Mproxy');
		$this->config->load('google_oauth2');
		// $this->bbs_conf = ;
		$this->load->library('GoogleOAuth2');

		$conf_google_oauth2 = $this->config->item('google_oauth2');
		$this->googleoauth2->set_mproxy($this->mproxy);
		$this->client =& $this->googleoauth2->client;
		$this->googleoauth2->set_client($conf_google_oauth2['clients']['default']);
		$this->googleoauth2->set_access_token($conf_google_oauth2['access_tokens']['analytics.readonly']);
		$this->cache_key = 'analytics.readonly';

		// $this->load->library('GoogleAnalyticsApi');
		// $this->googleanalyticsapi->set_mproxy($this->mproxy);

		$this->load->library('GoogleAnalyticsReportingApiV4');
		$this->googleanalyticsreportingapiv4->set_mproxy($this->mproxy);
		// $this->googleanalyticsreportingapiv4->set_access_token($access_token['access_token']);

		$this->profileId = $conf['menu']['mn_arg1'];
		$this->daysAgo = isset($conf['menu']['mn_arg2'][0])?$conf['menu']['mn_arg2']:14;
		if(!isset($this->profileId[0])){
			show_error('mn_arg1(profileId) is empty.');
		}
	}

		// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		// print_r($param);exit;
		$method = isset($param[0][0])?$param[0]:'index';
		if(!method_exists($this,$method)){
			// show_error("지정 메소드가 없습니다.");
			$method = 'index';
		}
		$this->{$method}($conf,$param);
	}


	public function index($conf,$param){
		$this->dashboard($conf,$param);
	}

	public function get_access_token(){
		$key = $this->cache_key;
		// $this->mh_cache->use_log_header = 1;
		$access_token = $this->mh_cache->get($key);
		if(!$access_token){
			$access_token = $this->googleoauth2->refreshed_access_token();
			if(!isset($access_token['access_token'][5])){
				show_error('Wrong access_token 1');
			}
			$this->mh_cache->save($key,$access_token,60*50);
		}
		if(!isset($access_token['access_token'][5])){
			show_error('Wrong access_token 2');
		}
		return $access_token['access_token'];
	}

	public function dashboard($conf,$param){
		$key='ga_dashboard_'.$this->profileId.'_'.$this->daysAgo;
		$rowss = $this->mh_cache->get($key);
		$nocache = $this->input->get('nocache');
		if($nocache || !$rowss){
			$rowss = $this->get_ga_rowss($this->profileId,$this->daysAgo);
			
			$this->mh_cache->save($key,$rowss,60*30); //30분 캐시
		}
		// var_dump($rowss);exit;
		if(!isset($rowss['per_date']) || $rowss['per_date']==null){
			show_error('데이터가 없습니다.');
		}
		// print_r($rowss);
		// echo 'dashboard';

		$this->load->view(
			'mh_admin/ga_dashboard/dashboard',
			array(
				'conf'=>$conf,
				'param'=>$param,
				'rowss'=>$rowss,
				'daysAgo'=>$this->daysAgo
			)
		);
	}
	private function get_ga_rowss($profileId,$daysAgo){
		$rowss = array();
		$rowss['createdAt'] = date('Y-m-h H:i:s');
		$access_token = $this->get_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		$this->googleanalyticsreportingapiv4->set_access_token($access_token);
		$posts = array(
			'reportRequests'=>array(
				// USER,NEW USER,PV,UPV per date
				array(
					'viewId'=>$profileId,
					'dateRanges'=>array(
						"startDate"=>$daysAgo.'daysAgo',
						"endDate"=>"today"
					),
					'dimensions'=>array(
						array("name"=>"ga:date")
					),
					'metrics'=>array(
						array("expression"=>"ga:users"),
						array("expression"=>"ga:newUsers"),
						array("expression"=>"ga:sessions"),
						array("expression"=>"ga:uniquePageviews"),
						array("expression"=>"ga:pageviews"),
					),
					// 'orderBys'=>array(
					// 	array(
					// 		"fieldName"=> "ga:pageviews",
					// 		"sortOrder"=> "DESCENDING"
					// 	)
					// ),
					'samplingLevel'=>'SMALL', //SMALL , LARGE, DEFAULT
					// 'segments'=>array(
					// 	array(
					// 		"segmentId"=> "gaid::-11"
					// 	)
					// ),
					// 'dimensionFilterClauses'=>array(
					// 	'filters'=>array(
					// 		"dimension_name"=>"ga:browser",
					// 		"operator"=>"EXACT",
					// 		"expressions"=>array("Firefox"),
					// 	)
					// ),
					// "filtersExpression"=> "ga:browser==Firefox",
					// "includeEmptyRows"=> "true",
					// "pageToken"=>"10000",
					"pageSize"=> 365*2,
				),
				//-- PAGE PV
				array(
					'viewId'=>$profileId,
					'dateRanges'=>array(
						"startDate"=>$daysAgo.'daysAgo',
						"endDate"=>"today"
					),
					'dimensions'=>array(
						array("name"=>"ga:pagePath"),
						// array("name"=>"ga:pageTitle")
					),
					'metrics'=>array(
						array("expression"=>"ga:pageviews"),
						array("expression"=>"ga:uniquePageviews"),
						array("expression"=>"ga:bounceRate"),
					),
					'orderBys'=>array(
						array(
							"fieldName"=> "ga:pageviews",
							"sortOrder"=> "DESCENDING"
						)
					),
					'samplingLevel'=>'SMALL', //SMALL , LARGE, DEFAULT
					// 'segments'=>array(
					// 	array(
					// 		"segmentId"=> "gaid::-11"
					// 	)
					// ),
					// 'dimensionFilterClauses'=>array(
					// 	'filters'=>array(
					// 		"dimension_name"=>"ga:browser",
					// 		"operator"=>"EXACT",
					// 		"expressions"=>array("Firefox"),
					// 	)
					// ),
					// "filtersExpression"=> "ga:browser==Firefox",
					// "includeEmptyRows"=> "true",
					// "pageToken"=>"10000",
					"pageSize"=>"10",
				),
				//-- SEARCH PV
				array(
					'viewId'=>$profileId,
					'dateRanges'=>array(
						"startDate"=>$daysAgo.'daysAgo',
						"endDate"=>"today"
					),
					'dimensions'=>array(
						array("name"=>"ga:searchKeyword"),
						// array("name"=>"ga:pagePath")
					),
					'metrics'=>array(
						array("expression"=>"ga:searchResultViews"),
						array("expression"=>"ga:searchUniques"),
						array("expression"=>"ga:bounceRate"),
					),
					'orderBys'=>array(
						array(
							"fieldName"=> "ga:searchResultViews",
							"sortOrder"=> "DESCENDING"
						)
					),
					'samplingLevel'=>'SMALL', //SMALL , LARGE, DEFAULT
					// 'segments'=>array(
					// 	array(
					// 		"segmentId"=> "gaid::-11"
					// 	)
					// ),
					// 'dimensionFilterClauses'=>array(
					// 	'filters'=>array(
					// 		"dimension_name"=>"ga:browser",
					// 		"operator"=>"EXACT",
					// 		"expressions"=>array("Firefox"),
					// 	)
					// ),
					// "filtersExpression"=> "ga:pagePath=~.*/list?.*", //목록만 검색
					// "includeEmptyRows"=> "true",
					// "pageToken"=>"10000",
					"pageSize"=>"10",
				),
				//-- source , session, 이탈률
				array(
					'viewId'=>$profileId,
					'dateRanges'=>array(
						"startDate"=>$daysAgo.'daysAgo',
						"endDate"=>"today"
					),
					'dimensions'=>array(
						array("name"=>"ga:sourceMedium"),
					),
					'metrics'=>array(
						array("expression"=>"ga:sessions"),
						array("expression"=>"ga:organicSearches"),
						array("expression"=>"ga:bounceRate"),
					),
					'orderBys'=>array(
						array(
							"fieldName"=> "ga:sessions",
							"sortOrder"=> "DESCENDING"
						)
					),
					'samplingLevel'=>'SMALL', //SMALL , LARGE, DEFAULT
					"pageSize"=>"10",
				),
				//-- source , session, 이탈률
				array(
					'viewId'=>$profileId,
					'dateRanges'=>array(
						"startDate"=>$daysAgo.'daysAgo',
						"endDate"=>"today"
					),
					'dimensions'=>array(
						array("name"=>"ga:keyword"),
					),
					'metrics'=>array(
						array("expression"=>"ga:sessions"),
						array("expression"=>"ga:organicSearches"),
						array("expression"=>"ga:bounceRate"),
					),
					'orderBys'=>array(
						array(
							"fieldName"=> "ga:sessions",
							"sortOrder"=> "DESCENDING"
						)
					),
					// "filtersExpression"=> "ga:keyword=~..*", //목록만 검색
					'samplingLevel'=>'SMALL', //SMALL , LARGE, DEFAULT
					"pageSize"=>"10",
				),
			)
		);
		$res = $this->googleanalyticsreportingapiv4->data_ga($posts);
		// print_r($res);exit;
		$res_rowss = $this->googleanalyticsreportingapiv4->extract_rowss($res);
		// print_r($res_rowss);exit;
		if(!isset($res_rowss[0])){
			return null;
		}
		$rowss = array(
			'per_date' => $res_rowss[0],
			'pages' => $res_rowss[1],
			'searchs' => $res_rowss[2],
			'sources' => $res_rowss[3],
			'keywords' => $res_rowss[4],
			'total_per_date' => $res['reports'][0]['data']['totals'][0]['values'],
			'total_pages' => $res['reports'][1]['data']['totals'][0]['values'],
			'total_searchs' => $res['reports'][2]['data']['totals'][0]['values'],
			'total_sources' => $res['reports'][3]['data']['totals'][0]['values'],
			'total_keywords' => $res['reports'][4]['data']['totals'][0]['values'],
			'createdAt' => date('Y-m-h H:i:s'),
		);
		// print_r($rowss);
		// exit;
		return $rowss;
	}



	public function analytics_test($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		header('Content-Type: application/json');

		$this->load->library('GoogleAnalyticsApi');
		$this->googleanalyticsapi->set_mproxy($this->mproxy);
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		$this->googleanalyticsapi->set_access_token($access_token['access_token']);
		// $res = $this->googleanalyticsapi->accountSummaries();
		//-- ex
		$profileId = '54658549'; //Lee Minsu/mins01.com/homepage
		// ga:searchResultViews //검색수
		// ga:searchUniques //유니크 검색수
		// *pageviewsPerSearch = ga:searchResultViews / ga:searchUniques //검색당 굘과 페이지뷰수
		// ga:searchExitRate // 검색 후 종료율
		// ga:percentSearchRefinements //재검색율
		// ga:avgSearchDuration //검색후 시간
		// ga:avgSearchDepth //평균검색심도
		$gets = array(
			'ids'=> 'ga:'.$profileId,
	    // 'start-date'=> 'yesterday',
	    // 'end-date'=> 'today',
			'start-date'=> '2daysAgo',
			'end-date'=> 'yesterday',
			// 검색어용
			'dimensions'=> 'ga:searchKeyword,ga:date',
			'metrics'=> 'ga:searchResultViews,ga:searchUniques,ga:searchExitRate,ga:percentSearchRefinements,ga:avgSearchDuration,ga:avgSearchDepth',
			'sort'=> '-ga:searchUniques',
			// 일반 페이지용
			// 'dimensions'=> 'ga:pagePath,ga:date',
			// 'metrics'=> 'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage,ga:entrances,ga:bounceRate,ga:exitRate',
			// 'sort'=> '-ga:pageviews',
			// 필터
			// 'filters'=>'ga:pagePath=~^/main/item/itemList.php?'
			'max-results'=> 10
		);
		$res = $this->googleanalyticsapi->data_ga($gets);

		print_r($res);
		exit;
	}


	public function analytics_test_v4($conf,$param){
		$access_token = $this->googleoauth2->refreshed_access_token(); //캐싱 해야함!! 꼭 60분 캐싱하자
		header('Content-Type: application/json');

		$this->load->library('GoogleAnalyticsReportingApiV4');
		$this->googleanalyticsreportingapiv4->set_mproxy($this->mproxy);
		$this->googleanalyticsreportingapiv4->set_access_token($access_token['access_token']);
		// $res = $this->googleanalyticsapi->accountSummaries();
		//-- ex
		$profileId = '54658549'; //Lee Minsu/mins01.com/homepage


		$posts = array(
			'reportRequests'=>array(
					array(
						'viewId'=>$profileId,
						'dateRanges'=>array(
							"startDate"=>"7daysAgo",
							"endDate"=>"yesterday"
						),
						'dimensions'=>array(
							array("name"=>"ga:searchKeyword"),
							array("name"=>"ga:date")
						),
						'metrics'=>array(
							array("expression"=>"ga:searchResultViews"),
							array("expression"=>"ga:searchUniques"),
							array("expression"=>"ga:searchExitRate"),
							array("expression"=>"ga:percentSearchRefinements"),
							array("expression"=>"ga:avgSearchDuration"),
							array("expression"=>"ga:avgSearchDepth"),
						),
						'orderBys'=>array(
							array(
								"fieldName"=> "ga:searchUniques",
								"sortOrder"=> "DESCENDING"
							)
						),
						'samplingLevel'=>'LARGE', //SMALL , LARGE, DEFAULT
						// 'segments'=>array(
						// 	array(
						// 		"segmentId"=> "gaid::-11"
						// 	)
						// ),
						// 'dimensionFilterClauses'=>array(
						// 	'filters'=>array(
						// 		"dimension_name"=>"ga:browser",
						// 		"operator"=>"EXACT",
						// 		"expressions"=>array("Firefox"),
						// 	)
						// ),
						// "filtersExpression"=> "ga:browser==Firefox",
						// "includeEmptyRows"=> "true",
						// "pageToken"=>"10000",
						"pageSize"=>"10",
					),
					array(
						'viewId'=>$profileId,
						'dateRanges'=>array(
							"startDate"=>"7daysAgo",
							"endDate"=>"yesterday"
						),
						// 	// 'dimensions'=> 'ga:pagePath,ga:date',
						// 	// 'metrics'=> 'ga:pageviews,ga:uniquePageviews,ga:avgTimeOnPage,ga:entrances,ga:bounceRate,ga:exitRate',
						// 	// 'sort'=> '-ga:pageviews',
						'dimensions'=>array(
							array("name"=>"ga:pagePath"),
							array("name"=>"ga:date")
						),
						'metrics'=>array(
							array("expression"=>"ga:pageviews"),
							array("expression"=>"ga:uniquePageviews"),
							array("expression"=>"ga:avgTimeOnPage"),
							array("expression"=>"ga:entrances"),
							array("expression"=>"ga:bounceRate"),
							array("expression"=>"ga:exitRate"),
						),
						'orderBys'=>array(
							array(
								"fieldName"=> "ga:pageviews",
								"sortOrder"=> "DESCENDING"
							)
						),
						'samplingLevel'=>'LARGE', //SMALL , LARGE, DEFAULT
						// 'segments'=>array(
						// 	array(
						// 		"segmentId"=> "gaid::-11"
						// 	)
						// ),
						// 'dimensionFilterClauses'=>array(
						// 	'filters'=>array(
						// 		"dimension_name"=>"ga:browser",
						// 		"operator"=>"EXACT",
						// 		"expressions"=>array("Firefox"),
						// 	)
						// ),
						// "filtersExpression"=> "ga:browser==Firefox",
						// "includeEmptyRows"=> "true",
						// "pageToken"=>"10000",
						"pageSize"=>"10",
					),
			)
		);
		$res = $this->googleanalyticsreportingapiv4->data_ga($posts);
		$rowss = $this->googleanalyticsreportingapiv4->extract_rowss($res);

		print_r($rowss);
		print_r($res);
		exit;
	}




}
