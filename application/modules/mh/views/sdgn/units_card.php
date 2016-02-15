<?
	$avg_star = round($su_row['avg_star']);
	$tmp_avg_star = str_repeat('★<br>',$avg_star).str_repeat('☆<br>',5-$avg_star);
?>

<? if($use_a): ?>
	<a href="/sdgn/units?unit_idx=<?=$su_row['unit_idx']?>" class="unit_card_a unit_card_img">
<? else: ?>
	<div class="unit_card_img">
<? endif; ?>
<div class="text-dot">&nbsp;</div>
<table border="0" cellpadding="0" cellspacing="0" class="unit_card_table_layout  unit_card_table unit_card_info"
data-unit_properties="<?=$su_row['unit_properties']?>"
data-unit_rank="<?=$su_row['unit_rank']?>"
data-unit_name="<?=preg_replace('/[\s\t]/','',$su_row['unit_name'])?>"
>
	<tr><td>
	<table  border="0" cellpadding="0" cellspacing="0" class="unit_card_table_layout unit_card_top_info">
		<tr>
			<td width="50%" style="height:30px" class="text-left"><span class="unit_properties label unit_properties_num unit_properties_num-<?=$su_row['unit_properties_num']?>"><?=html_escape($su_row['unit_properties'])?></span></td>
			<td  class="text-right"><span class="unit_rank unit_rank-<?=html_escape($su_row['unit_rank']);?>"><?=html_escape($su_row['unit_rank']);?></span></td>
		</tr>
		<tr>
			<td width="50%" class="text-left"><div class="avg_star avg_star-<?=$avg_star?>">
				<?=$tmp_avg_star?>
				</div></td>
		</tr>
	</table>
	</td></tr>
	<tr><td>

	<img src="<?=html_escape($su_row['unit_img']);?>" class="img-rounded" alt="<?=html_escape($su_row['unit_name']);?> 이미지">
	</td></tr>
	<tr><td><table  border="0" cellpadding="0" cellspacing="0" class="unit_card_table_layout unit_card_bottom_info">
		<tr>
			<td  class="text-center"><div class="unit_name"><?=html_escape($su_row['unit_name']);?></div></td>
		</tr>
	</table></td></tr>
</table>
<div class="text-dot">&nbsp;</div>
<? if($use_a): ?>
	</a>
<? else: ?>
	</div>
<? endif; ?>
