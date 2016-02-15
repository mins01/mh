<?
// bc_rows , su_rows, units_cards
?>

<h1 class="text-center" ><a href="http://sdgn.co.kr/" target="_blank"><img src="http://static.sdgn.co.kr/next/images/common/logo.png"></a></h1>
<h3 class="text-center" >
	SD건담넥스트에볼루션분석
</h3>


<div class="row">
	<div class="col-sm-4">
		<ul class="list-group">
			<li class="list-group-item active text-center"><strong>한마디 TOP 10</strong></li>
			<? 
			$i_cnt = 1;
			foreach($bc_rows as $bc_row): 
			?>
			<li class="list-group-item">
				<strong><?=$i_cnt++?></strong>. <?=html_escape($bc_row['bc_name'])?>
				<span class="badge"><?=$bc_row['cnt']?> 마디</span>
			</li>
			<? endforeach; ?>
			
		</ul>
	</div>
	<div class="col-sm-4">
		<div class="list-group">
			<div class="list-group-item active text-center"><strong>최근 한마디</strong></div>
			<? 
			$i_cnt = 1;
			foreach($last_bc_rows as $last_bc_row): 
			?>
		<a href="/sdgn/units?unit_idx=<?=$last_bc_row['unit_idx']?>#cmt_<?=$last_bc_row['bc_idx']?>"  class="list-group-item text-overflow-ellipsis">
				<span class="label  unit_properties_num unit_properties_num-<?=$last_bc_row['unit_properties_num']?>"><?=$last_bc_row['unit_name']?></span>
				<?=html_escape($last_bc_row['bc_comment'])?>
				
			</a>
			<? endforeach; ?>
			
		</div>
	</div>
	<div class="col-sm-4">
		<ul class="list-group">
			<li class="list-group-item active text-center"><strong>유닛 TOP 10</strong></li>
			<? 
			$i_cnt = 1;
			foreach($su_rows as $su_row): 
			?>
			<li class="list-group-item">
				<strong><?=$i_cnt++?></strong>. <a href="/sdgn/units?unit_idx=<?=$su_row['unit_idx']?>"><?=html_escape($su_row['unit_name'])?></a>
				<span class="badge"><?=$su_row['cnt']?> 마디</span>
			</li>
			<? endforeach; ?>
			
		</ul>
	</div>
	<div class="text-center">
		<? foreach($units_cards as $units_card): ?>
		<div class="unit_card_box">
			<?=$units_card?>
		</div>
		<? endforeach; ?>
	</div>
</div>