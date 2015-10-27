<?
//$m_row,$ret_url
//print_r($m_row);
?>
<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="search_pw_send_mail">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">비밀번호 대상 찾기 결과</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">아이디</label>
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
			<label for="m_id" class="col-sm-3 control-label">변경</label>
			<div class="col-sm-9">
				<button type="submit" class="btn btn-primary glyphicon glyphicon-ok-circle"> 비밀번호 변경 메일 보내기</button>
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