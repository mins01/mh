<?
//$bm_row,$b_row
//$start_num,$count

?>
<form class="form-horizontal" action="<?=html_escape($_SERVER['REQUEST_URI'])?>" method="post">
<input type="hidden" name="process" value="<?=html_escape($process)?>">

<div class="panel panel-danger center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">정말로 삭제하시겠습니까?</h3>
	</div>
	<div class="panel-body" >
		<h3 class="text-center"><?=html_escape($b_row['b_title'])?></h3>
	
		<div class="form-group">
			<label for="b_pass" class="col-sm-2 control-label">비밀번호</label>
			<div class="col-sm-10">
				<input type="password" readonly name="b_pass" class="form-control" id="b_pass" placeholder="비밀번호" value="<?=html_escape($b_row['b_pass'])?>">
				<div class="text-danger"><?php echo $error_msg; ?></div>
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