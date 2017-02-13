<?
// print_r($rows);
if(isset($rows[0])){
	$fields = array_keys($rows[0]);
}else{
	$fields = array('##');
}
?>
<div class="text-danger text-right">
	<a href="<?=html_escape($base_url)?>" class="btn btn-primary btn-xs">테이블 목록으로</a> / 최대 100개까지 보입니다.
</div>
<form action="?" method="get">
	<input type="hidden" name="tbl_name" value="<?=html_escape($tbl_name)?>" />
	<input type="hidden" name="mode" value="lists" />
	
	
	<div class="table-responsive form-group-sm ">
		<table class="table table-bordered table-hover table-condensed small">
			<tr>
				<th>-</th>
				<th><?=implode('</th><th>',$fields); ?></th>
			</tr>
			<tr >
				<th><button type="submit" class="btn btn-info btn-xs">검색</button></th>
				<? foreach($fields as $field): ?>
				<th><input class="form-control" type="text" name="_!SH_<?=html_escape($field)?>" value="<?=isset($_GET['_!SH_'.$field])?$_GET['_!SH_'.$field]:''?>" /></th>
				<? endforeach; ?>
			</tr>
			<?
			foreach($rows as $row): 
				$row = array_map('html_escape', $row); 
				$pks = array();
				foreach($cnf['pks'] as $t){
					$pks[] = $row[$t];
				}
				$qstrs = array('pks'=>$pks,'tbl_name'=>$tbl_name,'mode'=>'form');
				$qstr = '?'.http_build_query($qstrs);
			?>
			<tr class="">
				<td><a href="<?=html_escape($base_url.$qstr)?>" class="btn btn-primary btn-xs">수정</a></td>
				<td><?=implode('</td><td>',$row); ?></td>
			</tr>
			<?
			endforeach;
			?>	
		</table>
	</div>
</form>