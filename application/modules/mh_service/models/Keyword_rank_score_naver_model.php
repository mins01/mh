<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(dirname(__FILE__).'/Keyword_rank_score_model.php');

//==

class Keyword_rank_score_naver_model extends Keyword_rank_score_model {

	public $tbl_score='keyword_rank_naveralldepth_score';
	public $tbl_words='keyword_rank_naveralldepth_words';
	public $tbl_data='keyword_rank_naveralldepth_data';


	public $fileds = array();

	public function __construct()
	{
		// $this->load->library('mh_cache');
		// Call the CI_Model constructor
		parent::__construct();

	}

}
