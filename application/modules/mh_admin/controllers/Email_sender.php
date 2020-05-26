<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_sender extends MX_Controller {

  public $logedin;
  public $def_rc_group = null;
  public $def_rc_sub_group = null;

  public function __construct()
	{
		$this->load->helper('form');

    // $this->load->model('mh/recommend_model','recommend_m');
		// $this->load->model('mh/recommend_catalogs_model','rc_m');
		$this->m_row = $this->common->get_login();

	}


  public function _remap($method, $params = array())
	{
		$this->index($params);
	}

	public function set_base_url($base_url){
		$this->base_url = $base_url;
		// $this->bbs_m->set_base_url($base_url);
		// $this->bf_m->set_base_url($base_url);
	}
  // front 컨트롤에서 접근할 경우.
  public function index_as_front($conf,$param){
    $base_url = $conf['base_url'];
    $this->set_base_url($base_url);
    // $this->def_rc_group = isset($conf['menu']['mn_arg1'][0])?$conf['menu']['mn_arg1']:null;
    // $this->def_rc_sub_group = isset($conf['menu']['mn_arg2'][0])?$conf['menu']['mn_arg2']:null;
    // $this->method = isset($conf['menu']['mn_arg3'][0])?$conf['menu']['mn_arg3']:null;
    // $rc_idx = isset($param[0][0])?$param[0]:'';
    // $b_idx = isset($param[1][0])?$param[1]:null;
    $method = isset($param[0])?$param[0]:'form';
    $process = $this->input->post('process');
    $process = 'process_'.$process;
    $mode = 'mode_'.$method;
    if(method_exists($this,$process)){
      $this->{$process}($conf,$param);
    }else if(method_exists($this,$mode)){
      $this->{$mode}($conf,$param);
    }else{
      show_error('지원되지 않는 모드입니다.');
    }

  }



  private function mode_form($conf,$param){
    $this->load->view('mh_admin/email_sender/form',
      array(
        'base_url'=>$this->base_url,
      )
    );
  }
  // private function process_form($conf,$param){
  //   echo 'form';
  //   $to='new10@ggook.com,mins01.lycos.co.kr@gmail.com';
  //   $subject='테스트메일 제목';
  //   $message='테스트메일 메세지<br>{{date}}';
  //   $binds=array(
  //     'date'=>date('Y-m-d H:i:s'),
  //   );
  //   $this->send_mail($smtp_user,$smtp_pass,$from,$to,$subject,$message,$binds);
  // }

  private function process_send($conf,$param){
    // $posts = $this->input->post();
    // $from = $this->input->post('email_from');
    // $from = SITE_ADMIN_MAIL;
    $from = $this->input->post('email_from');
    $tos = $this->input->post('email_tos');
    $subject = $this->input->post('email_subject');
    $message = $this->input->post('email_message');
    $mailtype = $this->input->post('email_mailtype');
    $mail_conf = array(
      'mailtype'=>$mailtype,
    );
    $binds = array();
// header('Content-Type: text/plain');
    $ress = array();
    foreach ($tos as $to) {
      $to = preg_replace('/(\s|\n)/','',$to);
      $send_data = array(
        'from'=>$from,
        'to'=>$to,
        'subject'=>$subject,
        'message'=>$message,
        'binds'=>$binds,
      );
      $res = $this->send_mail($send_data,$mail_conf);
      $ress[] =$res;
    }
    // print_r($ress);
    $this->load->view('mh_admin/email_sender/process',
      array(
        'base_url'=>$this->base_url,
        'ress'=>$ress,
      )
    );

  }




  public function send_mail($send_data,$mail_conf = array()){
    // http://www.ciboard.co.kr/user_guide/kr/libraries/email.html
    $send_data = array_merge(array(
      'from'=>'',
      'to'=>'',
      'subject'=>'',
      'message'=>'',
      'binds'=>'',
    ),$send_data);
     $send_data['from'] = trim($send_data['from']);
     if(!isset($send_data['from'][0])){
         $res = array('result'=>false,'to'=>$send_data['to'],'headers'=>'발송자 메일 주소가 없습니다.');
         return $res;
     }
    $send_data['to'] = trim($send_data['to']);
    if(!isset($send_data['to'][0])){
        $res = array('result'=>false,'to'=>$send_data['to'],'headers'=>'빈 주소로 메일을 발송 할 수 없습니다.');
        return $res;
    }
    if(count($send_data['binds'])>0){
      $keys = array_keys($send_data['binds']);
  		foreach($keys as & $v){
  			$v = '{{'.$v.'}}';
  		}
  		$message = str_replace($keys,array_values($send_data['binds']),$message);
    }
		$this->load->library('email');
		$this->config->load('mail'); // 이메일 설정
		$mail_conf = array_merge($this->config->item('mail'),$mail_conf);
    $mail_conf['validate'] = true;
    // $mail_conf['smtp_user'] = $smtp_user;
    // $mail_conf['smtp_pass'] = $smtp_pass;

		$this->email->initialize($mail_conf);
		$this->email->set_newline("\r\n");
    // $this->email->from(SITE_ADMIN_MAIL);
		$this->email->from($send_data['from']);
		$this->email->to($send_data['to']);
		$this->email->subject($send_data['subject']);
		$this->email->message($send_data['message']);

    $r = @$this->email->send();
    $res = array('result'=>$r,'to'=>$send_data['to'],'headers'=>$this->email->print_debugger(array('headers')));
    return $res;
	}


}
?>
