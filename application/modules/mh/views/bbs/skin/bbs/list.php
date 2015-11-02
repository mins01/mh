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
				<form action="" class="form-inline text-center">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" >
								<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
								<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
								</select>
							</div>
							<input name="q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">검색</button>
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
				<th class="text-center" width="80">작성자</th>
				<th class="text-center hidden-xs hidden-sm"  width="120">등록일</th>
			</tr>
		<? foreach($b_n_rows as $r):
		//print_r($r);
		?>
			<tr class="bbs-notice info <?=$b_idx==$r['b_idx']?'warning':''?> ">
				<td class="text-center hidden-xs">공지</td>
				<td class="bbs-title text-overflow-ellipsis"><a href="<?=html_escape($r['read_url'])?>"><?=html_escape($r['b_title'])?></a>
				</td>
				<td class="text-center"><?=html_escape($r['b_name'])?></td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape(date('m/d H:i',strtotime($r['b_insert_date'])))?></td>

			</tr>
		<? endforeach; ?>
		<? foreach($b_rows as $r):
		//print_r($r);
		?>
			<tr class="bbs-dpeth bbs-dpeth-<?=$r['depth']?> <?=$b_idx==$r['b_idx']?'warning':''?> ">
				<td class="text-center hidden-xs"><?=$start_num--?></td>
				<td class="bbs-title text-overflow-ellipsis"><a href="<?=html_escape($r['read_url'])?>"><?=html_escape($r['b_title'])?></a>
				</td>
				<td class="text-center"><?=html_escape($r['b_name'])?></td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape(date('m/d H:i',strtotime($r['b_insert_date'])))?></td>

			</tr>
		<? endforeach; ?>
		</table>
	</div>
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
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" >
								<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
								<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
								</select>
							</div>
							<input name="q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">검색</button>
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


