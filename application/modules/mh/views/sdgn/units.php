<?
// su_rows, su_cnt
?>

<h3 class="text-right">
	<?=$su_cnt?> 유닛
</h3>


<div class="row">
<? foreach($units_cards as $units_card): ?>
	<div class="col-md-3 col-sm-3 col-xs-6 text-center unit_card">
		<?=$units_card?>
		
	</div>
<? endforeach; ?>
</div>