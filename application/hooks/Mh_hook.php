<?
class Mh_hook{
	private $CI;

	public function __construct(){
		$this->CI = &get_instance();
		
	}
	public function pre(){
	}
	public function post(){
		$layout_disable = $this->CI->config->item('layout_disable');
		if(!isset($layout_disable)){
			$layout_disable = false;
		}
		if($layout_disable){
		}else{
			
			$this->CI->load->module('mh/layout');
			$this->CI->output->set_output(
				$this->CI->layout->layout_head().
				$this->CI->output->get_output().
				$this->CI->layout->layout_tail()
			);
		}
	}
}