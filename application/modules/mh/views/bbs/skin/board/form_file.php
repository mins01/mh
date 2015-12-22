<?
//$bm_row,$b_row
//$start_num,$count
//print_r($bm_row);
?>
<div class="row">
<input type="hidden" name="bf_idx" value="" disabled>
<? 
foreach($bf_rows as $r):
//print_r($r);
?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  mode-read-file-item">
		<div class="panel panel-default center-block" style="max-width:310px">
			<div class="panel-heading text-center  text-overflow-ellipsis">
				<a title="<?=html_escape($r['bf_name'])?>" href="<?=html_escape($r['download_url'])?>"><?=html_escape($r['bf_name'])?></a>
			</div>
			<div class="panel-body text-center">
				<? if($r['is_image']): ?>
				<a  title="<?=html_escape($r['bf_name'])?>" href="<?=html_escape($r['view_url'])?>" target="_blank"><img src="<?=html_escape($r['thumbnail_url'])?>" class="img-responsive center-block" alt="<?=html_escape($r['bf_name'])?>"
				title="<?=html_escape($r['bf_name'])?>"></a>
				<? else: ?>
				<span class="text-danger">미리보기 지원되지 않는 파일</span>
				<? endif;?>
			</div>
			<? if($mode=='edit'): ?>
			<div class="panel-footer text-center">
				<label><input type="checkbox" name="delf[]" value="<?=$r['bf_idx']?>" > <span class="glyphicon glyphicon-floppy-remove"></span> 삭제</label>
				<? if($r['bf_represent']):?>  / <button type="button" disabled class="btn btn-success btn-xs">대표이미지</button>
				<? elseif($r['is_image']):?>  / <button type="button" onclick="return set_represent(this.form,<?=$r['bf_idx']?>)" class="btn btn-info btn-xs">대표이미지설정</button><? endif; ?>
			</div>
			<? endif; ?>
		</div>
	</div>
<?
endforeach;
?>	
<? if($mode=='read' && count($bf_rows)==0): ?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  mode-read-file-item">
	첨부된 파일이 없습니다.
	</div>
<? endif; ?>
<? 
	if(preg_match('/^(edit|answer|write)$/',$mode)): 
?>
<?
		for($i=0,$m=$bm_row['bm_file_limit']-count($bf_rows);$i<$m;$i++): 
?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mode-form-file-item">
		<div class="panel panel-primary center-block " style="max-width:310px">
			<div class="panel-heading text-center  text-overflow-ellipsis">
				NEW FILE
			</div>
			<div class="panel-body text-center">
				<div class="img-preview">Select File...</div>
			</div>
			<div class="panel-footer text-center">
				<span class="btn-block btn btn-primary btn-file ">
					<span class="glyphicon glyphicon-floppy-open"></span> 파일 선택...<input type="file" name="upf[]" multiple onchange="bbs_form_file_item_oncahngeUpload(event)">
				</span>
			</div>
		</div>
	</div>
<? 
		endfor;
	endif; 
?>
</div>
<script>
//첨부파일 미리보기 처리
	function bbs_form_file_item_oncahngeUpload(event){
		try{
			var ta = event.target;
			var preview = $(ta).parents('.mode-form-file-item').find('.img-preview');
			preview.html('');
			if(ta.files.length > 0){ //파일 업로드가 있을 경우만
				for(var i=0,m=ta.files.length;i<m;i++){ //다중 셀렉트 가능.
					var file = ta.files[i];
					var num = i;
					if(file.type.indexOf('image')===-1){ 
						
						if(preview.html().length>1){
							$(preview).append('<hr>');
						}
						$(preview).append('<div class="text-danger">파일'+(num+1)+'</div><div>'+file.name+'</div>');
						
						
						continue;
					}
					(function(file,num){
						var fileReader = new FileReader();
						fileReader.onload = function (event) {
							var ta = event.target;
							var img = new Image();
							img.src = ta.result;
							if(m>1){
								if(preview.html().length>1){
									$(preview).append('<hr>');
								}
								$(preview).append('<div class="text-primary">파일'+(num+1)+'</div>');
							}
							$(preview).append(img);
						};
						fileReader.readAsDataURL(file);
					})(ta.files[i],num)
				}
			}else{
				preview.html('Select File...');
			}
		}catch(e){
			//지원안되는 브라우저
		}
	}
</script>