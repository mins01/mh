<?
// sb_rows
$suib_sort = 0;
foreach($su_rows as $su_row){
	$suib_sort = max($suib_sort,$su_row['suib_sort']);	
}
$suib_sort+=1;
?>
<hr>
<h3>목록</h3>
<div class="form-horizontal">
	<form name="form_add_units" action="" method="post" onsubmit="box_add_units.save_unit_in_box(this);return false">
		<input type="hidden" name="mode" value="process">
		<input type="hidden" name="process" value="add_units">
		<div class="form-group">
			<label class="col-sm-2 control-label">suib_idx</label>
			<div class="col-sm-10">
				<input class="form-control" readonly name="suib_idx" type="text"  value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_idx</label>
			<div class="col-sm-10">
				<input class="form-control" name="sb_idx" type="text" readonly value="<?=$sb_idx?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">unit_idx</label>
			<div class="col-sm-10">
				<select name="unit_idx" class="form-control unit_idx">
				<option value="">NONE</option>
				<? foreach($all_su_rows as $su_row):
					$w = strtolower(str_replace(' ','',$su_row['unit_name']));
				?>
					<option  data-word="<?=$w?>" value="<?=$su_row['unit_idx']?>">(<?=$su_row['unit_idx']?>) [<?=$su_row['unit_rank']?>] <?=$su_row['unit_name']?></option>
				<? endforeach; ?>
				</select>
				<input class="form-control" name="" type="text" value="" onkeyup="box_add_units.search_unit_idx(this)" autofocus>
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">suib_desc</label>
			<div class="col-sm-10">
				<input class="form-control" name="suib_desc" type="text"  value="">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">suib_sort</label>
			<div class="col-sm-10">
				<input class="form-control" name="suib_sort" type="text"  value="<?=$suib_sort?>">
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-2 control-label">확인</label>
			<div class="col-sm-10">
				<button class="btn btn-default btn-danger" type="reset"  >초기화</button>
				<button class="btn btn-default btn-primary" type="submit">확인</button>
				<button class="btn btn-default btn-danger" type="button" onclick="box_add_units.delete_unit_in_box(this.form);return false">삭제</button>
			</div>
		</div>
	</form>
</div>


<script>
var box_add_units = {
	search_unit_idx:function(el){
		var f = el.form;
		var val = el.value.replace(/\s/,'').toLowerCase();
		if(val.length==0){
			$(f).find('select.unit_idx option').show();
		}else{
			if($(f).find('select.unit_idx option[data-word*="'+val+'"]').length>0){
				var $t = $(f).find('select.unit_idx option[data-word*="'+val+'"]');
				$t.show();
				f.unit_idx.value= $t[0].value;
				
				$(f).find('select.unit_idx option:not([data-word*="'+val+'"])').hide();
			}else{
				$(f).find('select.unit_idx option').show();
			}
			
			
		}
	},
	show_unit:function(el){
		var f = document.form_add_units;
		var $el = $(el)
		f.suib_idx.value = $el.attr('data-suib_idx');
		f.unit_idx.value = $el.attr('data-unit_idx');
		//f.sb_idx.value = $el.attr('data-sb_idx');
		f.suib_desc.value = $el.attr('data-suib_desc');
		f.suib_sort.value = $el.attr('data-suib_sort');

	},
	clear_unit:function(el){
		var f = document.form_add_units;
		var $el = $(el)
		f.suib_idx.value = '';
		f.unit_idx.value = '';
		//f.sb_idx.value = $el.attr('data-sb_idx');
		f.suib_desc.value = '';
		f.suib_sort.value = '';

	},
	save_unit_in_box:function(f){
		var post_date = $(f).serialize();
		var url = '/sdgn/json/save_unit_in_box';
		$.post(url,post_date,function(res){
			if(res.msg){
				alert(res.msg);
			}
			
			if(res.is_error){
				
			}else{
				setTimeout(function(){ 
					document.location.reload(true);
				},0);
			}
			
		},'json').fail(function(){
			alert('ERROR : 통신에러');
		}
		)
	},
	delete_unit_in_box:function(f){
		if(!confirm('삭제할까요?')){
			return false;
		}
		var post_date = $(f).serialize();
		var url = '/sdgn/json/delete_unit_in_box';
		$.post(url,post_date,function(res){
			if(res.msg){
				alert(res.msg);
			}
			
			if(res.is_error){
				
			}else{
				setTimeout(function(){ 
					document.location.reload(true);
				},0);
			}
			
		},'json').fail(function(){
			alert('ERROR : 통신에러');
		}
		)
	},
}
</script>
