<?
//$bm_row,$b_row
//$start_num,$count

?>

<div>
	<form action="" name="form_bbs" method="post" onsubmit="submitWysiwyg();return check_form_bbs(this);"  enctype="multipart/form-data" data-bm_use_category="<?=$bm_row['bm_use_category']?>"  >
	<input type="hidden" name="process" value="<?=html_escape($process)?>">
	<div class="panel panel-default form-horizontal bbs-mode-form">
		<div class="panel-heading">
			<input type="text" required maxlength="200" class="form-control" id="b_title" name="b_title" placeholder="글제목" value="<?=html_escape($b_row['b_title'])?>">
		</div>
		<ul class="list-group">
			
			<li class="list-group-item form-inline">
				<div class="input-group">
					<div class="input-group-addon">작성자</div>
						<? if($input_b_name): ?>
						<input type="text" class="form-control" required name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
						<? else: ?>
						<input type="text" class="form-control" readonly  name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
						<? endif; ?>
				</div>
				<? if($input_b_pass):?>
				<div class="input-group">
					<div class="input-group-addon">비밀번호</div>
					<input type="password" class="form-control" required name="b_pass" aria-label="비밀번호" placeholder="비밀번호" style="min-width:80px" value="<?=html_escape($b_row['b_pass'])?>" maxlength="40" <?=isset($b_row['b_pass'][0])?'readonly="readonly"':''?> >
				</div>
				<? endif; ?>
			</li>
			
			<li class="list-group-item form-inline">
				
				<div class="input-group">
					<div class="input-group-addon">링크</div>
					<input type="text" class="form-control"  name="b_link" aria-label="링크" placeholder="http://mins01.com/mh/" style="min-width:200px" value="<?=html_escape($b_row['b_link'])?>">
				</div>
				<? if($bm_row['bm_use_category']!='0'): ?>
				<?=form_dropdown('b_category', $bm_row['categorys'], $b_row['b_category'], 'class="selectpicker show-tick" style="width:8em" data-width="100px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리" ')?>
				<? endif; ?>
				<? if($bm_row['bm_use_secret']=='1'): ?>
				
				<div class="btn-group" >
					<?=print_onoff('b_secret',$b_row['b_secret'],'비밀글','일반글')?>
				</div>
				<? endif; ?>
				
				<?=form_dropdown('b_html', $permission['admin']?$bbs_conf['b_htmls_for_admin']:$bbs_conf['b_htmls'], $b_row['b_html'], ' class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="글형식" title="글형식"  data-header="글형식"')?>
				
				<?
				if($permission['admin']){
				echo form_dropdown('b_notice', $bbs_conf['b_notices'], $b_row['b_notice'], 'class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="공지설정" title="공지글" data-header="공지글 설정"');
				}
				?>

			</li>
			
			<? if(isset($view_form_file[0])): ?>
			<li class="list-group-item form-inline bbs-mode-read-file">
				<?=$view_form_file?>
			</li>
			<? endif; ?>
			
		</ul>
		<div class="panel-body" style="min-height:200px">
			<textarea class="form-control pre-wysiwyg" name="b_text" rows="3"  placeholder="글내용" style="min-height:180px"><?=html_escape($b_row['b_text'])?></textarea>
		</div>
		<div class="panel-footer text-right">
		<button type="submit" class="btn btn-primary glyphicon glyphicon-ok"> 확인</button>
		<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-remove"> 취소</button>
		</div>
	</div>
	</form>
</div>








