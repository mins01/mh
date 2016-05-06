<?
// sb_rows
$sb_types = array(
//'0'=>'NONE',
'1'=>'럭키상자',
'2'=>'퀘스트',
'3'=>'한정이벤트',
'10'=>'기타',
)
?>
<hr>
<h3>수정</h3>
<div class="form-horizontal">
	<form action="" method="post" onsubmit="box_edit.save_box(this);return false">
		<input type="hidden" name="mode" value="process">
		<input type="hidden" name="process" value="edit">
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_idx</label>
			<div class="col-sm-10">
				<input class="form-control" readonly name="sb_idx" type="number" value="<?=html_escape($selected_sb_row['sb_idx'])?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_type</label>
			<div class="col-sm-10">
				<?=form_dropdown('sb_type', $sb_types, $selected_sb_row['sb_type'], 'class="form-control" ')?>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_sort</label>
			<div class="col-sm-10">
				<input class="form-control" name="sb_sort" type="number"  value="<?=html_escape($selected_sb_row['sb_sort'])?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_label</label>
			<div class="col-sm-10">
				<input class="form-control" name="sb_label" type="text"  value="<?=html_escape($selected_sb_row['sb_label'])?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">sb_desc</label>
			<div class="col-sm-10">
				<input class="form-control" name="sb_desc" type="text"  value="<?=html_escape($selected_sb_row['sb_desc'])?>">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label">확인</label>
			<div class="col-sm-10">
				<button class="btn btn-default btn-primary" type="submit">확인</button>
				<button class="btn btn-default btn-danger" type="button" onclick="box_edit.delete_box(this.form);return false">삭제</button>
			</div>
		</div>
	</form>
</div>


<script>
var box_edit = {
	save_box:function(f){
		var post_date = $(f).serialize();
		var url = '/sdgn/json/save_box';
		$.post(url,post_date,function(res){
			if(res.msg){
				alert(res.msg);
			}
			
			if(res.is_error){
				
			}else{
				setTimeout(function(){ 
				if(f.sb_idx.value ==res.sb_idx ){
					document.location.reload(true)
				}else{
					var url = document.location.href.replace(/sb_idx=\d*/,'sb_idx='+res.sb_idx);
					window.open(url,'_self');
				}
				
				},0);
			}
			
		},'json').fail(function(){
			alert('ERROR : 통신에러');
		}
		)
	},
	delete_box:function(f){
		if(!confirm('삭제할까요?')){
			return false;
		}
		var post_date = $(f).serialize();
		var url = '/sdgn/json/delete_box';
		$.post(url,post_date,function(res){
			if(res.msg){
				alert(res.msg);
			}
			
			if(res.is_error){
				
			}else{
				setTimeout(function(){ 
				if(f.sb_idx.value ==res.sb_idx ){
					document.location.reload(true)
				}else{
					window.open('?','_self');
				}
				
				},0);
			}
			
		},'json').fail(function(){
			alert('ERROR : 통신에러');
		}
		)
	},
}
</script>
