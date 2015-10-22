<?
//$bm_row,$b_row
//$start_num,$count

?>
<form action="" method="post">
<input type="hidden" name="process" value="<?=html_escape($process)?>">
<div class="panel panel-default bbs-mode-delete">
	<div class="panel-heading">
		<h3 class="panel-title"><?=html_escape($b_row['b_title'])?></h3>
	</div>
	<div class="panel-body" style="min-height:200px">
		<div class="alert alert-danger text-center" role="alert">삭제하시겠습니까?</div>
		<div class="text-center form-inline">
		<label>비밀번호</label>
		<input type="text" class="form-control" name="b_pass" placeholder="비밀번호" value="">
		</div>
	</div>
	<div class="panel-footer text-center">
	<button type="submit" href="<?=html_escape($bbs_conf['list_url'])?>" class="btn btn-primary glyphicon glyphicon-ok"> 확인</button>
	<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-remove"> 취소</button>
	</div>
</div>
</form>