<?

$base_url = base_url();
?>

<!-- 게시판 추가 head_contents -->
<link href="<?=html_escape(base_url('css/bbs/skin/bbs_skin_default.css'))?>" rel="stylesheet">
<!-- //게시판 추가 head_contents -->
<? if($mode=='read' && $bm_row['bm_use_comment']=='1'): ?>
<script src="<?=html_escape(base_url('js/bbs/comment.js'))?>"></script>
<? endif; ?>

<? if($mode=='write' || $mode=='edit' || $mode=='answer'): ?>
<script src="<?=html_escape(base_url('js/bbs/script.js'))?>"></script>

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
