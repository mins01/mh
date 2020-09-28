<?
/*
'oauth2_auth_url'=> $this->oauth2_auth_url,
'oauth2_token_url'=> $this->oauth2_token_url,
'client_id'=> $this->client_id,
'client_secret'=> $this->client_secret,
'scope'=> $this->scope,
'access_type'=> $this->access_type,
'redirect_uri'=> $this->redirect_uri,
 */
// echo $client_id;
// $auth_url = "{$oauth2_auth_url}?client_id={$client_id}&response_type=code&scope={$scope}&access_type=offline&redirect_uri=urn:ietf:wg:oauth:2.0:oob";

?>
<div class="container">
	<div>
		<a class="btn btn-info" href="<?=ADMIN_URI_PREFIX?>google_api_tool/analytics_acounts">analytics acounts 확인</a>
		<a class="btn btn-info" href="<?=ADMIN_URI_PREFIX?>google_api_tool/analytics_test">analytics test</a>
		<a class="btn btn-info" href="<?=ADMIN_URI_PREFIX?>google_api_tool/analytics_test_v4">analytics test V4</a>
	</div>
	<hr>
	<h2>코드 받기 부분</h2>
	<form action="<?=html_escape($client['auth_uri'])?>" target="_blank" class="form-inline">
		scope : <input type="text" class="form-control" name="scope" value="" required> <a href="https://developers.google.com/identity/protocols/oauth2/scopes" target="_blank">OAuth 2.0 Scopes for Google APIs</a><br>
		client_id : <input type="text" class="form-control" name="client_id" value="<?=html_escape($client['client_id'])?>"><br>
		response_type : <input type="text" class="form-control" name="response_type" value="code"><br>
		access_type : <input type="text" class="form-control" name="access_type" value="offline"><br>
		redirect_uri : <input type="text" class="form-control" name="redirect_uri" value="<?=html_escape($client['redirect_uris'][0])?>"><br>
		<button class="btn btn-primary" type="submit">확인</button>
	</form>
	<hr>
	<h2>토큰 받기 부분</h2>
	<form action="?" target="_blank" class="form-inline" method="post">
		<!-- <?=html_escape($client['token_uri'])?> -->
		<input type="hidden" class="form-control" name="process" value="authorization_code" required><br>
		code : <input type="text" class="form-control" name="code" value="" required><br>
		client_id : <input type="text" class="form-control" name="client_id" value="<?=html_escape($client['client_id'])?>" disabled> from Config<br>
		client_secret : <input type="text" class="form-control" name="client_secret" value="******" disabled> from Config<br>
		grant_type : <input type="text" class="form-control" name="grant_type" value="authorization_code"><br>
		redirect_uri : <input type="text" class="form-control" name="redirect_uri" value="<?=html_escape($client['redirect_uris'][0])?>"><br>
		<button class="btn btn-primary" type="submit">확인</button>
	</form>
	<hr>
	<h2>현재 설정된 엑세스 토큰</h2>
	<div style="overflow:auto;">
		<div style="white-space:pre"><? print_r($access_token); ?></div>
	</div>
	<hr>
	<h2>현재 설정된 엑세스 토큰 갱신</h2>
	<form action="?" target="_blank" class="form-inline" method="post">
		<!-- <?=html_escape($client['token_uri'])?> -->
		<input type="hidden" class="form-control" name="process" value="refresh_token" required><br>
		client_id : <input type="text" class="form-control" name="client_id" value="<?=html_escape($client['client_id'])?>" disabled> from Config<br>
		client_secret : <input type="text" class="form-control" name="client_secret" value="******" disabled> from Config<br>
		refresh_token : <input type="text" class="form-control" name="refresh_token" value="<?=html_escape($access_token['refresh_token'])?>"><br>
		<button class="btn btn-primary" type="submit">확인</button>
	</form>
	<hr>
	<h2>현재 설정된 엑세스 토큰 자동 갱신 테스트</h2>
	<form action="?" target="_blank" class="form-inline" method="post">
		<!-- <?=html_escape($client['token_uri'])?> -->
		<input type="hidden" class="form-control" name="process" value="refreshed_access_token" required><br>
		<button class="btn btn-primary" type="submit">확인</button>
	</form>
</div>
