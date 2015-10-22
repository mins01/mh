<?
//$bm_row,$b_row
//$start_num,$count

?>

<form action="" method="post">
<input type="hidden" name="process" value="<?=html_escape($process)?>">
<div class="panel panel-default form-horizontal bbs-mode-form">
	<div class="panel-heading">
		<input type="text" required class="form-control" id="b_title" name="b_title" placeholder="글제목" value="<?=html_escape($b_row['b_title'])?>">
	</div>
	<ul class="list-group">
		
		<li class="list-group-item form-inline">
			<div class="input-group">
				<div class="input-group-addon">작성자</div>
					<? if($input_b_name): ?>
					<input type="text" class="form-control" required  name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
					<? else: ?>
					<input type="text" class="form-control" readonly  name="b_name" aria-label="작성자" placeholder="작성자" style="min-width:80px" maxlength="40" value="<?=html_escape($b_row['b_name'])?>">
					<? endif; ?>
			</div>
			<? if(!$logedin):?>
			<div class="input-group">
				<div class="input-group-addon">비밀번호</div>
				<input type="password" class="form-control" required name="b_pass" aria-label="비밀번호" placeholder="비밀번호" style="min-width:80px" value="" maxlength="40">
			</div>
			<? endif; ?>
		</li>
		
		<li class="list-group-item form-inline">
			
				<div class="input-group">
					<div class="input-group-addon">링크</div>
					<input type="text" class="form-control"  name="b_link" aria-label="링크" placeholder="http://mins01.com/mh/" style="min-width:200px" value="<?=html_escape($b_row['b_link'])?>">
				</div>
			<select name="b_category" class="selectpicker show-tick" style="width:8em" data-width="100px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리">

				<option value="" <?=$b_row['b_category']==''?'selected':''?>>없음</option>
				<option value="카테고리" <?=$b_row['b_category']=='카테고리'?'selected':''?>>카테고리</option>

			</select>
			<select name="b_html" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="글형식 설정" title="글형식"  data-header="글형식 설정">

				<option value="t" <?=$b_row['b_html']=='t'?'selected':''?> >Text</option>
				<option value="t" <?=$b_row['b_html']=='p'?'selected':''?> >Pre</option>
				<option value="t" <?=$b_row['b_html']=='h'?'selected':''?> >html</option>
				<option value="r" <?=$b_row['b_html']=='r'?'selected':''?> >rawHtml</option>

			</select>
			
			<div class="btn-group" data-toggle="buttons">
				<label class="btn btn-success  <?=!$b_row['b_secret']?'active':''?>">
					<input type="radio" name="b_secret" value="0" autocomplete="off" <?=!$b_row['b_secret']?'checked':''?>>공개글
				</label>
				<label class="btn btn-warning <?=$b_row['b_secret']=='1'?'active':''?>">
					<input type="radio" name="b_secret" value="1" autocomplete="off" <?=$b_row['b_secret']=='1'?'checked':''?>>비밀글
				</label>
			</div>
			
			<select name="b_notice" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="공지글 설정" title="공지글" data-header="공지글 설정">
				
				<option value="0" <?=$b_row['b_notice']=='0'?'selected':''?>>일반글</option>
				<?
					for($i=1,$m=10;$i<$m;$i++):
				?>
				<option value="<?=$i?>" <?=$b_row['b_notice']==$i?'selected':''?>>Lv.<?=$i?></option>
				<?
					endfor;
				?>
			</select>
		</li>
	</ul>
	<div class="panel-body" style="min-height:200px">
		<textarea class="form-control" name="b_text" rows="3"  placeholder="글내용" style="min-height:180px"><?=html_escape($b_row['b_text'])?></textarea>
	</div>
	<div class="panel-footer text-right">
	<button type="submit" href="<?=html_escape($bbs_conf['list_url'])?>" class="btn btn-primary glyphicon glyphicon-ok"> 확인</button>
	<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-remove"> 취소</button>
	</div>
</div>
</form>
