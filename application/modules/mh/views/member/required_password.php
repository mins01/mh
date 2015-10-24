<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="">
<input type="hidden" name="ret_url" value="?">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 확인</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="b_pass" class="col-sm-2 control-label">비밀번호</label>
			<div class="col-sm-10">
				<input type="password" name="m_pass" class="form-control" id="m_pass" placeholder="비밀번호">
				<?php echo form_error('m_pass'),$error_msg; ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button  class="btn btn-primary glyphicon glyphicon-ok-circle"> 확인</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 취소</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>