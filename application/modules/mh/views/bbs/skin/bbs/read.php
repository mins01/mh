<?
//$bm_row,$b_row
//$start_num,$count

?>


<div class="panel panel-default <?=$b_row['b_notice']>0?'bbs-notice':''?> bbs-mode-read">
	<div class="panel-heading">
		<h3 class="panel-title text-center bbs-title"><?=html_escape($b_row['b_title'])?>
		<sup><span class="label label-success">New</span>
		<span class="label label-info">12</span></sup>
		</h3>
	</div>
	<ul class="list-group">
		<li class="list-group-item form-inline">
		<? if(isset($b_row['b_link'][0])): ?>
			<a class="label label-info glyphicon glyphicon-link" href="<?=html_escape($b_row['b_link'])?>" target="_blank">링크</a>
		<? endif; ?>
		<? if(isset($b_row['b_category'][0])): ?>
			<span class="label label-primary"><?=html_escape($b_row['b_category'])?></span>
		<? endif; ?>
		<? if($b_row['b_secret']=='1'): ?>
			<span class="label label-danger">비밀글</span>
		<? endif; ?>
		<? if($b_row['b_notice']>0): ?>
			<span class="label label-danger">공지글 Lv.<?=$b_row['b_notice']?></span>
		<? endif; ?>
		<span class="pull-right">
			<span class="label label-info ">작성일 : <?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></span>
		</span>
		<span class="clearfix"></span>
		</li>
	</ul>
	<div class="panel-body" style="min-height:200px">
		<?=nl2br(html_escape($b_row['b_text']))?>
	</div>
	
	<div class="panel-footer text-right">
	
		<a href="<?=html_escape($bbs_conf['list_url'])?>" class="btn btn-primary glyphicon glyphicon-list"> 목록</a>
	<div class="btn-group" role="group" aria-label="">
		<a href="<?=html_escape($b_row['answer_url'])?>"  class="btn btn-info glyphicon glyphicon-pencil"> 답변</a>
		<a href="<?=html_escape($b_row['edit_url'])?>"  class="btn btn-warning glyphicon glyphicon-pencil"> 수정</a>
	</div>
		<a href="<?=html_escape($b_row['delete_url'])?>"  class="btn btn-danger glyphicon glyphicon-remove"> 삭제</a>
	
	
	</div>
</div>
