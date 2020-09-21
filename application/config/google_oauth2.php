<?

//-- OAuth 2.0 클라이언트 ID 에서 다운로드 받은 파일
$tjson = file_get_contents(APPPATH.'/../../conf/googleoauth2/client_secret_375808261858-ckr7kfsllh69v1j43tc7v7a8212lsft3.apps.googleusercontent.com.json');
$default_client = JSON_DECODE($tjson,1);
unset($tjson);

//-- 엑세스 토큰 JSON
// $analytics_readonly = array(); //초기 empty 설정
$tjson = file_get_contents(APPPATH.'/../../conf/googleoauth2/access_token_analytics.readonly.json');
$analytics_readonly = JSON_DECODE($tjson,1); unset($tjson);

$config['google_oauth2'] = array(
	'clients' => array(
		'default'=>current($default_client),
	),
	'access_tokens' => array(
		'analytics.readonly'=>$analytics_readonly,
	)

);

// var_export($config['google_oauth2'] );
