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

<link href="/web_work/mb_wysiwyg_dom/fontawesome.css" rel="stylesheet" type="text/css" />
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
			"format":"yyyy-mm-dd",
			language: "kr",
			autoclose: true,
			todayHighlight: true,
			todayBtn: "linked",
			});
		return;
		// var checkin = $('.b_etc_0_datepicker').datepicker(
			// {
				// "format":"yyyy-mm-dd",
				// language: "kr",
				// autoclose: true,
				// todayHighlight: true,
				// todayBtn: "linked",
			// }
		// ).on('changeDate', function(ev) {
			// var checkout_date = checkout.getDate();
			// if(!checkout_date){
				// checkout.datesDisabled()
				// checkout.setDate(ev.date)
			// }
			// $('.b_etc_1_datepicker').focus();
		// }).data('datepicker');
		// var checkout = $('.b_etc_1_datepicker').datepicker(
			// {
				// "format":"yyyy-mm-dd",
				// language: "kr",
				// autoclose: true,
				// todayHighlight: true,
				// todayBtn: "linked",
				// onRender: function(date) {
					// var checkout_date = checkout.getDate();
					// if(!checkout_date){
						// return '';
					// }
					// return date.valueOf() <= checkout_date.valueOf() ? 'disabled' : '';
				// }
			// }
		// ).data('datepicker');
	}
);
</script>
<? endif; ?>
