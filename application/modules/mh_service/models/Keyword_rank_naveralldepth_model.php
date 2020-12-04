<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Keyword_rank_model.php');

//== GA 도매국 키워드(검색어) 모델

class Keyword_rank_naveralldepth_model extends Keyword_rank_model {

	public $tbl='keyword_rank_naveralldepth';
	public $tbl_words='keyword_rank_naveralldepth_words';
	public $tbl_data='keyword_rank_naveralldepth_data';
	public $tbl_view='keyword_rank_naver_vw';
	public $tbl_score='keyword_rank_naveralldepth_score';
	// public $fileds = array();

	public function __construct()
	{
		parent::__construct();

	}

}
