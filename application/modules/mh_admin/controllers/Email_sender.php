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
	}


  // public function _remap($method, $params = array())
	// {
	// 	$this->index($params);
	// }

	public function set_base_url($base_url){
		$this->base_url = $base_url;
		// $this->bbs_m->set_base_url($base_url);
		// $this->bf_m->set_base_url($base_url);
	}
  // front 컨트롤에서 접근할 경우.
  public function index_as_front($conf,$param){
    $this->m_row = $this->common->get_login();
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

  public function mode_enc_str($conf,$param){
    $plain_text = $this->input->post('plain_text');
    $cipher_text = '';
    $checked_enc = false;
    if(isset($plain_text[0])){
      $cipher_text = $this->enc_str($plain_text);
      $checked_enc = $this->dec_str($cipher_text) == $plain_text;
    }
    $this->load->view('mh_admin/email_sender/enc_str',
      array(
        'base_url'=>$this->base_url,
        'cipher_text'=>$cipher_text,
        'checked_enc'=>$checked_enc,
      )
    );
  }

  public function enc_str($plain_text){
		return $this->common->enc_str($plain_text);
	}
	public function dec_str($cipher_text){
		return $this->common->dec_str($cipher_text);
	}

  private function mode_form($conf,$param){
    $mail_accounts = $this->config->load('mail_accounts'); // 이메일 설정
    // print_r($mail_accounts);

    $this->load->view('mh_admin/email_sender/form',
      array(
        'base_url'=>$this->base_url,
        'mail_accounts'=>$mail_accounts,
      )
    );
  }


  private function process_send($conf,$param){

    // $posts = $this->input->post();
    // $from = $this->input->post('email_from');
    // $from = SITE_ADMIN_MAIL;
    // $from = $this->input->post('from');
    $tos = $this->input->post('tos');
    $tos = preg_split('/(\r\n|\r|\n)/',$tos);
    $subject = $this->input->post('subject');
    $message = $this->input->post('message');
    $mail_account = $this->input->post('mail_account');
    if(!isset($mail_account)){
      show_error('필수값 mail_account가 없습니다.');
    }

    $mail_accounts = $this->config->load('mail_accounts'); // 이메일 설정
    $mail_conf = $mail_accounts[$mail_account];
    if(!isset($mail_accounts[$mail_account])){
      show_error('존재하지 않는 mail_account입니다.');
    }
    $from = $mail_conf['from'];
    if(isset($mail_conf['enc_smtp_pass'])){
      $mail_conf['smtp_pass'] = $this->dec_str($mail_conf['enc_smtp_pass']);
      // print_r($mail_conf['smtp_pass']);
    }
    unset($mail_conf['process'],$mail_conf['from'],$mail_conf['tos'],$mail_conf['subject'],$mail_conf['message']);
    $mailtype = $this->input->post('mailtype');
    $mail_conf['mailtype'] = $mailtype;

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
      $res['mail_account'] = $mail_account;
      $this->mh_log->info(array(
      'title'=>__METHOD__,
      'msg'=>'메일발송',
      'result'=>$res['result']?'성공':'실패',
      'res'=>$res
      ));
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
    // print_r($send_data);exit;
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
