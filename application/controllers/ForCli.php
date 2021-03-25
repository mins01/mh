<?php
class ForCli extends CI_Controller {

	public function message($to = 'World')
	{
		echo "Hello {$to}!".PHP_EOL;
	}
	
	public function is_dev(){
		var_dump(IS_DEV);
		var_dump($this->db);
	}
}
