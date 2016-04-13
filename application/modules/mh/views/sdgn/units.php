<?
// su_rows, su_cnt
?>

<h3 class="text-right">
	<span class="label label-success" id="su_cnt"><?=$su_cnt?></span> / <span class="label label-danger" id="su_cnt_all"><?=$su_cnt_all?></span> 유닛
</h3>
<div class="well">
	<form name="form_filter" class="text-center" action="" >
		<div class="form-inline">
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_ranks[]" <?=in_array('S',$sh['unit_ranks'])?'checked':''?> value="S">
				S랭크
				</label>
			</div>
			
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_ranks[]" <?=in_array('A',$sh['unit_ranks'])?'checked':''?> value="A">
				A랭크
				</label>
			</div>
			
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_ranks[]" <?=in_array('B',$sh['unit_ranks'])?'checked':''?> value="B">
				B랭크
				</label>
			</div>
			
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_ranks[]" <?=in_array('C',$sh['unit_ranks'])?'checked':''?> value="C">
				C랭크
				</label>
			</div>
		</div>
		<div class="form-inline">
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_properties_nums[]" <?=in_array('1',$sh['unit_properties_nums'])?'checked':''?> value="1">
					<span class="unit_properties label unit_properties_num unit_properties_num-1">어썰트</span>
				</label>
			</div>
			
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_properties_nums[]" <?=in_array('2',$sh['unit_properties_nums'])?'checked':''?> value="2">
				<span class="unit_properties label unit_properties_num unit_properties_num-2">밸런스</span>
				</label>
			</div>
			
			<div class="checkbox-inline">
				<label>
				<input type="checkbox" name="unit_properties_nums[]" <?=in_array('3',$sh['unit_properties_nums'])?'checked':''?> value="3">
				<span class="unit_properties label unit_properties_num unit_properties_num-3">슈터</span>
				</label>
			</div>

		</div>
	
		<div class="form-inline">
			<div class="input-group">
				<span class="input-group-addon" id="basic-addon1">유닛명</span>
				<input type="text" name="unit_name" class="form-control" placeholder="유닛명" aria-describedby="basic-addon1" value="<?=html_escape($sh['unit_name'])?>">
			</div>
			<div class="btn-group" role="group" aria-label="검색버튼">
				<button class="btn btn-warning">검색</button>
			</div>
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