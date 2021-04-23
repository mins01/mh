<?
//$bm_row,$b_row
//$start_num,$count

?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="login">
<input type="hidden" name="ret_url" value="<?=html_escape($ret_url)?>">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">로그인</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="b_id" class="col-sm-2 control-label">아이디</label>
			<div class="col-sm-10">
				<input type="text" name="m_id" class="form-control" id="m_id" value="<?=set_value('m_id'); ?>" placeholder="아이디" required>
				<?php echo form_error('m_id'); ?>
			</div>
		</div>

		<div class="form-group">
			<label for="b_pass" class="col-sm-2 control-label">비밀번호</label>
			<div class="col-sm-10">
				<input type="password" name="m_pass" class="form-control" id="m_pass" placeholder="비밀번호" required>
				<?php echo form_error('m_pass'); ?>
			</div>
		</div>

		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 로그인</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
			<div class="btn-group" role="group" aria-label="">
				<a href="<?=html_escape(SITE_URI_MEMBER_PREFIX.'join')?>"  class="btn btn-success glyphicon glyphicon-ok-circle"> 회원가입</a>
			</div>
		</div>
	</div>
	<div class="panel-body" >
		<div class="alert alert-danger text-center" role="alert" style="margin-bottom:5px;" ><b>주의 : 공개된 장소에서는 사용 완료 후 꼭 로그아웃을 하시기바랍니다.</b></div>
		<ul  class="list-group">
			<li  class="list-group-item active text-center">개인정보 오남용 피해예방 10계명 <a style="color:#fff;" target="_blank" class="glyphicon glyphicon-link text-primary" href="https://www.privacy.go.kr/nns/ntc/cmd/tenCommandments.do"></a></li>
			<li  class="list-group-item">01. 개인정보처리방침 및 이용약관 꼼꼼히 살피기</li>
			<li  class="list-group-item">02. 비밀번호는 문자와 숫자로 8자리 이상</li>
			<li  class="list-group-item">03. 비밀번호는 주기적으로 변경하기</li>
			<li  class="list-group-item">04. 회원가입은 주민번호 대신 I-PIN 사용</li>
			<li  class="list-group-item">05. 명의도용확인 서비스 이용하여 가입정보 확인</li>
			<li  class="list-group-item">06. 개인정보는 친구 에게도 알려주지 않기</li>
			<li  class="list-group-item">07. P2P 공유폴더에 개인 정보 저장하지 않기</li>
			<li  class="list-group-item">08. 금융거래는 PC방 에서 이용하지 않기</li>
			<li  class="list-group-item">09. 출처가 불명확한  자료는 다운로드금지</li>
			<li  class="list-group-item">10. 개인정보 침해신고 적극 활용하기</li>
		</ul>
	</div>
</div>

</form>
