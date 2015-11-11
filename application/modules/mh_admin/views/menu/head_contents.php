<?

//$base_url = base_url();
?>
<!-- 게시판 추가 head_contents -->
<link href="<?=html_escape(base_url('css/bbs/skin/bbs/bbs.css'))?>" rel="stylesheet">
<!-- //게시판 추가 head_contents -->
<? if($mode=='read' && $bm_row['bm_use_reply']=='1'): ?>
<script src="<?=html_escape(base_url('js/bbs/comment.js'))?>"></script>
<? endif; ?>

<? if($mode=='write' || $mode=='edit' || $mode=='answer'): ?>
<script src="<?=html_escape(base_url('js/bbs/script.js'))?>"></script>
<? endif; ?>
