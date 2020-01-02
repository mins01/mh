<?

//$base_url = base_url();
?>
<!-- 게시판 추가 head_contents -->
<link href="<?=html_escape(SITE_URI_ASSET_PREFIX.'css/bbs/skin/bbs_skin_default.css')?>" rel="stylesheet"  class="mb_wysiwyg_head_css">
<!-- //게시판 추가 head_contents -->
<? if($mode=='read' && $bm_row['bm_use_comment']=='1'): ?>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/comment.js')?>"></script>
<? endif; ?>

<? if($mode=='write' || $mode=='edit' || $mode=='answer'): ?>
<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/script.js')?>"></script>
<? endif; ?>
