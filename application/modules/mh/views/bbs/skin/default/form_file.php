<?
//$bm_row,$b_row
//$start_num,$count
//print_r($bm_row);
?>

<div class="row bbs-files">
<input type="hidden" name="bf_idx" value="" disabled>
<?
foreach($bf_rows as $r):
//print_r($r);
?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4  mode-read-file-item">
		<div class="panel panel-default center-block file-item" style="max-width:310px">
			<div class="panel-heading text-center  text-overflow-ellipsis">
				<a title="<?=html_escape($r['bf_name'])?>" href="<?=html_escape($r['download_url'])?>"><?=html_escape($r['bf_name'])?></a>
			</div>
			<div class="panel-body text-center">
				<? if($r['is_image']): //외부이미지 ?>
				<a  title="<?=html_escape($r['bf_name'])?>" href="<?=html_escape($r['view_url'])?>" target="_blank"><img src="<?=html_escape($r['thumbnail_url'])?>" class="img-responsive center-block" alt="<?=html_escape($r['bf_name'])?>"
				title="<?=html_escape($r['bf_name'])?>"></a>
				<? elseif($r['is_external']): //외부링크 ?>
				<span class="text-danger"><a class="text-overflow-ellipsis text-overflow-ellipsis-box" title="<?=html_escape($r['bf_save'])?>" href="<?=html_escape($r['view_url'])?>" target="_blank">🔗 <?=html_escape($r['bf_save'])?></a></span>
				<div class="div_iframe_htmlOgp">
					<iframe class="iframe_htmlOgp" height="150" src="<?=SITE_URI_PREFIX?>misc/htmlOgp?url=<?=html_escape(urlencode($r['bf_save']))?>"></iframe>
				</div>
				<? else: //미리보기 불가 ?>
				<span class="text-danger">미리보기 지원되지 않는 파일</span>
				<? endif;?>
			</div>
			<? if($mode=='edit'): ?>
			<div class="panel-footer text-center">
				<label><input type="checkbox" name="delf[]" value="<?=$r['bf_idx']?>" > <span class="glyphicon glyphicon-floppy-remove"></span> 삭제</label>
				<? if($r['bf_represent']):?>  / <button type="button" disabled class="btn btn-success btn-xs">대표이미지</button>
				<? elseif($r['is_image'] || $r['is_external']):?>  / <button type="button" onclick="return set_represent(this.form,<?=$r['bf_idx']?>)" class="btn btn-info btn-xs">대표이미지설정</button><? endif; ?>
				
			</div>
			<? endif; ?>
		</div>
	</div>
<?
endforeach;
?>
<? if($mode=='read' && count($bf_rows)==0): ?>
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3  mode-read-file-item">
	첨부된 파일이 없습니다.
	</div>
<? endif; ?>
<?
	if(preg_match('/^(edit|answer|write)$/',$mode)):
?>
	<script type="text/javascript">
	// <!--
		$(function(){
				init_drag_and_drop_file();
		});
		function select_file_form(ta){
			var $file_item = $(ta).parents(".mode-form-file-item");
			var target = $(ta).find("option:selected").attr('data-target');
			$($file_item).find('.mode-form-file-item-input').addClass('hide');
			$($file_item).find('.mode-form-file-item-input input').prop('disabled',true);
			$($file_item).find(target).removeClass('hide');
			$($file_item).find(target).find('input').prop('disabled',false);

		}
	// -->
	</script>
<?
		for($i=0,$m=$bm_row['bm_file_limit']-count($bf_rows);$i<$m;$i++):
?>
<!--
	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 mode-form-file-item drag-and-drop-files" title="드래그앤드롭으로 파일 첨부 가능">
		<div class="panel panel-primary center-block " style="max-width:310px">
			<div class="panel-heading text-center  text-overflow-ellipsis">
				NEW FILE
			</div>
			<div class="panel-body text-center">
				<div class="img-preview">Select File... or Drop File...</div>
			</div>
			<div class="panel-footer text-center">
				<span class="btn-block btn btn-primary btn-file ">
					<span class="glyphicon glyphicon-floppy-open"></span> 파일 선택...<input type="file" name="upf[]" multiple onchange="bbs_form_file_item_oncahngeUpload(event)">
				</span>
			</div>
		</div>
	</div>
-->

	<div class="col-xs-12 col-sm-6 col-md-4 col-lg-4 mode-form-file-item" >
		<div class="panel panel-primary center-block " style="max-width:310px">
			<div class="panel-heading text-center  text-overflow-ellipsis">

				<span class="input-group-btn">
					<select class="form-control ext_urls_types" name="ext_urls_types[<?=$i?>]" onchange="select_file_form(this)">
						<option value="attach/file" data-target=".attach-file">첨부파일</option>
						<option value="external/url" data-target=".external-url">외부링크</option>
						<option value="external/image" data-target=".external-image">외부이미지</option>
						<option value="attach/dataurl" data-target=".attach-dataurl">데이터URL</option>
					</select>
				</span>

			</div>

			<div class="panel-body text-center mode-form-file-item-input  attach-file drag-and-drop-files " title="드래그앤드롭으로 파일 첨부 가능">
				<div>
					<span class="btn-block btn btn-primary btn-file ">
						<span class="glyphicon glyphicon-floppy-open"></span> 파일 선택...<input type="file" name="upf[]" multiple onchange="bbs_form_file_item_oncahngeUpload(this)">
						<!-- <span class="glyphicon glyphicon-floppy-open"></span> 파일 선택...<input type="file" name="upf[]" onchange="bbs_form_file_item_oncahngeUpload(event)"> -->
					</span>
				</div>
				<div class="img-preview img-preview-upload" >Select File... or Drop File...</div>
			</div>
			<div class="panel-body text-center mode-form-file-item-input  external-image external-url hide">
				<div>
					<div class="input-group">
						<input type="text" name="ext_urls[<?=$i?>]"  class="form-control ext_urls" placeholder="http://~~~">
					</div>
				</div>
			</div>
			<div class="panel-body text-center mode-form-file-item-input attach-dataurl  hide">
				<div>
					<div class="img-preview img-preview-dataurl" >이미지 에디터를 사용해서만 처리됩니다.</div>
					<div class="input-group">
						<input type="hidden" name="ext_urls[<?=$i?>]"  class="form-control ext_urls ext_urls-dataurl" placeholder="data:~~~">
					</div>
				</div>
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
	function bbs_form_file_item_oncahngeUpload(input_file){
		try{
			var ta = input_file;
			var preview = $(ta).parents('.mode-form-file-item').find('.img-preview-upload');
			preview.html('');
			if(ta.files.length > 0){ //파일 업로드가 있을 경우만
				for(var i=0,m=ta.files.length;i<m;i++){ //다중 셀렉트 가능.
					var file = ta.files[i];
					var num = i;
					if(file.type.indexOf('image')===-1){

						if(preview.html().length>1){
							$(preview).append('<hr>');
						}
						$(preview).append('<div class="text-danger">파일'+(num+1)+'</div><div>'+file.name+'</div><div>'+file.size+' Byte</div>');


						continue;
					}
					(function(file,num){
						var fileReader = new FileReader();
						fileReader.onload = function (event) {
							var ta = event.target;
							var img = new Image();
							$(img).attr('data-num',num);
							img.src = ta.result;
							if(m>1){
								if(preview.html().length>1){
									$(preview).append('<hr>');
								}
							}
							$(preview).append('<div class="text-primary preview_text">파일'+(num+1)+' <a href="/WC2/WC2.html" target="_blank" class="btn btn-success btn-xs">수정</a></div>');


							$(preview).find('.preview_text a.btn').get(0).onclick=function(img){
								return function(){
									set_wc2cb(img);
								}
							}(img);;
							$(preview).append(img);
						};
						fileReader.readAsDataURL(file);
					})(ta.files[i],num)
				}
			}else{
				preview.html('Select File... or Drop File...');
			}
		}catch(e){
			//지원안되는 브라우저
		}
	}


/**
 * 파일 업로드 관련
 */
function init_drag_and_drop_file(){
	$('.drag-and-drop-files').on("dragstart dragend dragover dragenter dragleave drag drop",function(evt){
		// console.log(evt.type);
		evt.preventDefault();
		evt.stopPropagation();
	})
	$('.drag-and-drop-files').on("drop",function(evt){
		try{
			// console.log(evt.originalEvent.dataTransfer.files);
			$(this).find('input[type="file"]').prop('files',evt.originalEvent.dataTransfer.files)
			// $(this).find('input[type="file"]').trigger('change');
			bbs_form_file_item_oncahngeUpload($(this).find('input[type="file"]').get(0))
		}catch(e){
			//지원 안되는 브라우저
		}
	})
}
function set_wc2cb(img){
	WC2CB.img = img;
}
//웹 에디터 콜백
var WC2CB = {
	"img":null,
	"getImage":function(){
		return this.img;
	},
	"btnFileSaveCallback":function(win,wc2){
		// var dataUrl = wc2.activeWcb.toDataURL()
		var dataUrl = wc2.getDataurlForFileSavePreview();
		var $p = $(this.img).parents('.mode-form-file-item');
		$p.find('select.ext_urls_types').val('attach/dataurl').get(0).onchange();
		$p.find('input.ext_urls-dataurl').val(dataUrl);
		// $p.find("img.attach-dataurl-dataurl").prop("src",dataUrl);
		var img = new Image();
		$(img).prop('src',dataUrl);
		$p.find('.img-preview-dataurl').html("")
		.append('<div class="text-primary preview_text">파일'+' <a href="/WC2/WC2.html" target="_blank" class="btn btn-success btn-xs">수정</a></div>')
		.append(img);



		$p.find('.img-preview-dataurl .preview_text a.btn').get(0).onclick=function(img){
			return function(){
				set_wc2cb(img);
			}
		}(img);;

		win.close();
	}
};

</script>
