<?
//$bm_row,$b_row
//$start_num,$count

?>
<div >
	<div >
		<form action="" name="form_bbs" method="post" onsubmit="submitWysiwyg();return check_form_bbs(this);"  >
		<input type="hidden" name="process" value="<?=html_escape($process)?>">
		<div class="panel panel-default form-horizontal bbs-mode-form">
			<div class="panel-heading">
				<h3>게시판 설정</h3>
			</div>
			<ul class="list-group">
				<li class="list-group-item  form-horizontal">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='게시판아이디';
									$t_col = 'b_id';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<input type="text" required <?=isset($bm_row['bm_insert_date'][0])?'readonly':''?> class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
									<?php echo form_error('b_id'); ?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='게시판테이블';
									$t_col = 'bm_table';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									
										<? if(isset($bm_row['bm_insert_date'][0])): ?>
											<input type="text" disabled class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
										<? else: ?>
										<div class="input-group">
											<span class="input-group-addon">
												<?=DB_PREFIX.'bbs_'?>
											</span>
											<?=form_dropdown($t_col, $tables, $bm_row[$t_col], ' class="selectpicker form-control show-tick" style="width:4em" data-width="100%" aria-label="'.$t_label.'" title="'.$t_label.'"  data-header="'.$t_label.'"')?>
											<span class="input-group-addon">
												_data
											</span>
										</div><!-- /input-group -->
										<div class="text-danger">주의 : 최초 설정시 변경 불가!</div>
										<? endif;?>
									
								</div>
						</div>
					</div>
					
				</li>
				<li class="list-group-item  form-horizontal">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='게시판제목';
									$t_col = 'bm_title';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<input type="text" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='게시판<br>사용여부';
									$t_col = 'bm_open';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='스킨';
									$t_col = 'bm_skin';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
								<?=form_dropdown($t_col, $skins, $bm_row[$t_col], ' class="selectpicker show-tick" style="width:4em" data-width="120px" aria-label="'.$t_label.'" title="'.$t_label.'"  data-header="'.$t_label.'"')?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='페이징단위';
									$t_col = 'bm_page_limit';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										
										<input type="number" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='목록타입(정렬)';
									$t_col = 'bm_list_type';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
								<?=form_dropdown($t_col, $list_types, $bm_row[$t_col], ' class="selectpicker show-tick" style="width:4em" data-width="120px" aria-label="'.$t_label.'" title="'.$t_label.'"  data-header="'.$t_label.'"')?>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='읽기에서 목록?';
									$t_col = 'bm_read_with_list';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='코멘트?';
									$t_col = 'bm_use_comment';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='썸네일?';
									$t_col = 'bm_use_thumbnail';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='카테고리?';
									$t_col = 'bm_use_category';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='카레고리목록';
									$t_col = 'bm_category';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<input type="text" class="form-control" name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='비밀글?';
									$t_col = 'bm_use_secret';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='새글 기간(초)';
									$t_col = 'bm_new';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<input type="number" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='첨부파일?';
									$t_col = 'bm_use_file';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="<?=html_escape($t_col)?>" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='첨부파일수';
									$t_col = 'bm_file_limit';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<input type="number" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
							</div>
						</div>
					</div>
				</li>
			</ul>
			<div class="panel-footer text-right">
			<? if(isset($bm_row['b_id'][0])): ?>
			<a class="btn btn-info" href="<?=base_url('/bbs/'.$bm_row['b_id'])?>" target="_blank"><span class="glyphicon glyphicon-link"></span> 미리보기</a>
			<? endif; ?>
			
			<button type="submit" class="btn btn-primary"><span class="glyphicon glyphicon-ok"></span> 확인</button>
			<a type="button" href="<?=$bbs_conf['list_url']?>"  class="btn btn-danger"><span class="glyphicon glyphicon-remove"></span> 목록</a>
			</div>
		</div>
		</form>
	</div>
</div>






