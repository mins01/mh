<table style="width:100%;table-layout:fixed">
	<tr>
		<td style="width:126px">
			<div class="weapon_card" >
				<img class="sw_img" src="<?=html_escape($sw_row['sw_img'])?>">
				<? if(isset($sw_row['sw_cost'][0])): ?><div class="sw_cost"><?=html_escape($sw_row['sw_cost'])?></div><? endif; ?>
				<div class="sw_name"><?=html_escape($sw_row['sw_name'])?></div>
				<? if(isset($sw_row['m_nick'][0])): ?><div class="m_nick">edit by <?=html_escape($sw_row['m_nick'])?></div><? endif; ?>
				<? if(isset($sw_row['sw_range'][0])): ?><div class="sw_range tag_label"><?=$sw_row['sw_range']<=5?'근거리':'원거리'?> [<?=html_escape($sw_row['sw_range'])?>m]</div><? endif; ?>
			</div>
		</td>
		<td valign="top">
			<? if(isset($sw_row['sw_desc'][0])):?>
				<div class="weapon_card box-sw_desc">
					<div class="sw_range tag_label">무기 설명</div>
					<div class="sw_desc"><?=nl2br(html_escape($sw_row['sw_desc']))?></div>
				</div>
			<? endif; ?>
		</td>
	</tr>
</table>

<script>
if(!window.sw_rows){
	var sw_rows = {}
}
<? 
$t = $sw_row;
unset($t['card'],$t['m_idx'],$t['swa_isdel']);
?>
sw_rows['<?=$t['sw_key']?>'] = <?=json_encode($t)?>;

</script>