<?
//$bm_row,$b_row
//$start_num,$count

?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="join">
<input type="hidden" name="ret_url" value="<?=html_escape($ret_url)?>">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">회원가입</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-2 control-label">이메일*</label>
			<div class="col-sm-10">
				<input type="email" name="m_id" id="m_id" max-length="40" value="<?=set_value('m_id')?>" class="form-control" required placeholder="이메일">
				<?php echo form_error('m_id'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="m_pass" class="col-sm-2 control-label">비밀번호*</label>
			<div class="col-sm-5">
				<input type="password" name="m_pass" id="m_pass" maxlength="40" value="<?=set_value('m_pass')?>" class="form-control" required placeholder="비밀번호">
				<?php echo form_error('m_pass'); ?>
			</div>
			<div class="col-sm-5">
				<input type="password" name="m_pass_re" id="m_pass_re" maxlength="40" value="<?=set_value('m_pass_re')?>" class="form-control" required placeholder="비밀번호확인">
				<?php echo form_error('m_pass_re'); ?>
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label for="m_nick" class="col-sm-2 control-label">닉네임*</label>
			<div class="col-sm-10">
				<input type="text" name="m_nick" id="m_nick" maxlength="40" value="<?=set_value('m_nick')?>" class="form-control" required placeholder="닉네임">
				<?php echo form_error('m_nick'); ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 회원가입</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>