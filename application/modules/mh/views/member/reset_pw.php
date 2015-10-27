<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="reset_pw">
<input type="hidden" name="m_key" value="<?=html_escape($m_key)?>">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 재설정</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">아이디*</label>
			<div class="col-sm-9">
				<input type="text" name="m_id" max-length="40" value="<?=set_value('m_id')?>" class="form-control" required placeholder="아이디 부분">
				<?php echo form_error('m_id'); ?>
			</div>
		</div>
		<hr>
		<div class="form-group">
			<label for="m_nick" class="col-sm-3 control-label">비밀번호*</label>
			<div class="col-sm-9">
				<input type="text" name="m_pass" id="m_pass" maxlength="40" value="<?=set_value('m_nick')?>" class="form-control" required placeholder="비밀번호">
				<?php echo form_error('m_pass'); ?>
			</div>
		</div>
		<div class="form-group">
			<label for="m_nick" class="col-sm-3 control-label">비밀번호 확인*</label>
			<div class="col-sm-9">
				<input type="text" name="m_pass_re" id="m_pass_re" maxlength="40" value="<?=set_value('m_nick')?>" class="form-control" required placeholder="비밀번호 확인">
				<?php echo form_error('m_pass_re'); ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group  pull-right" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 재설정</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	
	
</div>
</form>