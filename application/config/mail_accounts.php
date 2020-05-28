<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//서버 환경에 맞춰서 수정.
// 화이트 도메인 설정이 필요함.
$config['mail_accounts']= array(
  //https://support.google.com/mail/answer/7126229?hl=ko
  'gmail' => array(
    'from'=>'***@gmail.com',
    'protocol'=> 'smtp',//smtp,mail,sendmail
    'smtp_host'=> 'smtp.gmail.com',
    'smtp_port'=> '465', //465, 587
    'smtp_user'=> 'xxxx@gmail.com',

    // /mh/_admin/email/enc_str
    'enc_smtp_pass'=> 'xxxx', // php index_cli.php mh_admin/email_sender/cli_enc_str password
    // 'smtp_pass'=> 'xxxx',

    'smtp_timeout'=> 5,
    //'smtp_crypto'=>'tls', //tls,ssl
    'smtp_crypto'=>'ssl',
    // 'charset'=>'utf-8',
    'mailtype'=>'html'
  ),
  // https://help.naver.com/support/contents/contents.help?serviceNo=2342&categoryNo=2288
  'naver' => array(
    'from'=>'***@naver.com',
    'protocol'=> 'smtp',//smtp,mail,sendmail
    'smtp_host'=> 'smtp.naver.com',
    'smtp_port'=> '465', //465, 587
    'smtp_user'=> 'xxxx',

    // /mh/_admin/email/enc_str
    'enc_smtp_pass'=> 'xxxx', // php index_cli.php mh_admin/email_sender/cli_enc_str password
    // 'smtp_pass'=> 'xxxx',


    'smtp_timeout'=> 5,
    //'smtp_crypto'=>'tls', //tls,ssl
    'smtp_crypto'=>'ssl',
    // 'charset'=>'utf-8',
    'mailtype'=>'html'
  ),
);
//
//
require_once(dirname(__FILE__).'/../../../conf/mail_accounts.php'); //이부분은 삭제해서 사용.
