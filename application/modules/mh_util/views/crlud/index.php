<?
//field_rowss
// print_r($rows);
?>
<h1><?=$from?></h1>
<form name="form_process" class="hide" action="?" method="get">

</form>
<button class="btn_submit hide">안보이는버튼</button>
<script >
var f = document.form_process;
function act_form(btn){
	$(f).html("");
	$tr = $(btn).parents('tr');
	$tr.find("input").each(function(idx,el){
		$(f).append(el.cloneNode(true))
	})
	
	$(f).append($(".btn_submit")[0].cloneNode(true))
	f.method=$tr.attr('data-method')?$tr.attr('data-method'):'get';
	$(f).find('.btn_submit').trigger("click");
	// f.submit();
}

function form_submit(evt){
	if(!document.activeElement){ return false;}
	var btn = $(document.activeElement).parents('tr').find("button");
	act_form(btn);
}
</script>
<div class="table-responsive form-group-sm ">
	
	<form action="?" method="post">
		<table class="table table-bordered table-hover table-condensed small">
			<tr>
				<th>-</th>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					} 
					?>
				<th>
					<div>
						<?=html_escape($show_field)?>
					</div>
					<div><?=html_escape($field_rowss[$show_field]['Comment'])?></div>
					<div><?=html_escape($field_rowss[$show_field]['Type'])?></div>
				</th>
				<? endforeach; ?>
				<th>-</th>
			</tr>	
			
			<tr class="danger">
				
				<td>추가<input type="hidden" name="_mode" value="process"/><input type="hidden" name="_process" value="create"/></td>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					}
					$is_auto_increment = ($field_rowss[$show_field]['Extra'] == 'auto_increment');
					$is_pk = in_array($show_field,$pks);
					// $is_autoinc = $field_rowss[$show_field][]
					?>
				<td>
					<? if(strpos($field_rowss[$show_field]['Type'],'set(')===0): //set 타입일 경우 
						$val = array();
						$t = explode(',',str_replace(array('set(',"'",')'),array('','',''),$field_rowss[$show_field]['Type']));
						foreach($t as $key):?>
						<label><input type="checkbox" value="<?=html_escape($key)?>" name="<?=html_escape($show_field)?>[]" <?=in_array($key,$val)?'checked':''?>  ><?=html_escape($key)?></label> 
						<?
						endforeach;
						?>
					<? else:?>
					<input type="text" <?=$is_auto_increment?'readonly  disabled':''?> <?= (!$is_auto_increment && $is_pk)?'required':''?>  class="form-control" name="<?=html_escape($show_field)?>"  />
					<? endif; ?>
				</td>
				
				<? endforeach; ?>
				<th><button type="submit" class="btn btn-default btn-xs">추가</button></th>					
				
			</tr>
		</table>
	</form>
	
	<form action="" method="get">
		<table class="table table-bordered table-hover table-condensed small">
			<tr>
				<th>-</th>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					} 
					?>
				<th>
					<div>
						<?=html_escape($show_field)?>
					</div>
					<div><?=html_escape($field_rowss[$show_field]['Comment'])?></div>
					<div><?=html_escape($field_rowss[$show_field]['Type'])?></div>
				</th>
				<? endforeach; ?>
				<th>-</th>
			</tr>
			
			<tr class="success">
			
				<td>검색<input type="hidden" name="_mode" disabled value=""/><input type="hidden" name="_process" disabled value=""/></td>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					}
					$is_auto_increment = ($field_rowss[$show_field]['Extra'] == 'auto_increment');
					$is_pk = in_array($show_field,$pks);
					?>
				<td><input type="text"  class="form-control" name="<?=html_escape($show_field)?>" value="<?=html_escape($get[$show_field])?>"  /></td>
				<? endforeach; ?>
				<th><button type="submit" class="btn btn-default btn-xs">검색</button></th>					
			
			</tr>
		</table>
	</form>
	

	<form action="javascript" method="post" onsubmit="form_submit(event); return false;">
		<table class="table table-bordered table-hover table-condensed small">
			<tr>
				<th>-</th>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					} 
					?>
				<th>
					<div>
						<?=html_escape($show_field)?>
					</div>
					<div><?=html_escape($field_rowss[$show_field]['Comment'])?></div>
					<div><?=html_escape($field_rowss[$show_field]['Type'])?></div>
				</th>
				<? endforeach; ?>
				<th>-</th>
			</tr>
			<? foreach($rows as $k=>$row): ?>
			<tr  data-method="post">			
				
				<td>수정<input type="hidden" name="_mode" value="process"/><input type="hidden" name="_process" value="update"/></td>
				<? foreach($show_fields as $show_field): 
					if(!isset($field_rowss[$show_field])){
						show_error($show_field.'는 지원되지 않습니다.');
					}
					$is_auto_increment = ($field_rowss[$show_field]['Extra'] == 'auto_increment');
					$is_pk = in_array($show_field,$pks);
					?>
				<td>
					<? if(strpos($field_rowss[$show_field]['Type'],'set(')===0): //set 타입일 경우 
						$val = explode(',',$row[$show_field]);
						$t = explode(',',str_replace(array('set(',"'",')'),array('','',''),$field_rowss[$show_field]['Type']));
						foreach($t as $key):?>
						<label><input type="checkbox" value="<?=html_escape($key)?>" name="<?=html_escape($show_field)?>[]" <?=in_array($key,$val)?'checked':''?>  ><?=html_escape($key)?></label> 
						<?
						endforeach;
						?>
					<? else:?>
					<input type="text" <?= ($is_pk)?'required readonly':''?> class="form-control" name="<?=html_escape($show_field)?>" value="<?=html_escape($row[$show_field])?>"  />
					<? endif; ?>
				</td>
				<? endforeach; ?>
				<th><button type="submit" class="btn btn-default btn-xs">수정</button></th>					
			
			</tr>
			<?
			endforeach;
			?>
			<? if(count($rows)==0): ?>
			<tr>
				<td>결과</td>	
				<td>없음</td>
				
			</tr>
			<? endif; ?>
			
		</table>		
	</form>
</div>