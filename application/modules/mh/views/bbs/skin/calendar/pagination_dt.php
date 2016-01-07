<?
//$dt,$def_url
//$count_rowss
$v_time_dt = strtotime($dt);
$v_Y0 = (int)date('Y',$v_time_dt);
$v_n0 = (int)date('n',$v_time_dt);
$v_time_st = mktime(0,0,0,$v_n0-6,1,$v_Y0);
$v_time_ed = mktime(0,0,0,$v_n0+6,1,$v_Y0);
?>

<div class="pagination-dt text-center">
	<div>
		<?
		$v_tm = mktime(0,0,0,$v_n0,1,$v_Y0-1);
		$v_Y = (int)date('Y',$v_tm);
		$v_n = (int)date('n',$v_tm);
		$v_yyyymm = date('Y-m',$v_tm);
		$v_cnt = isset($count_rowss[$v_yyyymm])?$count_rowss[$v_yyyymm]:0;
		$v_m = date('m',$v_tm);
		$v_dt = date('Y-m-01',$v_tm);
		$btn_color='btn-default';
		if($v_n==$v_n0 && $v_Y==$v_Y0){
			$btn_color='btn-info';
		}
		$url = str_replace('{{dt}}',$v_dt,$def_url);
		$v_tm =  mktime(0,0,0,$v_n+1,1,$v_Y);
		?>
		<button type="button" onclick="window.open('<?=$url?>','_self');" class="Ym btn <?=$btn_color?> plotting_label_parent">
			<div class="plotting_label text-right">
				<? $t = $v_Y%2?'label-success':'label-warning'; ?>
				<span class="Ym-label-Y label <?=$t?>"><?=$v_Y?>년</span>
				<? if($v_cnt>0): ?>
				<br>
				<span class="Ym-label-cnt label label-default"><?=$v_cnt?>건</span>
				<? endif; ?>
			</div>
			<span class="Ym-label-m2">1년전</span>
		</button>
		<?
		$v_tm = time();
		$v_Y = (int)date('Y',$v_tm);
		$v_n = (int)date('n',$v_tm);
		$v_yyyymm = date('Y-m',$v_tm);
		$v_cnt = isset($count_rowss[$v_yyyymm])?$count_rowss[$v_yyyymm]:0;
		$v_m = date('m',$v_tm);
		$v_dt = date('Y-m-01',$v_tm);
		$btn_color='btn-default';
		if($v_n==$v_n0 && $v_Y==$v_Y0){
			$btn_color='btn-info';
		}
		$url = str_replace('&&','',str_replace('dt={{dt}}','',$def_url));
		$v_tm =  mktime(0,0,0,$v_n+1,1,$v_Y);
		?>
		<button type="button" onclick="window.open('<?=$url?>','_self');" class="Ym btn <?=$btn_color?> plotting_label_parent">
			<div class="plotting_label text-right">
				<? $t = $v_Y%2?'label-success':'label-warning'; ?>
				<span class="Ym-label-Y label <?=$t?>"><?=$v_Y?>년</span><? 
				if($v_cnt>0): 
				?><br>
				<span class="Ym-label-cnt label label-default"><?=$v_cnt?>건</span>
				<? endif; ?>
			</div>
			<span class="Ym-label-m2">오늘</span>
		</button>
		
		<?
		$v_tm = mktime(0,0,0,$v_n0,1,$v_Y0+1);
		$v_Y = (int)date('Y',$v_tm);
		$v_n = (int)date('n',$v_tm);
		$v_yyyymm = date('Y-m',$v_tm);
		$v_cnt = isset($count_rowss[$v_yyyymm])?$count_rowss[$v_yyyymm]:0;
		$v_m = date('m',$v_tm);
		$v_dt = date('Y-m-01',$v_tm);
		$btn_color='btn-default';
		if($v_n==$v_n0 && $v_Y==$v_Y0){
			$btn_color='btn-info';
		}
		$url = str_replace('{{dt}}',$v_dt,$def_url);
		$v_tm =  mktime(0,0,0,$v_n+1,1,$v_Y);
		?>
		<button type="button" onclick="window.open('<?=$url?>','_self');" class="Ym btn <?=$btn_color?> plotting_label_parent">
			<div class="plotting_label text-right">
				<? $t = $v_Y%2?'label-success':'label-warning'; ?>
				<span class="Ym-label-Y label <?=$t?>"><?=$v_Y?>년</span>
				<? if($v_cnt>0): ?>
				<br>
				<span class="Ym-label-cnt label label-default"><?=$v_cnt?>건</span>
				<? endif; ?>
			</div>
			<span class="Ym-label-m2">1년후</span>
		</button>
	</div>
	<div>
		<?
		$limit_i = 100;
		$v_tm = $v_time_st;
		while($v_tm<=$v_time_ed && $limit_i--):
				$v_Y = (int)date('Y',$v_tm);
				$v_n = (int)date('n',$v_tm);
				$v_yyyymm = date('Y-m',$v_tm);
				$v_cnt = isset($count_rowss[$v_yyyymm])?$count_rowss[$v_yyyymm]:0;
				$v_m = date('m',$v_tm);
				$v_dt = date('Y-m-01',$v_tm);
				$btn_color='btn-default';
				if($v_n==$v_n0 && $v_Y==$v_Y0){
					$btn_color='btn-info';
				}
				$url = str_replace('{{dt}}',$v_dt,$def_url);
				$v_tm =  mktime(0,0,0,$v_n+1,1,$v_Y);
		?>
		<button type="button" onclick="window.open('<?=$url?>','_self');" class="Ym btn <?=$btn_color?> plotting_label_parent">
			<div class="plotting_label text-right">
				<? $t = $v_Y%2?'label-success':'label-warning'; ?>
				<span class="Ym-label-Y label <?=$t?>"><?=$v_Y?>년</span>
				<? if($v_cnt>0): ?>
				<br>
				<span class="Ym-label-cnt label label-default"><?=$v_cnt?>건</span>
				<? endif; ?>
			</div>
			<span class="Ym-label-m"><?=$v_n?></span><span  class="Ym-label-m-unit">월</span>
		</button>
		<?
		endwhile;
		?>
	</div>
	
</div>