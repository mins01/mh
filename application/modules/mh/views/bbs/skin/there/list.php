<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
?>

<nav class="text-right">
	게시물 : <?=$count?> (<?=$max_page?> page)
</nav>

<div class="panel panel-default bbs-mode-list">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<div class="row">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<form action="<?=html_escape($bbs_conf['base_url'])?>/list" class="form-inline text-center">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<? if($bm_row['bm_use_category']=='1'): ?>
								<?=form_dropdown('ct', $bm_row['categorys'], isset($get['ct'])?$get['ct']:'', 'class="selectpicker show-tick" style="width:8em" data-width="80px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리"')?>
								<? endif; ?>
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" >
								<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
								<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
								<option value="title_or_text" <?=$get['tq']=='title_or_text'?'selected':''?>>제목+내용</option>
								</select>
							</div>
							<input name="q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info">검색</button>
							</span>
						</div><!-- /input-group -->
					</div>
				</form>
			</div>
			<div class="col-lg-2 col-sm-2 text-right">
				<? if($permission['write']): ?>
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 작성</a>
				<? endif; ?>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-condensed" style="table-layout:fixed">
			<tr >
				<th class="text-center hidden-xs" width="80">No</th>
				<th class="text-center">제목</th>
				<th class="text-center hidden-xs hidden-sm " width="80">작성자</th>
				<th class="text-center "  width="120">등록</th>
				<th class="text-center hidden-xs hidden-sm"  width="40">조회</th>
			</tr>
		<? foreach($b_n_rows as $b_row):
		//print_r($r);
		?>
			<tr class="bbs-notice info <?=$b_idx==$b_row['b_idx']?'warning':''?> ">
				<td class="text-center hidden-xs">공지</td>
				<td class="bbs-title text-overflow-ellipsis floating_label_parent">
					<? if(isset($b_row['b_category'])): ?><span class="label label-primary"><?=html_escape($b_row['b_category'])?></span><? endif; ?>
					<a href="<?=html_escape($b_row['read_url'])?>"><?=html_escape($b_row['b_title'])?></a>
					
					<div class="floating_label">
						<? if(($b_row['is_new'])): ?>
							<span class="is_new label label-default" title="새글">new</span>
						<? endif; ?>
						<? if(($b_row['b_secret'])): ?>
							<span class="b_secret label label-default" title="비밀">S</span>
						<? endif; ?>
						<? if(!empty($b_row['bf_cnt'])): ?>
							<span class="bf_cnt label label-default" title="<?=$b_row['bf_cnt']?> 파일"><?=$b_row['bf_cnt']?></span>
						<? endif; ?>
						
						<? if(!empty($b_row['bc_cnt'])): ?>
							<span class="bc_cnt label label-default" title="<?=$b_row['bc_cnt']?> 댓글"><?=$b_row['bc_cnt']?></span>
						<? endif; ?>
					</div>
				
				</td>
				<td class="text-center hidden-xs hidden-sm "><?=html_escape($b_row['b_name'])?></td>
				<td class="text-center "><?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape($b_row['bh_cnt'])?></td>

			</tr>
		<? endforeach; ?>
		<? foreach($b_rows as $b_row):
		//print_r($r);
		?>
			<tr class="bbs-dpeth bbs-dpeth-<?=$b_row['depth']?> <?=$b_idx==$b_row['b_idx']?'warning':''?> ">
				<td class="text-center hidden-xs"><?=$start_num--?></td>
				<td class="bbs-title text-overflow-ellipsis floating_label_parent">
					<? if(isset($b_row['b_category'])): ?><span class="label label-primary"><?=html_escape($b_row['b_category'])?></span><? endif; ?>
					<? if(isset($b_row['b_num_3'][0])): ?><span title="star-<?=$b_row['b_num_3']?>" class="label label-default star-span star-<?=$b_row['b_num_3']?>"></span><? endif; ?>
					
					<a href="<?=html_escape($b_row['read_url'])?>"><?=html_escape($b_row['b_title'])?></a>
					
					<div class="floating_label">
						<? if(($b_row['is_new'])): ?>
							<span class="is_new label label-default" title="새글">new</span>
						<? endif; ?>
						<? if(($b_row['b_secret'])): ?>
							<span class="b_secret label label-default" title="비밀">S</span>
						<? endif; ?>
						<? if(!empty($b_row['bf_cnt'])): ?>
							<span class="bf_cnt label label-default" title="<?=$b_row['bf_cnt']?> 파일"><?=$b_row['bf_cnt']?></span>
						<? endif; ?>
						
						<? if(!empty($b_row['bc_cnt'])): ?>
							<span class="bc_cnt label label-default" title="<?=$b_row['bc_cnt']?> 댓글"><?=$b_row['bc_cnt']?></span>
						<? endif; ?>
					</div>
				
				</td>
				<td class="text-center hidden-xs hidden-sm  text-overflow-ellipsis"><?=html_escape($b_row['b_name'])?></td>
				<td class="text-center "><?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape($b_row['bh_cnt'])?></td>

			</tr>
		<? endforeach; ?>
		</table>
	</div>
	<? if(count($b_rows)==0): ?>
		<div class="panel-body">
		<div class="alert alert-danger text-center" role="alert">게시물이 없습니다.</div>
		</div>
	<? endif; ?>
	<div class="panel-footer">
		<nav class="text-center">
			<?=$pagination?>
		</nav>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<form action="" class="form-inline text-center">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<? if($bm_row['bm_use_category']=='1'): ?>
								<?=form_dropdown('ct', $bm_row['categorys'], isset($get['ct'])?$get['ct']:'', 'class="selectpicker show-tick" style="width:8em" data-width="80px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리"')?>
								<? endif; ?>
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" >
								<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
								<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
								<option value="title_or_text" <?=$get['tq']=='title_or_text'?'selected':''?>>제목+내용</option>
								</select>
							</div>
							<input name="q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info">검색</button>
							</span>
						</div><!-- /input-group -->
					</div>
				</form>
			</div>
			<div class="col-lg-2 col-sm-2 text-right">
				<? if($permission['write']): ?>
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 작성</a>
				<? endif; ?>
			</div>
		</div>
	</div>
</div>


