<?
//print_r($b_rowss);
//echo base_url();
?>
<!-- <h2>최근 글</h2> -->
<div class="row">
<?
//-- 최근 글
	$tm = time();
	foreach($bbs_tbl_b_ids as $v):
	//foreach($b_rowss as $k=>$b_row): 
	$b_id = $v[1];
	$mn_text = $v[3];
	$date_type = $v[2];
	$mn_url = $v[4];
	$k = $b_id;
	$b_rows = isset($b_rowss[$k])?$b_rowss[$k]:array();
	$v_url = base_url($mn_url);
	?>
	<div class="col-sm-6 col-md-4">
		<div class="list-group">
			<a class="list-group-item list-group-item-success" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?> 최근 글</a>
			<? foreach($b_rows as $b_row): 
				$url = base_url($mn_url.'/read/'.$b_row['b_idx']);
			?>
			<a class="list-group-item bbs-flex-box"  href="<?=html_escape($url)?>">
				<span class="bbs-flex-main bbs-flex-main-fullsize text-primary"><?=html_escape($b_row['b_title'])?></span>
				<span class="bbs-flex-sub bbs-flex-sub-right">
					<? if($b_row['b_secret']!='0'):?><span class="b_secret label label-default" title="비밀">S</span><? endif; ?>
					<span class=" label label-info" title="새글"><?
							if($date_type==0){
								echo date('m-d H시',strtotime($b_row['b_insert_date']));
								
							}else{
								if($tm < strtotime($b_row['b_date_st'])){
									echo date('m-d',strtotime($b_row['b_date_st'])).'~';
								}else if($tm > strtotime($b_row['b_date_ed'])){
									echo 'END';
								}else{
									echo '~'.date('m-d',strtotime($b_row['b_date_ed']));
								}
								
							}
						?></span>
				</span>
			</a>
			<? endforeach;?>
			<? if(count($b_rows)==0):?><div class="list-group-item text-center">최근 내용이 없습니다.</div><? endif;?>
		</div>
	</div>
<? endforeach; ?>

<?
//-- 최근 코멘트
	$tm = time();
	foreach($bc_tbl_b_ids as $v):
	//foreach($b_rowss as $k=>$b_row): 
	$b_id = $v[1];
	$mn_text = $v[3];
	$date_type = $v[2];
	$mn_url = $v[4];
	$k = $b_id;
	$bc_rows = isset($bc_rowss[$k])?$bc_rowss[$k]:array();
	$v_url = base_url($mn_url);
	?>
	<div class="col-sm-6 col-md-4">
		<div class="list-group">
			<a class="list-group-item list-group-item-info" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?> 최근 코멘트</a>
			<? foreach($bc_rows as $bc_row): 
				$url = base_url($mn_url.'/read/'.$bc_row['b_idx']);
			?>
			<a href="<?=html_escape($url)?>" class="list-group-item bbs-flex-box">
				<span class="bbs-flex-main bbs-flex-main-fullsize text-primary" ><?=html_escape($bc_row['bc_comment'])?></span>
				<span class="bbs-flex-sub bbs-flex-sub-right">
					<? if($bc_row['b_secret']!='0'):?><span class="b_secret label label-default" title="비밀">S</span><? endif; ?>
					<span class=" label label-info"><? echo date('m-d H시',strtotime($bc_row['bc_insert_date'])); ?></span>
				</span>
			</a>
			<? endforeach;?>
			<? if(count($bc_rows)==0):?><div class="list-group-item text-center">최근 내용이 없습니다.</div><? endif;?>
		</div>
	</div>
<? endforeach; ?>

<?
//-- 최근 태그
	$tm = time();
	foreach($bt_rowss as $v):
	//foreach($b_rowss as $k=>$b_row): 
	$b_id = $v[1];
	$mn_text = $v[3];
	$date_type = $v[2];
	$mn_url = $v[4];
	$k = $b_id;
	$bt_rows = $v[5];
	$v_url = base_url($mn_url);
	?>
	<div class="col-sm-6 col-md-4">
		<div class="list-group">
			<a class="list-group-item list-group-item-warning" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?> 최근 태그</a>
			<? foreach($bt_rows as $bt_row): 
				$url = base_url($mn_url).'/list?tag='.urlencode($bt_row['bt_tag']);
			?>
			<a href="<?=html_escape($url)?>" class="list-group-item bbs-flex-box">
				<span class="bbs-flex-main bbs-flex-main-fullsize text-primary" ><?=html_escape($bt_row['bt_tag'])?></span>
				<span class="bbs-flex-sub bbs-flex-sub-right">
					<span class=" label label-warning" title=""><?=html_escape($bt_row['cnt'])?></span>
					<span class=" label label-info"><? echo date('m-d H시',strtotime($bt_row['bt_update_date'])); ?></span>
				</span>
				
			</a>
			<? endforeach;?>
			<? if(count($bt_rows)==0):?><div class="list-group-item text-center">최근 내용이 없습니다.</div><? endif;?>
		</div>
	</div>
<? endforeach; ?>
</div>