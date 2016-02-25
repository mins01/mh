<?
// bc_rows , su_rows, units_cards
?>


<h3 class="text-center" style="font-size:30px" >
	<label class="label label-danger" >SDGN</label> vs <label class="label label-info" >SDGO</label>
</h3>

<table class="table table-striped table-hover table-vertical-middle">
	<tr>
		<th  class="text-right" width="45%"><label class="label label-danger" >SDGN</label></th>
		<th  class="text-center" width="10%">vs</th>
		<th  class="text-left" width="45%"><label class="label label-info" >SDGO</label></th>
	</tr>
	<?
	$t1 = ceil((time()-strtotime('2015-02-25'))/(60*60*24));
	$t2 = ceil((strtotime('2015-05-29')-strtotime('2007-02-27'))/(60*60*24));
	?>
	<tr>
		<td class="text-right" >2015-02-25 ~ 현재<br>(<?=$t1?> days)<br>(<a href="http://www.sdgn.co.kr/Notice/Detail?bSn=1&amp;page=13" target="_blank">CBT 홈페이지 오픈일 기준</a>)</td>
		<td class="text-center"><strong>운영일</strong></td>
		<td class="text-left" >2007-02-27 ~ 2015-05-29<br>(<?=$t2?> days)</td>
	</tr>
	<tr>
		<td class="text-right" >트리니티</td>
		<td class="text-center"><strong>제작</strong></td>
		<td class="text-left" >소프트맥스</td>
	</tr>		
	<?
	$class1_def = 'class="label label-primary" style="font-size:1.4em"';
	$class2_def = 'class="label label-default"';
	?>
	<?
	$t1 = $sdgn_count_units;
	$t2 = $sdgo_count_units;
	$per = round($t1/$t2*100,2);
	$label = "유닛수<br>({$per}%)";
	
	$class1 = $t1>$t2?$class1_def:$class2_def;
	$class2 = $t1<$t2?$class1_def:$class2_def;
	?>
	<tr>
		<td class="text-right"><label <?=$class1?>><?=$t1?></label></td>
		<td class="text-center"><strong><?=($label)?></strong></td>
		<td class="text-left"><label <?=$class2?>><?=$t2?></label></td>
	</tr>
	<?
	$t1 = $sdgn_count_skills;
	$t2 = $sdgo_count_skills;
	$per = round($t1/$t2*100,2);
	$label = "스킬종류<br>({$per}%)";
	$class1 = $t1>$t2?$class1_def:$class2_def;
	$class2 = $t1<$t2?$class1_def:$class2_def;
	?>
	<tr>
		<td class="text-right" ><label <?=$class1?>><?=$t1?></label></td>
		<td class="text-center"><strong><?=($label)?></strong></td>
		<td class="text-left" ><label <?=$class2?>><?=$t2?></label></td>
	</tr>
	<?
	$t1 = 4;
	$t2 = 34;
	$per = round($t1/$t2*100,2);
	$label = "맵<br>({$per}%)";
	$class1 = $t1>$t2?$class1_def:$class2_def;
	$class2 = $t1<$t2?$class1_def:$class2_def;
	?>
	<tr>
		<td class="text-right" ><label <?=$class1?>><?=$t1?></label></td>
		<td class="text-center"><strong><?=($label)?></strong></td>
		<td class="text-left" ><label <?=$class2?>><?=$t2?></label></td>
	</tr>
	<? /* ?>
	<?
	$t1 = $sdgn_count_comments;
	$t2 = $sdgo_count_comments;
	$per = round($t1/$t2*100,2);
	$label = "한마디<br>({$per}%)";
	$class1 = $t1>$t2?$class1_def:$class2_def;
	$class2 = $t1<$t2?$class1_def:$class2_def;
	?>
	<tr>
		<td class="text-right" ><label <?=$class1?>><?=$t1?></label></td>
		<td class="text-center"><strong><?=($label)?></strong></td>
		<td class="text-left" ><label <?=$class2?>><?=$t2?></label></td>
	</tr>
	<?
	$t1 = $sdgn_count_comment_users;
	$t2 = $sdgo_count_comment_users;
	$per = round($t1/$t2*100,2);
	$label = "한마디<br>참여자<br>({$per}%)";
	$class1 = $t1>$t2?$class1_def:$class2_def;
	$class2 = $t1<$t2?$class1_def:$class2_def;
	?>
	<tr>
		<td class="text-right" ><label <?=$class1?>><?=$t1?></label></td>
		<td class="text-center"><strong><?=($label)?></strong></td>
		<td class="text-left" ><label <?=$class2?>><?=$t2?></label></td>
	</tr>
	<? */ ?>
	
</table>