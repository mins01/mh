<?
//print_r($b_rowss);
//echo base_url();
?>
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_SlideList/slideList.css?t=<?=REFLESH_TIME?>">
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_SlideList/SlideList.js?t=<?=REFLESH_TIME?>"></script>

<style>
.slideList{
	width:100%;height:300px;
	font-size:40px;
	/* 알맞게 재선언하자 */
	border: 0px solid #999;
	background-color: #fff;
	padding:10px;
	border-radius: 0.5em;
}

.slideList-item{
	/* 알맞게 재선언하자 */
	border: 3px solid #4e9427;
	background-color: #fff;
	border-radius: 1em;
	text-align: center;
	font-weight: bold;;
	/* text-overflow: ellipsis;
	overflow: hidden;
	white-space: nowrap; */
}
</style>

<!-- <h2>최근 글</h2> -->
<div class="last-bbs-columns last-bbs-columns-3">
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
	if($b_id=='freegame'):
		?>
		<? /* ?>
		<div class="last-bbs-columns-content">
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
		<? //*/ ?>
		<div class="last-bbs-columns-content">
			<div class="list-group">
				<a class="list-group-item list-group-item-success" href="<?=html_escape($v_url)?>"><?=html_escape($mn_text)?> 최근 글</a>
				<div class="list-group-item" style="padding:0;" >
					<div class="slideList slideList-h" id="sl01" style="margin:5px 0px;height:240px">
						<?
						foreach($b_rows as $b_row):
							$url = base_url($mn_url.'/read/'.$b_row['b_idx']);
							// print_r($b_row);
							//$b_row['thumbnail_url']
							$bgstyle = isset($b_row['thumbnail_url'][0])?"background-image:url('{$b_row['thumbnail_url']}');":'';
							?>
							<!-- <div class="slideList-item"> -->
								<a class="slideList-item  " style="<?=html_escape($bgstyle)?>;
								background-size: cover;
								background-position: center center;
								background-repeat: no-repeat;
								/* display: block; */
								"  href="<?=html_escape($url)?>">
								<div class="" style="width:100%; background-color:rgba(255,255,255,0.8); display:block; padding:5px 0;">
									<div class="text-primary" style="font-size:34px"><?=html_escape($b_row['b_title'])?></div>
									<div class="" style="font-size:20px">
										<? if($b_row['b_secret']!='0'):?><span class="b_secret" title="비밀">S</span><? endif; ?>
										<span class="" title="새글"><?
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
									</div>
								</div>
								<!-- <div>
									<img style="max-width:100%;max-height:100%;object-fit:contain" src="<?=$b_row['thumbnail_url']?>">
								</div> -->

								</a>

							<!-- </div> -->
							<?
						endforeach;
						?>
					</div>
					<div style="flex:0 0 40px; display:flex;flex-direction: row;">
						<button class="btn btn-success" onclick="sl01.prev()" style="flex: 1 1 50%; margin:5px;">◀</button>
						<button class="btn btn-success" onclick="sl01.next()" style="flex: 1 1 50%; margin:5px;">▶</button>
					</div>
				</div>
			</div>

		</div>
		<script>
		<!--
		var sl01= null;
		$(function(){
			sl01 = new SlideList(document.querySelector("#sl01"));
			sl01.isRepeat = true;
			var t = document.querySelectorAll("#sl01 .slideList-item")
			// console.log(t);
			if(t && t.length>1){
				sl01.playAuto(5000,0);
			}
		});
		-->
		</script>
		<?
	else:
		?>
		<div class="last-bbs-columns-content">
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
		<?
		endif;
	endforeach;
?>

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
	<div class="last-bbs-columns-content">
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
	<div class="last-bbs-columns-content">
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
