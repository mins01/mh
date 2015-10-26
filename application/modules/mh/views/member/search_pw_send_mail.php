<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 변경 안내 메일 발송.</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">메일</label>
			<div class="col-sm-9">
			<? if(isset($m_id)): 
			list($id,$emd) = explode('@',$m_id);
			$l = strlen($id);
			$l2 = round($l/2);
			$id1 = substr($id,0,$l2);
			$id2 = str_repeat('*',strlen(substr($id,$l2)));
			$t = $id1.$id2.'@'.$emd;
			//$t = preg_replace('/(.)(.)/','*',$m_id);
			?>
				<p class="form-control-static"><?=html_escape($t)?></p>
			<? else: ?>
				<p class="form-control-static text-danger">검색된 아이디가 없습니다.</p>
			<? endif; ?>
			</div>
		</div>
		<? if(isset($m_id)):  ?>
		<input type="hidden" name="m_id" value="<?=html_escape($m_id)?>">
		<input type="hidden" name="m_nick" value="<?=html_escape($m_nick)?>">
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">동작</label>
			<div class="col-sm-9">
				<p class="form-control-static">비밀번호 변경 안내 메일 발송.</p>
			</div>
		</div>
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">발송결과</label>
			<div class="col-sm-9">
				<p class="form-control-static">발송 <?=$result?'성공':'실패'?></p>
			</div>
		</div>
		<? endif; ?>
		<hr>
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">

				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 뒤로</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>