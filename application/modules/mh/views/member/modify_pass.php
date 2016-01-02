<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="password">
<input type="hidden" name="ret_url" value="<?=html_escape($ret_url)?>">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 수정</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">현재 비밀번호*</label>
			<div class="col-sm-9">
				<input type="password" name="m_pass"  max-length="40" value="" class="form-control" required placeholder="비밀번호">
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label for="m_nick" class="col-sm-3 control-label">새 비밀번호*</label>
			<div class="col-sm-9">
				<input type="password" name="m_pass_new" maxlength="40" value="" class="form-control" required placeholder="새 비밀번호">
				<?php echo form_error('m_nick'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="m_email" class="col-sm-3 control-label">새 비밀번호 확인*</label>
			<div class="col-sm-9">
				<input type="password" name="m_pass_new_re" id="m_email" maxlength="200" value="" class="form-control" required placeholder="새 비밀번호">
				<?php echo form_error('m_email'); ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 비밀번호 수정</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>