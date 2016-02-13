<?
// bc_rows , su_rows, units_cards
?>

<h3 class="text-right">
	SD건담넥스트에볼루션분석
</h3>


<div class="row">
	<div class="col-sm-6">
		<ul class="list-group">
			<li class="list-group-item active">한마디 TOP 7</li>
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
	<div class="col-sm-6">
		<ul class="list-group">
			<li class="list-group-item active">유닛 TOP 7</li>
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