<?
//$m_row,$ret_url
//print_r($m_row);
?>

<form class="form-horizontal" action="?" method="post">
<input type="hidden" name="process" value="search_id">
<div class="panel panel-primary center-block" style="min-width:200px;max-width:600px;" >
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title">아이디 찾기 결과</h3>
	</div>
	<div class="panel-body" >
		<div class="form-group">
			<label for="m_id" class="col-sm-3 control-label">아이디</label>
			<div class="col-sm-9">
			<? if(isset($m_id)): 
			//list($id,$emd) = explode('@',$m_id);
			$l = strlen($m_id);
			//$l= 4;
			$st = round($l/2)-round($l/4);
			$ed = $l-$st;
			
			$part0 = substr($m_id,0,$st);
			//$part1 = substr($m_id,$st,$ed);
			$part1 = str_repeat('*',$ed-$st);
			$part2 = substr($m_id,$ed);
			$t = $part0.$part1.$part2;
			//$t = preg_replace('/(.)(.)/','*',$m_id);
			?>
				<p class="form-control-static"><?=html_escape($t)?></p>
			<? else: ?>
				<p class="form-control-static text-danger">검색된 아이디가 없습니다.</p>
			<? endif; ?>
			</div>
		</div>
		<hr>
		<div class="text-right">
			<div class="btn-group" role="group" aria-label="">
				<button type="button" onclick="window.open('<?=base_url().'search_pw'?>','_self')" class="btn btn-info glyphicon glyphicon-link"> 비밀번호 찾기</button>
				<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-ban-circle"> 뒤로</button>
			</div>
		</div>
	</div>
	
	
</div>
</form>