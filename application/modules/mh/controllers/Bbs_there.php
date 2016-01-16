<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Bbs.php');

class Bbs_there extends Bbs {

	public function __construct()
	{
		parent::__construct();

	}
	
	public function _remap($method, $params = array())
	{
		$this->index($params);
	}
}






