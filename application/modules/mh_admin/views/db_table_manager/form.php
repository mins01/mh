<?
// print_r($rows);
if(isset($rows[0])){
	$fields = array_keys($rows[0]);
}else{
	$fields = array('##');
}
$row = $rows[0];
?>
<form action="?" method="post">
	<input type="hidden" name="tbl_name" value="<?=html_escape($tbl_name)?>" />
	<input type="hidden" name="mode" value="process" />
	<input type="hidden" name="process" value="update" />

	<div class="text-danger text-right">
		최대 100개까지 보입니다.
	</div>
	<div class="table-responsive">
		<table class="table table-bordered table-hover table-condensed">
			<tr>
				<th width="120">필드</th>
				<th>값</th>
				<th width="200">주석</th>
			</tr>
			<?
			
			foreach($columns as $col):
				$k = $col['Field']; 
				$v = $row[$k]; 
				$c = $col['Comment']; 
				$is_pk = $col['Key']=='PRI';
				if(!$is_pk){
					$is_pk = in_array($k,$cnf['pks']);
				}
				// $readonly = $is_pk?'readonly':'';
				$readonly = '';
				$pklabel = $is_pk?' (PK)':'';
				$is_update = strpos($col['Privileges'],'update')!==false;
				$disabled = $is_update?'':'disabled';
			?>
			<tr>
				<td><?=html_escape($k)?><?=$pklabel?>
					<? if($is_pk): ?>
					<input  class="form-control" <?=$readonly?> style="" type="hidden"  name="_!@#$_<?=html_escape($k)?>" readonly value="<?=html_escape($v)?>" >
					<? endif; ?>
				</td>
				<td>
					<?
					if(stripos($col['Type'],'text')!==false):
					?><textarea class="form-control" <?=$readonly?> <?=$disabled?>  style=" height:5em;" name="<?=html_escape($k)?>"><?=html_escape($v)?></textarea>
					<? 
					else: 
					?><input  class="form-control" <?=$readonly?> <?=$disabled?> style="" type="text"  name="<?=html_escape($k)?>" value="<?=html_escape($v)?>" >
					<?
					endif;
					?>
				</td>
				<td><?=html_escape($c)?></td>
			</tr>
			<?
			endforeach;
			?>
			<tr>
				<th colspan="3">
					<a class="btn btn-info" href="<?=html_escape($base_url.'?mode=lists&tbl_name='.$tbl_name)?>">뒤로</a>
					<button type="submit" class="btn btn-primary">수정</button>
				</th>
			</tr>	
		</table>
	</div>
</form>