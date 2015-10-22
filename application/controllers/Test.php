<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		//$this->load->database();
		$this->connDB();
		
		$query = $this->db->select('count(*) CNT')->from('mv7b_tech')->where('b_dttm_w >=',date('YmdHis',time()-60*60*24*30))->order_by('b_idx','DESC')->limit(0,10);
		//print_r($query->get_compiled_select());
		$rows = $query->get()->result_array();
		
		$data = array();
		$data['title'] = '테스트';
		$data['rows'] = $rows;
		
		
		$this->load->view('test_view',$data);
	}
	public function connDB()
	{
		if($this->config->config['isDev']){ //개발일경우
			$this->load->database('dev');
		}else{
			$this->load->database('real');
		}
		
	}
	
}
