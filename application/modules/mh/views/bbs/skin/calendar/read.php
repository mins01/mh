<?
//$bm_row,$b_row
//$start_num,$count

$period = floor((strtotime($b_row['b_etc_1'])-strtotime($b_row['b_etc_0']))/86400)+1;

$d_day  = floor((strtotime($b_row['b_etc_0'])-time())/86400);
$next_day  = floor((time()-strtotime($b_row['b_etc_1']))/86400);

if($d_day==0){
	$d_day_label = 'D-day';
}else if($d_day>0){
	$d_day_label = 'D-'.$d_day;
}else if($next_day>0){
	$d_day_label = '지남';
}else{
	$d_day_label = '진행';
}
?>


<div class="panel panel-default <?=$b_row['b_notice']>0?'bbs-notice':''?> bbs-mode-read">
	<div class="panel-heading floating_label_parent">
		<h3 class="panel-title text-center bbs-title "><?=html_escape($b_row['b_title'])?>
		<div class="clearfix"></div>
		</h3>
		
		<div class="floating_label">
			<? if(($b_row['is_new'])): ?>
				<span class="is_new label label-default" title="새글">new</span>
			<? endif; ?>
			<? if(!empty($b_row['bf_cnt'])): ?>
				<span class="bf_cnt label label-default" title="<?=$b_row['bf_cnt']?> 파일"><?=$b_row['bf_cnt']?></span>
			<? endif; ?>
			
			<? if(!empty($b_row['bc_cnt'])): ?>
				<span class="bc_cnt label label-default" title="<?=$b_row['bc_cnt']?> 댓글"><?=$b_row['bc_cnt']?></span>
			<? endif; ?>
		</div>
		
	</div>
	<ul class="list-group">
		<li class="list-group-item form-inline">
			<div class="input-group">
				<span class="input-group-addon" >작성자</span>
				<span class="form-control" ><?=html_escape($b_row['b_name'])?></span>
			</div>
			
			<p class="pull-right form-control-static">
				<? if(isset($b_row['b_link'][0])): ?>
					<a class="label label-info glyphicon glyphicon-link" href="<?=html_escape($b_row['b_link'])?>" target="_blank">링크</a>
				<? endif; ?>
				<? if(isset($b_row['b_category'][0])): ?>
					<span class="label label-primary">카테고리:<?=html_escape($b_row['b_category'])?></span>
				<? endif; ?>
				<? if($b_row['b_secret']=='1'): ?>
					<span class="label label-danger">비밀글</span>
				<? endif; ?>
				<? if($b_row['b_notice']>0): ?>
					<span class="label label-danger">공지글</span>
				<? endif; ?>
				<span class="label label-info ">작성 : <?=html_escape(date('m-d H:i',strtotime($b_row['b_insert_date'])))?></span>
				<span class="label label-info ">조회 : <?=html_escape(isset($b_row['bh_cnt'][0])?$b_row['bh_cnt']:'0')?></span>
			</p>
			<span class="clearfix"></span>
		</li>
		<li class="list-group-item form-inline">
			<div class="input-group ">
				<span class="input-group-addon"><?=$d_day_label?></span>
				<span class="form-control"><?=$period?>일간</span>
			</div>
			<div class="input-group input-daterange">
				<span class="form-control" ><?=html_escape($b_row['b_etc_0'])?></span>
				<span class="input-group-addon">-</span>
				<span class="form-control" ><?=html_escape($b_row['b_etc_1'])?></span>
			</div>
			<? if(isset($b_row['b_etc_3'][0])): ?>
			<div class="input-group ">
				<span class="input-group-addon">주소</span>
				<span class="form-control"><?=html_escape($b_row['b_etc_3'])?></span>
				<div class="input-group-btn">
					<button type="button" class="btn btn-success" onclick="showMapByAddress('<?=html_escape($b_row['b_etc_3'])?>','<?=html_escape($b_row['b_num_0'])?>','<?=html_escape($b_row['b_num_1'])?>')">장소확인</button>
				</div>
			</div>
			
			<? if(isset($b_row['b_num_0'][0])):
				$lat = rawurlencode($b_row['b_num_0']);
				$lng = rawurlencode($b_row['b_num_1']);
				$v_query = rawurlencode(base64_encode($b_row['b_etc_3']));
				$url="http://map.naver.com/?menu=location&mapMode=0&lat={$lat}&lng={$lng}&dlevel=10&query={$v_query}&tab=1&enc=b64";
			?>
			<div class="btn-group ">
					<a href="<?=html_escape($url)?>" target="_blank" type="button" class="btn btn-success">네이버맵</a>
			</div>
			<? endif; ?>
			<? endif; ?>
				
			<div class="input-group hide">
				<span class="input-group-addon">좌표</span>
				<span class="form-control"><?=html_escape($b_row['b_etc_4'])?></span>
			</div>
			
		</li>
		
		<li class="list-group-item">
			<div id="google_map_canvas" style="height:300px"></div>
		</li>
		
		
		<? if(isset($view_form_file[0])): ?>
		<li class="list-group-item form-inline bbs-mode-read-file">
			<?=$view_form_file?>
		</li>
		<? endif; ?>
	</ul>
	<div class="panel-body contents-area" style="min-height:200px">
		<?=mh_util::cvt_html($b_row['b_text'],$b_row['b_html'])?>
	</div>
	<div class="panel-footer text-right">
		
		<? if($permission['list']): ?>
		<a href="<?=html_escape($bbs_conf['list_url'])?>" class="btn btn-sm btn-primary glyphicon glyphicon-list"> 목록</a>
		<? endif; ?>
		<div class="btn-group" role="group" aria-label="">
			
			<? if($permission['edit']): ?>
			<a href="<?=html_escape($b_row['edit_url'])?>"  class="btn btn-sm btn-warning glyphicon glyphicon-pencil"> 수정</a>
			<? endif; ?>
			<? if($permission['delete']): ?>
			<a href="<?=html_escape($b_row['delete_url'])?>"  class="btn btn-sm btn-danger glyphicon glyphicon-remove"> 삭제</a>
			<? endif; ?>
		</div>
		
	
	
	</div>
</div>

<script>
$(function(){
<? if(isset($b_row['b_etc_3'][0])&& isset($b_row['b_num_0'])&& isset($b_row['b_num_1'])):?>

	var lat = <?=$b_row['b_num_0']?>;
	var lng = <?=$b_row['b_num_1']?>;
	var zoom = <?=$b_row['b_num_2']?$b_row['b_num_2']:18?>;
	google_map.init_readonly_map(document.getElementById('google_map_canvas'),lat,lng,zoom);

<? else: ?>
	$('#google_map_canvas').parent().hide();
<? endif; ?>
})
</script>

<?=$html_comment?>