<?
//$bm_row,$b_row
//$start_num,$count

?>
<div style="line-height:3em;font-size:60px;" class="text-center">
Admin login

</div>
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
				<input type="text" name="m_id" class="form-control" id="m_id" value="<?=set_value('m_id'); ?>" placeholder="아이디">
				<?php echo form_error('m_id'); ?>
			</div>
		</div>
		
		<div class="form-group">
			<label for="b_pass" class="col-sm-2 control-label">비밀번호</label>
			<div class="col-sm-10">
				<input type="password" name="m_pass" class="form-control" id="m_pass" placeholder="비밀번호">
				<?php echo form_error('m_pass'); ?>
			</div>
		</div>
		
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 로그인</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>