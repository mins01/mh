<?
// su_rows, su_cnt
?>

<h3 class="text-right">
	<?=$su_cnt?> 유닛
</h3>


<div class="row">
<? foreach($su_rows as $su_row): ?>
	<div class="col-md-3 col-sm-4 text-center unit_card">
		<div class="unit_card_img">
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
		</div>
		
	</div>
<? endforeach; ?>
</div>