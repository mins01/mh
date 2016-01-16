<?

$base_url = base_url();
?>

<!-- 게시판 추가 head_contents -->

<script type="text/javascript" src="/web_work/js/_M/_M.js"></script>
<script type="text/javascript" src="/web_work/js/_M/UI/_M.UI.js"></script>
<link rel="stylesheet" type="text/css" href="/web_work/js/_M/UI/POPLAYER/_M.UI.POPLAYER.css"/>
<script type="text/javascript" src="/web_work/js/_M/UI/POPLAYER/_M.UI.POPLAYER.js"></script>

<link href="/web_work/mb_wysiwyg_dom/mb_wysiwyg.css" rel="stylesheet" type="text/css" />

<link href="<?=html_escape(base_url('css/bbs/skin/bbs_skin_default.css'))?>" rel="stylesheet">
<link href="<?=html_escape(base_url('css/bbs/skin/calendar/bbs_skin_calendar.css'))?>" rel="stylesheet">
<!-- //게시판 추가 head_contents -->
<? if($mode=='read' && $bm_row['bm_use_comment']=='1'): ?>
<script src="<?=html_escape(base_url('js/bbs/comment.js'))?>"></script>
<? endif; ?>

<? if($mode=='write' || $mode=='edit' || $mode=='answer'): ?>

<link href="/web_work/mb_wysiwyg_dom/bootstrap.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/mb_wysiwyg.js"></script>
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/set.toolbar.js"></script>

<script src="<?=html_escape(base_url('js/bbs/script.js'))?>"></script>

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
<link href="<?=html_escape(base_url('css/vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css'))?>" rel="stylesheet">

<script src="<?=html_escape(base_url('js/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js'))?>"></script>
<script src="<?=html_escape(base_url('js/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.kr.js'))?>"></script>
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
