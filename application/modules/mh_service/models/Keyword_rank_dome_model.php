<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Keyword_rank_model.php');

//== GA 도매국 키워드(검색어) 모델

class Keyword_rank_dome_model extends Keyword_rank_model {

	public $tbl='keyword_rank_dome';
	// public $fileds = array();

	public function __construct()
	{
		parent::__construct();

	}

}
