<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
//print_r($b_rows);
?>

<div class="panel panel-default bbs-mode-list">

	<!-- Default panel contents -->
	<div class="panel-heading">
		<nav class="text-right">
			게시물 : <?=$count?> (<?=$max_page?> page)
		</nav>
	</div>
	<? if(count($b_n_rows)>0): ?>
	<div class="table-responsive">
		<table class="table table-condensed" style="table-layout:fixed">
			<col width="80">
			<col >
			<col width="80">
			<col width="120">
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
						<? if(!empty($b_row['bf_cnt'])): ?>
							<span class="bf_cnt label label-default" title="<?=$b_row['bf_cnt']?> 파일"><?=$b_row['bf_cnt']?></span>
						<? endif; ?>
						
						<? if(!empty($b_row['bc_cnt'])): ?>
							<span class="bc_cnt label label-default" title="<?=$b_row['bc_cnt']?> 댓글"><?=$b_row['bc_cnt']?></span>
						<? endif; ?>
					</div>
				
				</td>
				<td class="text-center"><?=html_escape($b_row['b_name'])?></td>
				<td class="text-center hidden-xs hidden-sm"><?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></td>

			</tr>
		<? endforeach; ?>
		</table>
	</div>
	<? endif; ?>
	
	<!-- Default panel contents -->
	<div class="panel-body">
		<div class="row">
		<? 
		foreach($b_rows as $b_row):
		//print_r($r);
		?>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="panel panel-default center-block" style="max-width:300px;">
					<div class="panel-heading text-center  text-overflow-ellipsis floating_label_parent"
					 ><? if(isset($b_row['b_category'])): ?><span class="label label-primary"><?=html_escape($b_row['b_category'])?></span> <? endif; ?><a href="<?=html_escape($b_row['read_url'])?>" title="<?=html_escape($b_row['b_title'])?>"><?=html_escape($b_row['b_title'])?></a></div>
					<div class="panel-body thumbnail-div floating_label_parent" >
						<a href="<?=html_escape($b_row['read_url'])?>">
							<div class="text-center thumbnail-box img-rounded" >
								<? if(isset($b_row['thumbnail_url'][0])): ?>
									<img class="img-rounded" src="<?=html_escape($b_row['thumbnail_url'])?>">
								<? else: ?>
									<div class="no-thumbnail"></div>
								<? endif; ?>
							</div>
						</a>
						<div class="floating_label">
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
					
				</div>
			</div>
		<?
		endforeach;
		?>
		<? if(count($b_rows)==0): ?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 center-block clearB">
				<div class="panel panel-default center-block" style="max-width:300px">
					<div class="panel-heading text-center  text-overflow-ellipsis"
					 >No-Data</div>
					<div class="panel-body text-center">
						No-Data
					</div>
				</div>
			</div>
		<? endif; ?>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<form action="<?=html_escape($bbs_conf['base_url'])?>/list" class="form-inline text-center">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<? if($bm_row['bm_use_category']!='0'): ?>
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
	<div class="panel-footer">
		<nav class="text-center">
			<?=$pagination?>
		</nav>
	</div>
</div>


