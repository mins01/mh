<?
	$avg_star = round($su_row['avg_star']);
	$tmp_avg_star = str_repeat('★<br>',$avg_star).str_repeat('☆<br>',5-$avg_star);
	$url = '/sdgn/units?unit_idx='.$su_row['unit_idx'];
?>
<div class="unit_card_info"
data-unit_properties="<?=$su_row['unit_properties']?>"
data-unit_rank="<?=$su_row['unit_rank']?>"
data-unit_name="<?=preg_replace('/[\s\t]/','',$su_row['unit_name'])?>"
>
<? if($use_a): ?>
	<!--<a href="<?=html_escape($url)?>" class="unit_card_a unit_card_img">-->
	<div onclick="window.open('<?=html_escape($url)?>','_self');" class="unit_card_a unit_card_img">
<? else: ?>
	<div class="unit_card_img ">
<? endif; ?>
<div class="text-dot">&nbsp;</div>
<table border="0" cellpadding="0" cellspacing="0" class="unit_card_table_layout  unit_card_table "

>
	<tr><td>
	<table  border="0" cellpadding="0" cellspacing="0" class="unit_card_table_layout unit_card_top_info">
		<tr>
			<td width="70%" style="height:30px" class="text-left"><span class="unit_properties label unit_properties_num unit_properties_num-<?=$su_row['unit_properties_num']?>"><?=html_escape($su_row['unit_properties'])?></span>
			<? if($su_row['unit_is_weapon_change']==1): ?>
			<span class="label label-danger unit_is_weapon_change" title="웨폰 체인지">W</span>
			<? endif; ?>
			<? if($su_row['unit_is_transform']==1): ?>
			<span class="label label-info unit_is_transform" title="변신">T</span>
			<? endif; ?>
			
			</td>
			<td  class="text-right"><span class="unit_rank unit_rank-<?=html_escape($su_row['unit_rank']);?>"><?=html_escape($su_row['unit_rank']);?></span></td>
		</tr>
		<tr>
			<td width="30%" class="text-left"><div class="avg_star avg_star-<?=$avg_star?>">
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
			<td  class="text-center"><div class="unit_name"><a href="<?=html_escape($url)?>"><?=html_escape($su_row['unit_name']);?></a></div></td>
		</tr>
	</table></td></tr>
</table>
<div class="text-dot">&nbsp;</div>
<? if($use_a): ?>
	<!-- </a> -->
	</div>
<? else: ?>
	</div>
<? endif; ?>
</div>