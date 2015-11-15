<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count

?>

<nav class="text-right">
	게시판 : <?=$count?> (<?=$max_page?> page)
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
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 등록</a>
				<? endif; ?>
			</div>
		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-condensed" style="table-layout:fixed">
			<tr >
				<th class="text-center hidden-xs" width="80">No</th>
				
				<th class="text-center">게시판아이디</th>
				<th class="text-center hidden-xs hidden-sm">테이블명</th>
				<th class="text-center" width="80">게시판제목</th>
				<th class="text-center" width="80">사용여부</th>
				<th class="text-center hidden-xs hidden-sm"  width="120">스킨</th>
				<th class="text-center hidden-xs hidden-sm"  width="120">생성일</th>
				<th class="text-center hidden-xs hidden-sm"  width="120">미리보기</th>
			</tr>
		<? foreach($bm_rows as $r):
		//print_r($r);
		?>
			<tr class="" align="center">
				<td class="text-center hidden-xs"><?=$start_num--?></td>
				<td class="bbs-title text-overflow-ellipsis">
					<a href="<?=html_escape($r['edit_url'])?>"><?=html_escape($r['b_id'])?></a>
				</td>
				<td class="bbs-title text-overflow-ellipsis hidden-xs hidden-sm">
					<?=html_escape($r['bm_table'])?>
				</td>
				<td class="bbs-title text-overflow-ellipsis">
					<?=html_escape($r['bm_title'])?>
				</td>
				<td class="bbs-title text-overflow-ellipsis">
					<?=$r['bm_open']=='1'?'ON':'OFF'?>
				</td>
				<td class="bbs-title hidden-xs hidden-sm">
					<?=html_escape($r['bm_skin'])?>
				</td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape(date('m/d H:i',strtotime($r['bm_insert_date'])))?></td>
				<td class="text-center hidden-xs hidden-sm"><a href="<?=base_url('/bbs/'.$r['b_id'])?>" target="_blank">[미리보기]</a></td>

			</tr>
		<? endforeach; ?>
		</table>
	</div>
	<? if(count($bm_rows)==0): ?>
		<div class="panel-body">
		<div class="alert alert-danger text-center" role="alert">게시판이 없습니다.</div>
		</div>
	<? endif; ?>
	<div class="panel-footer">
		<nav class="text-center">
			<?=$pagination?>
		</nav>
	</div>
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
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 등록</a>
				<? endif; ?>
			</div>
		</div>
	</div>
</div>


