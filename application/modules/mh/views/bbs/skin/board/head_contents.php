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
<? endif; ?>
