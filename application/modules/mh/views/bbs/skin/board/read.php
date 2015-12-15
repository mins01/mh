<?
//$bm_row,$b_row
//$start_num,$count

?>


<div class="panel panel-default <?=$b_row['b_notice']>0?'bbs-notice':''?> bbs-mode-read">
	<div class="panel-heading plotting_label_parent">
		<h3 class="panel-title text-center bbs-title "><?=html_escape($b_row['b_title'])?>
		<div class="clearfix"></div>
		</h3>
		
		<div class="plotting_label">
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
				<span class="input-group-addon" id="basic-addon1">작성자</span>
				<span class="form-control" id="basic-addon1"><?=html_escape($b_row['b_name'])?></span>
			</div>
		</li>
		<li class="list-group-item form-inline">
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
			<span class="pull-right">
				<span class="label label-info ">작성일 : <?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></span>
			</span>
			<span class="clearfix"></span>
		</li>
		<? if(isset($view_form_file[0])): ?>
		<li class="list-group-item form-inline bbs-mode-read-file">
			<?=$view_form_file?>
		</li>
		<? endif; ?>
	</ul>
	<div class="panel-body" style="min-height:200px">
		<?=nl2br(html_escape($b_row['b_text']))?>
	</div>
	<div class="panel-footer text-right">
		
		<? if($permission['list']): ?>
		<a href="<?=html_escape($bbs_conf['list_url'])?>" class="btn btn-sm btn-primary glyphicon glyphicon-list"> 목록</a>
		<? endif; ?>
		<? if($permission['answer']): ?>
		<a href="<?=html_escape($b_row['answer_url'])?>"  class="btn btn-sm btn-info glyphicon glyphicon-pencil"> 답변</a>
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
<?=$html_comment?>