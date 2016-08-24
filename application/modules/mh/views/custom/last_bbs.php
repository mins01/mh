<?
//print_r($b_rowss);
//echo base_url();
?>
<h2>최근 글</h2>
<div class="row">
<?
	$tm = time();
	foreach($bbs_tbl_b_ids as $v):
	//foreach($b_rowss as $k=>$b_row): 
	$b_id = $v[1];
	$mn_text = $v[3];
	$date_type = $v[2];
	$mn_url = $v[4];
	$k = $b_id;
	$b_row = isset($b_rowss[$k])?$b_rowss[$k]:array();
	$v_url = base_url($mn_url);
	?>
	<div class="col-sm-6 col-md-4">
		<ul class="list-group">
			<a class="list-group-item list-group-item-success" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?></a>
			<? foreach($b_row as $b_row): 
				$url = base_url($mn_url.'/read/'.$b_row['b_idx']);
			?>
			<li class="list-group-item text-overflow-ellipsis floating_label_parent">
				<a href="<?=html_escape($url)?>"><?=html_escape($b_row['b_title'])?></a>
				<div class="floating_label">
					<span class=" label label-info" title="새글"><?
							if($date_type==0){
								echo date('m-d H시',strtotime($b_row['b_insert_date']));
								
							}else{
								if($tm < strtotime($b_row['b_etc_0'])){
									echo date('m-d',strtotime($b_row['b_etc_0'])).'~';
								}else if($tm > strtotime($b_row['b_etc_1'])){
									echo 'END';
								}else{
									echo '~'.date('m-d',strtotime($b_row['b_etc_1']));
								}
								
							}
						?></span>
				</div>
			</li>
			<? endforeach;?>
		</ul>
	</div>
<? endforeach; ?>
</div>
<h2>최근 리플</h2>
<div class="row">
<?
	$tm = time();
	foreach($bc_tbl_b_ids as $v):
	//foreach($b_rowss as $k=>$b_row): 
	$b_id = $v[1];
	$mn_text = $v[3];
	$date_type = $v[2];
	$mn_url = $v[4];
	$k = $b_id;
	$bc_row = isset($bc_rowss[$k])?$bc_rowss[$k]:array();
	$v_url = base_url($mn_url);
	?>
	<div class="col-sm-6 col-md-4">
		<ul class="list-group">
			<a class="list-group-item list-group-item-info" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?></a>
			<? foreach($bc_row as $bc_row): 
				$url = base_url($mn_url.'/read/'.$bc_row['b_idx']);
			?>
			<li class="list-group-item text-overflow-ellipsis floating_label_parent">
				<a href="<?=html_escape($url)?>"><?=html_escape($bc_row['bc_comment'])?></a>
				<div class="floating_label">
					<span class=" label label-info" title="새글"><?
							echo date('m-d H시',strtotime($bc_row['bc_insert_date']));
						?></span>
				</div>
			</li>
			<? endforeach;?>
		</ul>
	</div>
<? endforeach; ?>
</div>