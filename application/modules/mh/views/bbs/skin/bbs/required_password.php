<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="<?=html_escape($_SERVER['REQUEST_URI'])?>" method="post">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title"><?=html_escape($title)?></h3>
		
	</div>
	<div class="panel-body" >
		<? if(isset($sub_title[0])): ?>
		<h3 class="panel-title text-center bbs-title"><?=html_escape($sub_title)?></h3>
		<hr>
		<? endif; ?>
		<div class="form-group">
			<label for="b_pass" class="col-sm-2 control-label">비밀번호</label>
			<div class="col-sm-10">
				<input type="password" name="b_pass" class="form-control" id="b_pass" placeholder="비밀번호">
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