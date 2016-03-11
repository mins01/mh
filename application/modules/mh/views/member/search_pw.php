<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="search_pw">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 찾기</h3>
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
			<label for="m_nick" class="col-sm-3 control-label">닉네임*</label>
			<div class="col-sm-9">
				<input type="text" name="m_nick" id="m_nick" maxlength="40" value="<?=set_value('m_nick')?>" class="form-control" required placeholder="닉네임">
				<?php echo form_error('m_nick'); ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group  pull-right" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 찾기</button>
			</div>
			<div class="btn-group pull-left" role="group" aria-label="">
				<button type="button" onclick="window.open('<?=base_url('member/search_id')?>','_self')" class="btn btn-info glyphicon glyphicon-link"> 아이디 찾기</button>
			</div>
			<div class="clearfix"></div>
		</div>
	</div>
	
	
</div>
</form>