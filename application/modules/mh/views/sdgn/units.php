<?
// su_rows, su_cnt
?>

<h3 class="text-right">
	<span class="label label-danger" id="su_cnt"><?=$su_cnt?></span> 유닛
</h3>
<div class="well">
	<form name="form_filter" class="form-inline text-center" action="" onsubmit="return false;" >
		<div class="input-group">
			<span class="input-group-addon" id="basic-addon1">유닛명</span>
			<input type="text" name="unit_name"  onkeyup="fn_filter(this.form)" class="form-control" placeholder="유닛명" aria-describedby="basic-addon1">
		</div>
	</form>
</div>
<script>
function fn_filter(f){
	var unit_name = f.unit_name.value;
	unit_name = unit_name.replace(/[\s\t]/,'');
	var filted_cnt = 0;
	if(unit_name.length>0){
		$(".unit_card_info").each(function(){
			if( ($(this).attr('data-unit_name')).indexOf(unit_name)>-1 ){
				$(this).parent().removeClass('hide');
				filted_cnt++;
			}else{
				$(this).parent().addClass('hide');
			}
		});
	}else{
		$(".unit_card_info").each(function(){
			$(this).parent().removeClass('hide');
			filted_cnt++;
		});
	}
	$('#su_cnt').html(filted_cnt)
	
}
</script>

<div class="text-center">
	<? foreach($units_cards as $units_card): ?>
	<div class="unit_card_box">
		<?=$units_card?>
	</div>
	<? endforeach; ?>
</div>