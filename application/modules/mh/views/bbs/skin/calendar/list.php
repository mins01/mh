<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count

?>

<nav class="text-right">
	게시물 : <?=$count?> (<?=$max_page?> page)
	<a href="?lm=calendar" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-calendar"></span>달력형</a>
	<a target="_blank" href="<?=html_escape($bbs_conf['rss_url'])?>" type="button" class="btn btn-link btn-xs"><span class="glyphicon bbs_feed_icon_14x14"></span>RSS</a>
</nav>
<div class="panel panel-default bbs-mode-list">
	<!-- Default panel contents -->
	<div class="panel-heading ">
		<div class="row visible-lg-block">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<? include(dirname(__FILE__).'/inc_search.php'); ?>
			</div>
			<div class="col-lg-2 col-sm-2 text-right">
				<? if($permission['write']): ?>
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 작성</a>
				<? endif; ?>
			</div>

		</div>
	</div>
	<div class="table-responsive">
		<table class="table table-condensed bbs-list-table" style="table-layout:fixed">
			<tr >
				<th class="text-center hidden-xs" width="80">No</th>
				<th class="text-center">제목</th>
				<th class="text-center" width="80">작성자</th>
				<th class="text-center"  width="80">날짜</th>
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
				<td class="text-center text-overflow-ellipsis"><?=html_escape($b_row['b_name'])?></td>
				<?
				$t = $b_row['b_date_st']!=$b_row['b_date_ed']?'b_date_st_ed':'b_date';
				?>
				<td class="text-center <?=$t?>"><?
				echo '<span class="b_date_st">',html_escape(bbs_date_former('y-m-d',$b_row['b_date_st'])),'</span>';
				echo '<span class="b_date_ed">',html_escape(bbs_date_former('m-d',$b_row['b_date_ed'])),'</span>';
				?></td>
				<td class="text-center hidden-xs hidden-sm"><small><?=html_escape($b_row['bh_cnt'])?></small></td>
			</tr>
		<? endforeach; ?>
		<? foreach($b_rows as $b_row):
		//print_r($r);
		?>
			<tr class="bbs-dpeth bbs-dpeth-<?=$b_row['depth']?> <?=$b_idx==$b_row['b_idx']?'warning':''?> ">
				<td class="text-center hidden-xs"><?=$start_num--?></td>
				<td class="bbs-title text-overflow-ellipsis floating_label_parent">
					<? if(isset($b_row['b_category'])): ?><span class="label label-primary"><?=html_escape($b_row['b_category'])?></span><? endif; ?>
					<a href="<?=html_escape($b_row['read_url'])?>" ><?=html_escape($b_row['b_title'])?></a>

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
				<td class="text-center text-overflow-ellipsis"><?=html_escape($b_row['b_name'])?></td>
				<?
				$t = $b_row['b_date_st']!=$b_row['b_date_ed']?'b_date_st_ed':'b_date';
				?>
				<td class="text-center <?=$t?>"><?
				echo '<span class="b_date_st">',html_escape(bbs_date_former('y-m-d',$b_row['b_date_st'])),'</span>';
				echo '<span class="b_date_ed">',html_escape(bbs_date_former('m-d',$b_row['b_date_ed'])),'</span>';
				?></td>
				<td class="text-center hidden-xs hidden-sm"><small><?=html_escape($b_row['bh_cnt'])?></small></td>
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
				<? include(dirname(__FILE__).'/inc_search.php'); ?>
			</div>
			<div class="col-lg-2 col-sm-2 text-right">
				<? if($permission['write']): ?>
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 작성</a>
				<? endif; ?>
			</div>
		</div>
	</div>
</div>
