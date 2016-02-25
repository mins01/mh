<?
// bc_rows, su_cnt
?>

<h3 class="text-right">
	최근 한마디
</h3>


<div class="list-group last_comments center-block" style="max-width:800px">
<a  class="list-group-item active">
	한마디
</a>
<? 
foreach($bc_rows as $bc_row): 
	$avg_star = round($bc_row['avg_star']);
	$tmp_avg_star = str_repeat('★',$avg_star).str_repeat('☆',5-$avg_star);
	$avg_star2 = round($bc_row['bc_number']);
	$tmp_avg_star2 = str_repeat('★',$avg_star2).str_repeat('☆',5-$avg_star2);
	$gap_min =	(time()- strtotime($bc_row['bc_insert_date']))/60;
?>
<div class="list-group-item">
<table>
	<tr>
		<td style="width:1em;vertical-align:middle;word-break:break-all;" class="text-center">
			&nbsp;
		</td>
		<td style="width:90px;vertical-align:top">
			<div style="display:block;margin:0 0.5em; background-image:url('<?=$bc_row['unit_img']?>'); background-position: center -10px; height:80px; width:80px;" class="img-rounded cnter-block"><a href="/sdgn/units?unit_idx=<?=$bc_row['unit_idx']?>#cmt_<?=$bc_row['bc_idx']?>" style="display:block;height:100%;"></a></div>
		</td>
		<td style="vertical-align:top">
			<a href="/sdgn/units?unit_idx=<?=$bc_row['unit_idx']?>#cmt_<?=$bc_row['bc_idx']?>">
				<span class="pull-left" style="display:block;">
				<span class="unit_name unit_properties_num unit_properties_num-<?=$bc_row['unit_properties_num']?>"><?=html_escape($bc_row['unit_name'])?></span>

				<span class="avg_star avg_star-<?=$avg_star2?>">
					<?=$tmp_avg_star2?>
				</span>
				<br>
				<?=nl2br(html_escape($bc_row['bc_comment']))?>
				<small class="bc_name"><strong>by <?=html_escape($bc_row['bc_name'])?></strong></small>
				</span>
			</a>
		</td>
	</tr>
</table>

	
	
	<span class="clearfix"></span>
</a>
</div>
<? 
endforeach; 
?>
</div>
