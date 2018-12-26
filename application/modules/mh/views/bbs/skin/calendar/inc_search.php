<form action="<?=html_escape($bbs_conf['base_url'])?>/list" class="form-inline text-center form-search">
	<? if(isset($get['lm'])): ?><input type="hidden" name="lm" value="<?=html_escape($get['lm'])?>"><? endif; ?>
	<div class="form-group">
		<div class="input-group">
			<div class="input-group-btn">
			
				<? if($bm_row['bm_use_category']!='0'): ?>
				<?=form_dropdown('ct', $bm_row['categorys'], isset($get['ct'])?$get['ct']:'', 'class="form-control btn btn-default" style="max-width:8em" aria-label="카테고리 설정" title="카테고리" ')?>
				<? endif; ?>
				<select name="tq" class="form-control btn btn-default" style="max-width:8em" aria-label="검색대상" >
				<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
				<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
				<option value="tt" <?=$get['tq']=='tt'?'selected':''?>>제목+내용</option>
				<? if($bm_row['bm_use_comment']){ ?><option value="ttc" <?=$get['tq']=='ttc'?'selected':''?>>제목+내용+코멘트</option><? } ?>
				<option value="name" <?=$get['tq']=='name'?'selected':''?>>작성자</option>
				<? if($bm_row['bm_use_tag']!='0'): ?><option value="tag" <?=$get['tq']=='tag'?'selected':''?>>태그</option><? endif;?>
				</select>
				
			</div>
		</div>
		<div class="input-group">
			<input name="q" aria-label="검색어" type="search" class="form-control " placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
			<span class="input-group-btn">
				<button type="submit" class="btn btn-info">검색</button>
			</span>
		</div><!-- /input-group -->
	</div>
</form>