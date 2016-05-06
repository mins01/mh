<?
// sb_rows
?>


<div class="text-right well form-inline">
	<form action="" method="get">
	<select name="sb_idx" class="form-control" onchange="this.form.submit()">
		<optgroup label="럭키상자">
			<? 
			foreach($sb_rows as $sb_row): 
				if($sb_row['sb_type']!='1') continue;
				$selected = $sb_idx==$sb_row['sb_idx']?'selected="selected"':'';
			?>
				<option <?=$selected?> value="<?=$sb_row['sb_idx']?>"><?=html_escape($sb_row['sb_label'])?></option>
			<? 
			endforeach; 
			?>
		</optgroup>
		<optgroup label="기타">
			<? 
			foreach($sb_rows as $sb_row): 
				if($sb_row['sb_type']!='10') continue;
				$selected = $sb_idx==$sb_row['sb_idx']?'selected="selected"':'';
			?>
				<option <?=$selected?> value="<?=$sb_row['sb_idx']?>"><?=html_escape($sb_row['sb_label'])?></option>
			<? 
			endforeach; 
			?>
		</optgroup>
	</select>
	</form>
</div>


<div class="row">
	<div class="col-sm-4">
		<h3 class="text-center"><?=html_escape($selected_sb_row['sb_label'])?></h3>
		<h4 class="text-center"><?=html_escape($selected_sb_row['sb_desc'])?></h4>
	</div>
	<div class="col-sm-8 text-center">
		<? foreach($units_cards as $k=>$units_card): 
			$su_row = $su_rows[$k];
		?>
		<div class="unit_card_box">
			<?=$units_card?>
			<div style="height:3em;overflow-y:auto;overflow-y:hide">
				<small><?=html_escape($su_row['suib_desc'])?></small>
			</div>
			<? if($mode=='add_units'): ?>
			<div>
				sort: <?=$su_row['suib_sort']?> /
				<button class="btn btn-default" 
				type="button"
				data-suib_idx="<?=$su_row['suib_idx']?>"
				data-unit_idx="<?=$su_row['unit_idx']?>"
				data-sb_idx="<?=$su_row['sb_idx']?>"
				data-suib_desc="<?=$su_row['suib_desc']?>"
				data-suib_sort="<?=$su_row['suib_sort']?>"
				onclick="box_add_units.show_unit(this)"
				> 수정</button>
			</div>			
			<? endif; ?>
			
		</div>
		<? endforeach; ?>
		<? if(!isset($units_cards[0])): ?>
		<div class="alert alert-danger">NO-DATA</div>
		<? endif; ?>
	</div>
	
</div>

<?
if($is_admin):
?>
<hr>
<div class="text-right">
	<a class="btn btn-danger" href="?sb_idx=<?=$sb_idx?>&amp;mode=edit">EDIT-BOX</a>
	<a class="btn btn-info" href="?sb_idx=0&amp;mode=edit">NEW-BOX</a> /
	<a class="btn btn-success" href="?sb_idx=<?=$sb_idx?>&amp;mode=add_units">MANAGER-UNITS</a>
</div>
<?
	if($mode=='edit') require_once(dirname(__FILE__).'/box_edit.php');
	if($mode=='add_units') require_once(dirname(__FILE__).'/box_add_units.php');

endif; 
?>


