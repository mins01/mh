<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="modify">
<input type="hidden" name="ret_url" value="<?=html_escape($ret_url)?>">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">정보수정</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-2 control-label">아이디</label>
			<div class="col-sm-10">
				<input type="text" disabled max-length="40" value="<?=html_escape($m_row['m_id'])?>" class="form-control" required placeholder="아이디">
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label for="m_nick" class="col-sm-2 control-label">닉네임*</label>
			<div class="col-sm-10">
				<input type="text" name="m_nick" id="m_nick" maxlength="40" value="<?=html_escape($m_row['m_nick'])?>" class="form-control" required placeholder="닉네임">
				<?php echo form_error('m_nick'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="m_email" class="col-sm-2 control-label">이메일*</label>
			<div class="col-sm-10">
				<input type="email" name="m_email" id="m_email" maxlength="200" value="<?=html_escape($m_row['m_email'])?>" class="form-control" required placeholder="이메일">
				<?php echo form_error('m_email'); ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
		<a href="<?=html_escape(base_url('member/user_pass'))?>" class="btn btn-info glyphicon glyphicon-cog"> 비밀번호수정</a>
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 정보수정</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>