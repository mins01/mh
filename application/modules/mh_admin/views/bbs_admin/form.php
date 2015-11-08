<?
//$bm_row,$b_row
//$start_num,$count

?>
<div >
	<div >
		<form action="" name="form_bbs" method="post" onsubmit="return check_form_bbs(this);"  >
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
									<input type="text" required <?=isset($bm_row[$t_col][0])?'readonly':''?> class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
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
									<input type="text" required <?=isset($bm_row[$t_col][0])?'readonly':''?> class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
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
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="bm_open" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="bm_open" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
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
									<input type="text" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
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
									$t_label='카테고리<br>사용여부';
									$t_col = 'bm_use_category';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="bm_open" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="bm_open" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
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
									<input type="text" required class="form-control" required name="<?=html_escape($t_col)?>" aria-label="<?=html_escape($t_label)?>" placeholder="<?=html_escape($t_label)?>" style="min-width:80px" maxlength="40" value="<?=html_escape($bm_row[$t_col])?>">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group">
								<? 
									$t_label='비밀글<br>사용여부';
									$t_col = 'bm_use_secret';
								?>
								<label class="col-xs-3 control-label"><?=($t_label)?></label>
								<div class="col-xs-9">
									<div class="btn-group" data-toggle="buttons">
										<label class="btn btn-success  <?=$bm_row[$t_col]=='1'?'active':''?>"><input type="radio" name="bm_open" value="1" autocomplete="off" <?=$bm_row[$t_col]=='1'?'checked':''?>>사용
										</label>
										<label class="btn btn-warning <?=!$bm_row[$t_col]?'active':''?>"><input type="radio" name="bm_open" value="0" autocomplete="off" <?=!$bm_row[$t_col]?'checked':''?>>금지
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
				</li>
			</ul>
			<div class="panel-footer text-right">
			<button type="submit" class="btn btn-primary glyphicon glyphicon-ok"> 확인</button>
			<button type="button" onclick="history.back()" class="btn btn-danger glyphicon glyphicon-remove"> 취소</button>
			</div>
		</div>
		</form>
	</div>
</div>






