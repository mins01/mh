<?
//$bm_row,$b_row
//$start_num,$count
if($mode=='write'||$mode=='answer'){
	if(isset($get['dt'])){
		$b_row['b_date_st'] =$b_row['b_date_ed'] = $get['dt'];
	}
}

?>

<div class="skin-form ">
	<form action="" name="form_bbs" method="post" onsubmit="submitWysiwyg();return check_form_bbs(this);"  enctype="multipart/form-data" data-bm_use_category="<?=$bm_row['bm_use_category']?>"  >
	<input type="hidden" name="process" value="<?=html_escape($process)?>">
	<div class="panel panel-default form-horizontal bbs-mode-form for-geo-hide">
		<div class="panel-heading">
			<input type="text" required maxlength="200" class="form-control" id="b_title" name="b_title" placeholder="글제목" value="<?=html_escape($b_row['b_title'])?>">
		</div>
		<div class="panel-body text-right p-5px">
			<button type="submit" class="btn btn-xs btn-primary glyphicon glyphicon-ok"> 확인</button>
			<button type="button" onclick="history.back()" class="btn btn-xs btn-danger glyphicon glyphicon-remove"> 취소</button>
		</div>
		<ul class="list-group">
			
			<li class="list-group-item form-inline">
				<div class="input-group">
					<div class="input-group-addon">작성자</div>
						<? if($input_b_name): ?>
						<input type="text" class="form-control" required name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
						<? else: ?>
						<input type="text" class="form-control" readonly  name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
						<? endif; ?>
				</div>
				<? if($input_b_pass):?>
				<div class="input-group">
					<div class="input-group-addon">비밀번호</div>
					<input type="password" class="form-control" required name="b_pass" aria-label="비밀번호" placeholder="비밀번호" style="min-width:80px" value="<?=html_escape($b_row['b_pass'])?>" maxlength="40" <?=isset($b_row['b_pass'][0])?'readonly="readonly"':''?> >
				</div>
				<? endif; ?>
			</li>
			<li class="list-group-item form-inline">
				<div class="input-group">
					<div class="input-group-addon">링크</div>
					<input type="text" class="form-control"  name="b_link" aria-label="링크" placeholder="http://mins01.com/mh/" style="min-width:80px" value="<?=html_escape($b_row['b_link'])?>">
				</div>
				<? if($bm_row['bm_use_category']!='0'): ?>
				<?=form_dropdown('b_category', $bm_row['categorys'], $b_row['b_category'], 'class="form-control show-tick" style="width:8em" data-width="100px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리" ')?>
				<? endif; ?>
				<? if($bm_row['bm_use_secret']=='1'): ?>
				<div class="btn-group">
					<?=print_onoff('b_secret',$b_row['b_secret'],'비밀글','일반글')?>
				</div>
				<? endif; ?>
				
				<?=form_dropdown('b_html', $permission['admin']?$bbs_conf['b_htmls_for_admin']:$bbs_conf['b_htmls'], $b_row['b_html'], ' class="form-control show-tick" style="width:6em" data-width="80px" aria-label="글형식" title="글형식"  data-header="글형식"')?>
				
				<?
				if($permission['admin']){
				echo form_dropdown('b_notice', $bbs_conf['b_notices'], $b_row['b_notice'], 'class="form-control show-tick" style="width:6em" data-width="80px" aria-label="공지설정" title="공지글" data-header="공지글 설정"');
				}
				?>
				<div class="input-group input-daterange">
					<div class="input-group-addon">날짜</div>
					<input type="text" class="form-control"  name="b_date_st" aria-label="시작날짜" placeholder="YYYY-MM-DD" style="max-width:8em" value="<?=html_escape($b_row['b_date_st'])?>">
					<div class="input-group-addon">-</div>
					<input type="text" class="form-control"  name="b_date_ed" aria-label="끝날짜" placeholder="YYYY-MM-DD" style="max-width:8em" value="<?=html_escape($b_row['b_date_ed'])?>">
				</div>
				<div class="btn-group">
					<label class="m-onoff m-onoff-success m-with-label btn btn-success"><input type="radio" name="none_geo" value="1" autocomplete="off" onclick="show_geo_form(true)"><div class="m-layout" data-label-on="지도 on" data-label-off="on"></div>
					</label>
					<label class="m-onoff m-onoff-warning m-with-label btn btn-warning"><input type="radio" name="none_geo" value="0" autocomplete="off"  onclick="show_geo_form(false)" checked=""><div class="m-layout" data-label-on="지도 off" data-label-off="off"></div>
					</label>
				</div>
			</li>
			<li class="list-group-item  for-geo-form">
				<div class="input-group">
						<div class="input-group-addon">주소</div>
						<input type="text" class="form-control"	id="google_map_address" name="b_etc_3" aria-label="주소" placeholder="주소" style="min-width:6em" value="<?=html_escape($b_row['b_etc_3'])?>">
						<div class="input-group-btn">
						<button type="button" class="btn btn-success" onclick="google_map.search_by_address(this.form.b_etc_3.value)">주소검색</button>
					</div>
				</div>
			</li>
			<li class="list-group-item form-inline  for-geo-form">
				
				<div class="input-group">
					<div class="input-group-addon">위도</div>
					<input type="text" class="form-control" readonly id="google_map_lat" name="b_num_0" aria-label="위도" placeholder="위도" size="4" value="<?=html_escape($b_row['b_num_0'])?>">
					<div class="input-group-addon">경도</div>
					<input type="text" class="form-control" readonly id="google_map_lng" name="b_num_1" aria-label="경도" placeholder="경도" size="4" value="<?=html_escape($b_row['b_num_1'])?>">
					<div class="input-group-btn">
					<button type="button" class="btn btn-success" onclick="google_map.search_by_gps()">GPS검색</button>
					</div>
				</div>
				<div class="input-group">
					<div class="input-group-addon">Zoom</div>
					<input type="text" class="form-control" readonly id="google_map_zoom" name="b_num_2" aria-label="Zoom" placeholder="위도" size="4" value="<?=html_escape($b_row['b_num_2'])?>">
					
					
				</div>

			</li>
			<li	 class="list-group-item  for-geo-form">
				<div id="google_map_canvas" style="height:300px"></div>
			</li>
			<? if($bm_row['bm_use_tag']!='0'): ?>
				<li class="list-group-item">
					<div class="input-group">
						<div class="input-group-addon">Tag</div>
						<div class="multipleInputBox form-control" style="height:auto" data-removeEmptyBox data-inputBoxType="custom" <?=$bm_row['bm_use_tag']=='2'?'data-once-required':''?> data-min="1" data-max="10"  data-autoAddInputBox data-autoRemoveInputBox data-prefix="#" data-separator=" " >
							<input type="hidden" maxlength="200" class="form-control multipleInputBox-sync" <?=$bm_row['bm_use_tag']=='2'?'required':''?>  id="bt_tags_string" name="bt_tags_string" placeholder="tags (separator = ',',';',whitespace)" value="<?=html_escape(implode(' ',$bt_tags))?>">	
						</div>
						
					</div>
					
				</li>
			<? endif; ?>
			<? if(isset($view_form_file[0])): ?>
			<li class="list-group-item form-inline bbs-mode-read-file">
				<?=$view_form_file?>
			</li>
			<? endif; ?>
			
		</ul>
		<div class="panel-body" style="min-height:200px">
			<textarea class="form-control pre-wysiwyg" name="b_text" rows="3"  placeholder="글내용" style="min-height:180px"><?=html_escape($b_row['b_text'])?></textarea>
		</div>
		<div class="panel-footer text-right">
		<button type="submit" class="btn btn-primary glyphicon glyphicon-ok"> 확인</button>
		<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-remove"> 취소</button>
		</div>
	</div>
	</form>
	<datalist class="tag_lists" id="tag_lists">
		<option value="tag_elements"></option>
	</datalist>
</div>



<script type="text/javascript">
//<!--
function callbackMaps(obj,popWindow){
//var obj = {
//		'lat':LatLng.lat()
//		,'lng':LatLng.lng()
//		,'address':document.form_search.address.value
//		}
document.form_bbs.b_etc_3.value=obj.address;
document.form_bbs.b_num_0.value=obj.lat
document.form_bbs.b_num_1.value=obj.lng
popWindow.close();
}

function show_geo_form(bool){

	if(bool){
		if(!show_geo_form.inited_map){
			google_map.init();
			show_geo_form.inited_map = true;
		}
		$('.bbs-mode-form.for-geo-hide').removeClass('for-geo-hide').addClass('for-geo-show');
	}else{
		$('.bbs-mode-form.for-geo-show').removeClass('for-geo-show').addClass('for-geo-hide');
	}
	
}
show_geo_form.inited_map = false;

var on_geo = <?=isset($b_row['b_etc_3'][0])?'true':'false'?>;
if(on_geo){
	$(function(){
		$(document.form_bbs.none_geo[0]).trigger('click');
	})
}
//-->
</script>
<script>
$(function(){
	// multipleInputBox 초기화
	$(".multipleInputBox").each(function(idx,el){
		var cfg = {}
		cfg.customInputBox = '<input type="text" maxlength="30" list="tag_lists" placeholder="tag" />';
		var t = MultipleInputBox(el,cfg);
	})
	// 태그 르시트 갱신
	var tag_lists_url = <?=json_encode($bbs_conf['tag_lists_url'])?>;
	load_tag_lists(tag_lists_url)
})
</script>
