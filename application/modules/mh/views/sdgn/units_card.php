
<table border="0" cellpadding="0" cellspacing="0" class="unit_card_table unit_card_info"
data-unit_properties="<?=$su_row['unit_properties']?>"
data-unit_rank="<?=$su_row['unit_rank']?>"
data-unit_name="<?=preg_replace('/[\s\t]/','',$su_row['unit_name'])?>"
>
	<tr><td class="text-dot">&nbsp;</td></tr>
	<tr><td>
<? if($use_a): ?>
	<a href="/sdgn/units?unit_idx=<?=$su_row['unit_idx']?>" class="unit_card_img">
<? else: ?>
	<div class="unit_card_img">
<? endif; ?>
	<img src="<?=html_escape($su_row['unit_img']);?>" class="img-rounded" alt="<?=html_escape($su_row['unit_name']);?> 이미지">
	<?
	switch($su_row['unit_properties']){
		case '어썰트':$t = 'label-danger';break;
		case '밸런스':$t = 'label-info';break;
		case '슈터':$t = 'label-success';break;
	}
	?>
	<span class="unit_properties label <?=$t?>"><?=html_escape($su_row['unit_properties']);?></span>
	<span class="unit_rank unit_rank-<?=html_escape($su_row['unit_rank']);?>"><?=html_escape($su_row['unit_rank']);?></span>
	<div class="unit_name">
		<?=html_escape($su_row['unit_name']);?>
	</div>
	<?
		$avg_star = round($su_row['avg_star']);
		$tt = str_repeat('★<br>',$avg_star).str_repeat('☆<br>',5-$avg_star);
	?>
	<div class="avg_star avg_star-<?=$avg_star?>">
		<?=$tt?>
	</div>
<? if($use_a): ?>
	</a>
<? else: ?>
	</div>
<? endif; ?>
	</td></tr>
	<tr><td class="text-dot">&nbsp;</td></tr>
</table>