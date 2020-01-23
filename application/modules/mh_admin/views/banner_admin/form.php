<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/script.js')?>"></script>

<link href="/web_work/mb_wysiwyg_dom/bootstrap.css?t=<?=REFLESH_TIME?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/mb_wysiwyg.js?t=<?=REFLESH_TIME?>"></script>
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/set.toolbar.js?t=<?=REFLESH_TIME?>"></script>

<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/mh_gps.js')?>"></script>

<script>
//--- 위지윅 생성
$(
function(){
	 $('.pre-wysiwyg').each(function(idx,el){
		 createWysiwygObj(el)
	 })
})
</script>

<h2>배너 관리자</h2>
<div class="banner_admin banner_admin_form">
  <form onsubmit="submitWysiwyg();return check_form_bbs(this);" method="post">
		<input class="form-control" type="hidden" readonly name="process" value="<?=isset($row['bn_idx'][0])?'update':'insert'?>">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width:10em">필드</th>
          <th class="text-center">값</th>
        </tr>
      </thead>
      <tbody>
				<tr>
					<th class="text-center" style="width:10em">bn_idx</th>
					<td><input class="form-control" type="text" readonly name="bn_idx" value="<?=html_escape($row['bn_idx'])?>"></td>
				</tr>
				<tr>
					<th class="text-center">bn_title</th>
					<td><input class="form-control" type="text" name="bn_title" value="<?=html_escape($row['bn_title'])?>"></td>
				</tr>
				<tr>
					<th class="text-center">위치설정</th>
					<td>
						<div class="form-inline">
							<div class="input-group input-group-custom">
								<span class="input-group-addon">베이스노드셀렉터</span>
								<input class="form-control" list="datalist_bn_base_node" required type="text" name="bn_base_node" value="<?=html_escape($row['bn_base_node'])?>">
							</div>
							<datalist id="datalist_bn_base_node">
							  <option value="#banner_pos_top">최상단 기준</option>
								<option value="#banner_pos_bottom">최하단 기준</option>
							  <option value="body">&lt;body&gt; 속</option>
							</datalist>
						</div>
						<hr class="hr-admin">
						<div class="form-inline">
							<div>
									<div class="text-center">
										<div class="input-group input-group-custom">
											<span class="input-group-addon" style="min-width:8em !important">top</span>
											<input class="form-control" placeholder="단위 포함 입력" style="width:8em" list="datalist_bn_top_right_bottom_left" type="text" name="bn_top" value="<?=html_escape($row['bn_top'])?>">
										</div>
									</div>
									<div class="row">
										<div class="col-md-6 text-center">
											<div class="input-group input-group-custom">
												<span class="input-group-addon" style="min-width:8em !important">left</span>
												<input class="form-control" placeholder="단위 포함 입력" style="width:8em" list="datalist_bn_top_right_bottom_left" type="text" name="bn_left" value="<?=html_escape($row['bn_left'])?>">
											</div>
										</div>
										<div class="col-md-6 text-center">
											<div class="input-group input-group-custom">
												<span class="input-group-addon" style="min-width:8em !important">right</span>
												<input class="form-control" placeholder="단위 포함 입력" style="width:8em" list="datalist_bn_top_right_bottom_left" type="text" name="bn_right" value="<?=html_escape($row['bn_right'])?>">
											</div>
										</div>
									</div>
									<div  class="text-center">
										<div class="input-group input-group-custom">
											<span class="input-group-addon" style="min-width:8em !important">bottom</span>
											<input class="form-control" placeholder="단위 포함 입력" style="width:8em" list="datalist_bn_top_right_bottom_left" type="text" name="bn_bottom" value="<?=html_escape($row['bn_bottom'])?>">
										</div>
									</div>
							</div>
							<datalist id="datalist_bn_top_right_bottom_left">
								<option value="0"></option>
								<option value="auto"></option>
								<option value="10px"></option>
								<option value="50px"></option>
								<option value="100px"></option>
								<option value="200px"></option>
							  <option value="300px"></option>
								<option value="10%"></option>
								<option value="20%"></option>
								<option value="30%"></option>
								<option value="40%"></option>
								<option value="50%"></option>
								<option value="100%"></option>
							</datalist>
						</div>
						<hr class="hr-admin">
						<div class="form-inline">
							<div class="input-group input-group-custom">
								<span class="input-group-addon">bn_width</span>
								<input class="form-control" type="text" list="datalist_bn_width_height" name="bn_width" value="<?=html_escape($row['bn_width'])?>">
							</div>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">bn_height</span>
								<input class="form-control" type="text" list="datalist_bn_width_height" name="bn_height" value="<?=html_escape($row['bn_height'])?>">
							</div>
							<datalist id="datalist_bn_width_height">
								<option value="0"></option>
								<option value="auto"></option>
								<option value="10px"></option>
								<option value="50px"></option>
								<option value="100px"></option>
								<option value="200px"></option>
							  <option value="300px"></option>
								<option value="10%"></option>
								<option value="20%"></option>
								<option value="30%"></option>
								<option value="40%"></option>
								<option value="50%"></option>
								<option value="100%"></option>
								<option value="10vw"></option>
								<option value="10vh"></option>
								<option value="50vw"></option>
								<option value="50vh"></option>
							</datalist>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">bn_z_index</span>
								<input class="form-control" type="text" name="bn_z_index" value="<?=html_escape($row['bn_z_index'])?>">
							</div>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">bn_postion</span>
								<select class="form-control" name="bn_postion">
									<option value="static" <?=($row['bn_postion']=='static')?'selected':''?> >일반(static)</option>
									<option value="relative" <?=($row['bn_postion']=='relative')?'selected':''?> >relative</option>
									<option value="absolute" <?=($row['bn_postion']=='absolute')?'selected':''?> >레이어 배너(absolute)</option>
									<option value="fixed" <?=($row['bn_postion']=='fixed')?'selected':''?> >고정 레이어 배너(fixed)</option>
									<option value="sticky" <?=($row['bn_postion']=='sticky')?'selected':''?> >sticky</option>
								</select>
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th class="text-center">사용설정</th>
					<td>
						<div class="form-inline">
							<div class="input-group input-group-custom">
								<span class="input-group-addon">배너사용여부</span>
								<div class="form-control">
									<label class="text-danger">
										<input type="radio" name="bn_isuse" value="0" <?=$row['bn_isuse']=='0'?'checked':''?> >
										금지
									</label> /
									<label class="text-success">
										<input type="radio" name="bn_isuse" value="1" <?=$row['bn_isuse']=='1'?'checked':''?> >
										사용
									</label>
								</div>
							</div>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">해더사용여부</span>
								<div class="form-control">
									<label class="text-danger">
										<input type="radio" name="bn_use_header" value="0" <?=$row['bn_use_header']=='0'?'checked':''?> >
										금지
									</label> /
									<label class="text-success">
										<input type="radio" name="bn_use_header" value="1" <?=$row['bn_use_header']=='1'?'checked':''?> >
										사용
									</label>
								</div>
							</div>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">풋터사용여부</span>
								<div class="form-control">
									<label class="text-danger">
										<input type="radio" name="bn_use_footer" value="0" <?=$row['bn_use_footer']=='0'?'checked':''?> >
										금지
									</label> /
									<label class="text-success">
										<input type="radio" name="bn_use_footer" value="1" <?=$row['bn_use_footer']=='1'?'checked':''?> >
										사용
									</label>
								</div>
							</div>
							<hr class="hr-admin">
							<!-- <div class="input-group input-group-custom">
								<span class="input-group-addon">bn_content_type</span>
								<input class="form-control" type="text" name="bn_content_type" value="<?=html_escape($row['bn_content_type'])?>">
							</div> -->
							<div class="input-group input-group-custom">
								<span class="input-group-addon">시작일시</span>
								<input class="form-control" type="text" name="bn_date_st" value="<?=html_escape($row['bn_date_st'])?>">
							</div>
							<div class="input-group input-group-custom">
								<span class="input-group-addon">마침일시</span>
								<input class="form-control" type="text" name="bn_date_ed" value="<?=html_escape($row['bn_date_ed'])?>">
							</div>
						</div>
					</td>
				</tr>
				<tr>
					<th class="text-center" style="width:10em">
						추가 클래스명
					</th>
					<td>
						<div class="input-group input-group-custom">
							<span class="input-group-addon">추가클래스명</span>
							<input class="form-control" type="text" name="bn_class_name" maxlength="200" list="datalist_bn_class_name" value="<?=html_escape($row['bn_class_name'])?>">
						</div>
						<datalist id="datalist_bn_class_name">
							<option value="mh-banner-content-padding-5px">내용부분에 padding:5px</option>
							<option value="mh-banner-content-padding-0px">내용부분에 padding:0px</option>
						</datalist>
						<div class="text-danger"><small>* 빈칸으로 구분해서 다중 입력 가능</small></div>
					</td>
				</tr>

				<tr>
					<th class="text-center" style="width:10em">
						<label>
							<input type="radio" name="bn_content_type" value="a" <?=$row['bn_content_type']=='a'?'checked':''?>>
							a태그사용
						</label>
					</th>
					<td>
						<div class="input-group input-group-custom">
							<span class="input-group-addon">IMG URL</span>
							<input class="form-control" type="text" name="bn_img_src" value="<?=html_escape($row['bn_img_src'])?>">
						</div>
						<div class="input-group input-group-custom">
							<span class="input-group-addon">A URL</span>
							<input class="form-control" type="text" name="bn_a_href" value="<?=html_escape($row['bn_a_href'])?>">
						</div>
						<div class="input-group input-group-custom">
							<span class="input-group-addon">A target</span>
							<input class="form-control" type="text" list="datalist_bn_a_target" name="bn_a_target" value="<?=html_escape($row['bn_a_target'])?>">
						</div>
						<datalist id="datalist_bn_a_target">
							<option value="_blank">새창</option>
							<option value="_self">현재창</option>
							<option value="_parent">부모창</option>
							<option value="_top">최상위창</option>
						</datalist>
					</td>
				</tr>
				<tr>
					<th class="text-center" style="width:10em">
						<label>
							<input type="radio" name="bn_content_type" value="html" <?=$row['bn_content_type']=='html'?'checked':''?>>
							html사용
						</label>
					</th>
					<td>
						<textarea class="pre-wysiwyg"  name="bn_html"><?=html_escape($row['bn_html'])?></textarea>
					</td>
				</tr>
				<tr>
					<th class="text-center" style="width:10em">기타</th>
					<td>
						<?=html_escape($row['bn_insert_date'])?> /
						<?=html_escape($row['bn_update_date'])?> /
						<!-- <?=html_escape($row['bn_isdel'])?> -->
					</td>
				</tr>

        <tr>
					<th class="text-center" style="width:10em">동작</th>

          <td class="text-right">
            <a href="<?=html_escape($base_url)?>" class="btn btn-info">목록</a>
            <button class="btn btn-success">확인</button>
          </hd>
        </tr>
      </tbody>
    </table>
  </form>
</div>
