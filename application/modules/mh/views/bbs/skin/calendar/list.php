<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
//print_r($b_rows);

//$b_rowss
//$b_rowss['maxlength']

$v_time_st = $time_st;
$v_date_st = $date_st;
$v_time_ed = $time_ed;
$v_date_ed = $date_ed;



?>
<?=$v_date_st?> ~ <?=$v_date_ed?>

<div class="panel panel-default bbs-mode-list">

	<!-- Default panel contents -->
	<div class="panel-heading">
		<nav class="text-right">
			일정 : <?=$count?>
		</nav>
	</div>
	<div class="table-responsive">
		<table class="table  table-condensed  table-striped table-calender" style="table-layout:fixed" data-b_rowss_maxlength="<?=$b_rowss['maxlength']?>">
			<colgroup>
				<col style="auto">
				<col style="width:14%">
				<col style="width:14%">
				<col style="width:14%">
				<col style="width:14%">
				<col style="width:14%">
				<col style="width:14%">
				<col style="width:14%">
				<col style="auto">
			</colgroup>
			<thead>
				<tr>
					<th class="day-hide day-th day-th-w-hide">-</th>
					<th class="day-th day-th-w-0">일</th>
					<th class="day-th day-th-w-1">월</th>
					<th class="day-th day-th-w-2">화</th>
					<th class="day-th day-th-w-3">수</th>
					<th class="day-th day-th-w-4">목</th>
					<th class="day-th day-th-w-5">금</th>
					<th class="day-th day-th-w-6">토</th>
					<th class="day-hide day-th day-th-w-hide">-</th>
				</tr>
			</thead>
			<tbody>
				<? 
				$limit_i=100;
				$c_time = $v_time_st;
				while($c_time < $v_time_ed && $limit_i--):
				?>
				<tr>
					<td class="day-hide day-w-hide"></td>
					<?
					for($i=0,$m=7;$i<$m;$i++):
						$c_date = date('Y-m-d',$c_time);
						$c_m = date('m',$c_time);
						$c_date_label = date('m-d',$c_time);
						$c_time+=86400;
					?>
					<td class="day-td day-w-<?=$i?> day-m-<?=$c_m?>" data-date="<?=$c_date?>">
						<div class="day">
							<div class=" day-bg"></div>
							<div class="day-label"><?=$c_date_label?></div>
							<? 
							if(isset($b_rowss[$c_date])):
								foreach($b_rowss[$c_date] as $plan): 
								$b_row=$plan['b_row'];
							?>
								<div class="plan" 
								data-b_idx="<?=$b_row['b_idx']?>"
								data-plan-len="<?=$plan['len']?>"
								data-plan-order="<?=$plan['order']?>"
								><a href="<?=html_escape($b_row['read_url'])?>"><?=html_escape($b_row['b_title'])?></a></div>
							<? 
								endforeach;
							endif; 
							?>
						</div>
					</td>
					<? 
					
					endfor;
					?>
					<td class="day-hide day-w-hide"></td>
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
<script>
$(function(){
	$('.table-calender').on('mouseover','.plan',function(){
		var b_idx = $(this).attr('data-b_idx');
			$('.plan[data-b_idx="'+b_idx+'"]').addClass('plan-on');
		}).on('mouseout','.plan',function(){
		var b_idx = $(this).attr('data-b_idx');
			$('.plan[data-b_idx="'+b_idx+'"]').removeClass('plan-on');
		});
})
</script>

