<?

$base_url = base_url();
?>

<!-- 게시판 추가 head_contents -->

<script type="text/javascript" src="/web_work/js/_M/_M.js?t=<?=REFLESH_TIME?>"></script>
<script type="text/javascript" src="/web_work/js/_M/UI/_M.UI.js?t=<?=REFLESH_TIME?>"></script>
<link rel="stylesheet" type="text/css" href="/web_work/js/_M/UI/POPLAYER/_M.UI.POPLAYER.css?t=<?=REFLESH_TIME?>"/>
<script type="text/javascript" src="/web_work/js/_M/UI/POPLAYER/_M.UI.POPLAYER.js?t=<?=REFLESH_TIME?>"></script>
<link href="/web_work/mb_wysiwyg_dom/mb_wysiwyg.css?t=<?=REFLESH_TIME?>" rel="stylesheet" type="text/css" />

<!-- MultipleInputBox -->
<link rel="stylesheet" type="text/css" href="<?=html_escape(SITE_URI_ASSET_PREFIX.'etcmodule/ui_MultipleInputBox/MultipleInputBox.css')?>?t=<?=REFLESH_TIME?>"/>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'etcmodule/ui_MultipleInputBox/MultipleInputBox.js')?>?t=<?=REFLESH_TIME?>"></script>




<!-- //게시판 추가 head_contents -->
<link href="<?=html_escape(SITE_URI_ASSET_PREFIX.'css/bbs/skin/bbs_skin_default.css')?>?t=<?=REFLESH_TIME?>" rel="stylesheet"  class="mb_wysiwyg_head_css">
<link href="<?=html_escape(SITE_URI_ASSET_PREFIX.'css/bbs/skin/calendar/bbs_skin_calendar.css')?>?t=<?=REFLESH_TIME?>" rel="stylesheet"  class="mb_wysiwyg_head_css">
<!-- for RSS -->
<link rel="alternate" type="application/rss+xml" title="RSS : <?=$bm_row['bm_title']?>" href="<?=html_escape($bbs_conf['rss_url'])?>" />

<script type="text/javascript"
	  src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;key=AIzaSyBw8nAJOdLCqN3DuGZJKvY0idP_QWRR5WM&amp;libraries=places"></script>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/google_map.js')?>"></script>

<? if($mode=='read' && $bm_row['bm_use_comment']=='1'): ?>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/comment.js')?>"></script>
<? endif; ?>

<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/script.js')?>"></script>
<? if($mode=='write' || $mode=='edit' || $mode=='answer'): ?>

<link href="/web_work/mb_wysiwyg_dom/bootstrap.css?t=<?=REFLESH_TIME?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/mb_wysiwyg.js?t=<?=REFLESH_TIME?>"></script>
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/set.toolbar.js?t=<?=REFLESH_TIME?>"></script>

<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/mh_gps.js')?>"></script>

<script>
//--- 위지윅 생성
$(
function(){
	 $('.pre-wysiwyg').each(function(idx,el){
		 createWysiwygObj(el)
	 })
})
</script>

<!-- 달력 -->
<link href="<?=html_escape(SITE_URI_ASSET_PREFIX.'css/vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css')?>?t=<?=REFLESH_TIME?>" rel="stylesheet">

<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js')?>?t=<?=REFLESH_TIME?>"></script>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.kr.js')?>?t=<?=REFLESH_TIME?>"></script>
<script>
$(
	function(){
		$('.input-daterange').datepicker({
			format: "yyyy-mm-dd",
			language: "kr",
			autoclose: true,
			todayHighlight: true,
			todayBtn: "linked",
		});
		return;
	}
);
</script>
<? endif; ?>
<script>
/**
* 스킨 전용 기능
*/
//--- 장소 확인 구글맵 띄우기
function showMapByAddress(address,lat,Lng){
	if(!address || address.length<1){
		alert('확인할 주소가 너무 짧습니다.');
		return false;
	}
	var latLng = '';
	if(lat.length>1 && Lng.length>1){
		latLng =lat+','+Lng;
	}
	var url = "/web_work/google_apis/maps/maps.php?address="+encodeURIComponent(address)+'&latLng='+encodeURIComponent(latLng);
	window.open(url,'pop_showMapByAddress',"width=800,height=600");
}
</script>
