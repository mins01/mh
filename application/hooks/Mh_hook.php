<?
class Mh_hook{
	private $CI;

	public function __construct(){
		$this->CI = &get_instance();
		
	}
	public function pre(){
	}
	public function post(){
		$this->CI->load->module('mh/layout');
		$this->CI->output->set_output(
			$this->CI->layout->layout_head().
			$this->CI->output->get_output().
			$this->CI->layout->layout_tail()
		);
	}
}