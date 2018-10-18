<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
//print_r($b_rows);

//$b_rowss
//$b_rowss['maxlength']
//print_r($b_rowss);
$v_time_st = $time_st;
$v_date_st = $date_st;
$v_time_ed = $time_ed;
$v_date_ed = $date_ed;

$today_date = date('Y-m-d');

?>
<!--
<?=$v_date_st?> ~ <?=$v_date_ed?>
-->
<div class="text-right">
	<a href="?lm=list" type="button" class="btn btn-link btn-xs"><span class="glyphicon glyphicon-list"></span>목록형</a>
</div>
<div class="panel panel-default bbs-mode-list">
	
	<!-- Default panel contents -->
	<div class="panel-heading">
		<nav class="text-right">
			일정 : <?=$count?>
		</nav>
	</div>
	<div class="panel-body">
		<?=$pagination_dt?>
	</div>
	<? 
	if(count($b_n_rows)>0): 
	?>

		<table class="table table-condensed" style="table-layout:fixed">
			<tr >
				<th class="text-center hidden-xs" width="80">No</th>
				<th class="text-center">제목</th>
				<th class="text-center" width="80">작성자</th>
				<th class="text-center hidden-xs hidden-sm"  width="120">등록</th>
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
					<td class="text-center"><?=html_escape($b_row['b_name'])?></td>
					<td class="text-center hidden-xs hidden-sm"><?=html_escape(date('m/d H:i',strtotime($b_row['b_insert_date'])))?></td>
					<td class="text-center hidden-xs hidden-sm"><?=html_escape($b_row['bh_cnt'])?></td>

				</tr>
			<? endforeach; ?>
		</table>

	<? 
	endif;
	?>
	<div class="">
		<table class="table  table-condensed  table-striped table-calender" style="table-layout:fixed">
			<colgroup>
			
				<!-- <col style="width:auto"> -->
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<col style="width:14.285%;width:calc(100%/7)">
				<!-- <col style="width:auto"> -->
			</colgroup>
			<thead>
				<tr>
					<!-- <th class="day-hide day-th day-th-w-hide">-</th> -->
					<th class="day-th day-th-w-0"><div class="day-th-label">일</div></th>
					<th class="day-th day-th-w-1"><div class="day-th-label">월</div></th>
					<th class="day-th day-th-w-2"><div class="day-th-label">화</div></th>
					<th class="day-th day-th-w-3"><div class="day-th-label">수</div></th>
					<th class="day-th day-th-w-4"><div class="day-th-label">목</div></th>
					<th class="day-th day-th-w-5"><div class="day-th-label">금</div></th>
					<th class="day-th day-th-w-6"><div class="day-th-label">토</div></th>
					<!-- <th class="day-hide day-th day-th-w-hide">-</th> -->
				</tr>
			</thead>
			<tbody>
				<? 
				$limit_i=100;
				$c_time = $v_time_st;
				while($c_time < $v_time_ed && $limit_i--):
				?>
				<tr>
					<!-- <td class="day-hide day-w-hide"></td> -->
					<?
					
					$tmp_get = $get;
					for($i=0,$m=7;$i<$m;$i++):
						
						$c_date = date('Y-m-d',$c_time);
						$c_m = date('m',$c_time);
						$c_date_label = date('n.j',$c_time);
						
						$tmp_get['dt']=$c_date;
						$write_url = $base_url.'/write?'.http_build_query($tmp_get);
						$cl_today = $today_date==$c_date?'day-td-today':'';
						
						$c_time+=86400;
					?>
					<td class="day-td day-w-<?=$i?> day-m-<?=$c_m?> <?=$cl_today?>" data-date="<?=$c_date?>">
						<div class="day">
							<div class=" day-bg" data-day-bg-height="<?=$b_rowss['maxlength']?>"></div>
							<? if($permission['write']): ?>
							<a class="day-label" href="<?=html_escape($write_url)?>"><?=$c_date_label?></a>
							<? else:?>
							<div class="day-label"><?=$c_date_label?></div>
							<? endif;?>
							<? 
							
							if(isset($b_rowss[$c_date])):
								foreach($b_rowss[$c_date] as $k=>$plan): 
								$b_row=$plan['b_row'];
								$tmp_class = '';
								if($b_row['b_idx']==$b_idx){
									$tmp_class.=' plan-this';
								}
								$label = $b_row['b_title'];
							?>
								<div class="plan <?=$tmp_class?> floating_label_parent" 
								data-b_idx="<?=$b_row['b_idx']?>"
								data-plan-len="<?=$plan['len']?>"
								data-plan-order="<?=$plan['order']?>"
								>
								<a href="<?=html_escape($b_row['read_url'])?>">
								<? if(isset($b_row['b_category'])): ?><span class="hidden-xs label label-primary b_category"><?=html_escape($b_row['b_category'])?></span><? endif; ?>
								<?=html_escape($label)?></a>
								
								<div class="floating_label hidden-xs">
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
								
								</div>
							<? 
								endforeach;
							endif; 
							?>
						</div>
					</td>
					<? 
					
					endfor;
					?>
					<!-- <td class="day-hide day-w-hide"></td> -->
				</tr>
				<? 
				endwhile;
				?>
			</tbody>
			
		</table>
	</div>
	<div class="panel-footer">
		<div class="row">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<? include(dirname(__FILE__).'/inc_search.php'); ?>
			</div>
		</div>
	</div>

</div>
<script>
$(function(){
	$('.table-calender').on('mouseover','.plan',function(){
		var b_idx = $(this).attr('data-b_idx');
			$('.plan[data-b_idx="'+b_idx+'"]').addClass('plan-on');
		}).on('mouseout','.plan',function(){
		var b_idx = $(this).attr('data-b_idx');
			$('.plan[data-b_idx="'+b_idx+'"]').removeClass('plan-on');
		});
	$('.table-calender').on('dblclick','.day-bg',function(){
		var href=$(this).parents('td.day-td').find('a.day-label').prop('href');
		if(href) window.open(href,'_self');
		
	});
})
</script>

