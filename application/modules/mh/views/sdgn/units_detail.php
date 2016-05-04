<?
//$su_row
?>
<ul class="list-group">
	<li class="list-group-item  active"><?=html_escape($su_row['unit_name'])?></li>
	<li class="list-group-item">
		<div class="row">
			<div class="col-sm-3 text-center">
				<a href=""><?=$units_card?></a>
			</div>
			<div class="col-sm-9">
				<dl class="">
					<dt>출연작</dt>
					<dd><img src="<?=html_escape($su_row['unit_anime_img'])?>" /><?=html_escape($su_row['unit_anime'])?></dd>
				</dl>
				<dl class="">
					<dt>소개</dt>
					<dd><?=($su_row['unit_txt'])?></dd>
				</dl>
			</div>
		</div>
		
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>속성</dt>
			<dd>
			<span class="unit_rank unit_rank-<?=html_escape($su_row['unit_rank'])?>" ><?=html_escape($su_row['unit_rank'])?></span>랭크 / 
			<span class="label unit_properties_num unit_properties_num-<?=$su_row['unit_properties_num']?>"><?=html_escape($su_row['unit_properties'])?></span> 
			/ <?=html_escape($su_row['unit_movetype'])?> 
			<? if($su_row['unit_is_transform']): ?>
			/ <span class="label label-success unit_is_transform">변신가능</span> 
			<? endif; ?>
			<? if($su_row['unit_is_weapon_change']): ?>
			/ <span class="label label-danger unit_is_weapon_change">웨폰체인지</span> 
			<? endif; ?>
			</dd>
		</dl>
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>스킬</dt>
			<dd>
				<div class="row">
					<div class="col-sm-6 unit_skill unit_skill-1">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill1_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill1'])?></div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill1_desc'])?></div>
					</div>
					<div class="col-sm-6 unit_skill unit_skill-2">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill2_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill2'])?></div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill2_desc'])?></div>
					</div>
				</div>
			</dd>
		</dl>
		<? if($su_row['unit_is_change_skill']==1): ?>
		<dl class="dl-horizontal">
			<dt>스킬 (변신 후)</dt>
			<dd>
				<div class="row">
					<div class="col-sm-6 unit_skill unit_skill-4">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill4_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill4'])?></div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill4_desc'])?></div>
					</div>
					<div class="col-sm-6 unit_skill unit_skill-5">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill5_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill5'])?></div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill5_desc'])?></div>
					</div>
				</div>
			</dd>
		</dl>
		<? endif; ?>
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>필살기</dt>
			<dd>
				<div class="row">
					<div class="col-sm-6 unit_skill unit_skill-3">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill3_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill3'])?></div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill3_desc'])?></div>
					</div>
					<? if($su_row['unit_is_transform']): ?>
					<div class="col-sm-6 unit_skill unit_skill-6">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill6_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill6'])?> (변신후)</div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill6_desc'])?></div>
					</div>
					<? endif; ?>
				</div>
			</dd>
		</dl>
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<!-- 기본 무기 (변신전) -->
			<dt>무기</dt>
			<dd>
				<div class="row">
				<?
				$sw_rows_k = $sw_rows['0']['0'];
				?>
				<? foreach($sw_rows_k as $sw_row): ?>
					<div class="col-sm-4 unit_weapon unit_weapon-<?=$sw_row['sw_is_change']?>-<?=$sw_row['sw_is_transform']?>-<?=$sw_row['sw_sort']?>">
						<?=$sw_row['card']?>
						<div><button class="btn btn-warning btn-sm" onclick="units_detail.load_weapon_info('<?=$sw_row['sw_key']?>');" data-toggle="modal" data-target="#edit_weapon">추가정보입력</button></div>
					</div>
				<? endforeach; ?>
				</div>
			</dd>
			<? if(isset($sw_rows['0']['1'][0])): 
			$sw_rows_k = $sw_rows['0']['1'];
			?>
			<!-- 기본 무기 (변신후) -->
			<dt>무기 (변신 후)</dt>
			<dd>
				<div class="row">
				<? foreach($sw_rows_k as $sw_row): ?>
					<div class="col-sm-4 unit_weapon unit_weapon-<?=$sw_row['sw_is_change']?>-<?=$sw_row['sw_is_transform']?>-<?=$sw_row['sw_sort']?>">
						<?=$sw_row['card']?>
						<div><button class="btn btn-warning btn-sm" onclick="units_detail.load_weapon_info('<?=$sw_row['sw_key']?>');" data-toggle="modal" data-target="#edit_weapon">추가정보입력</button></div>
					</div>
				<? endforeach; ?>
				</div>
			</dd>
			<? endif; ?>
			<? if(isset($sw_rows['0']['2'][0])): 
			$sw_rows_k = $sw_rows['0']['2'];
			?>
			<!-- 기본 무기 (변신후) -->
			<dt>무기 (변신 후 변신)</dt>
			<dd>
				<div class="row">
				<? foreach($sw_rows_k as $sw_row): ?>
					<div class="col-sm-4 unit_weapon unit_weapon-<?=$sw_row['sw_is_change']?>-<?=$sw_row['sw_is_transform']?>-<?=$sw_row['sw_sort']?>">
						<?=$sw_row['card']?>
						<div><button class="btn btn-warning btn-sm" onclick="units_detail.load_weapon_info('<?=$sw_row['sw_key']?>');" data-toggle="modal" data-target="#edit_weapon">추가정보입력</button></div>
					</div>
				<? endforeach; ?>
				</div>
			</dd>
			<? endif; ?>
			
			<? if(isset($sw_rows['1']['0'][0])): 
			$sw_rows_k = $sw_rows['1']['0'];
			?>
			<!-- 추가 무기 (변신전) -->
			<dt>추가 무기</dt>
			<dd>
				<div class="row">
				<? foreach($sw_rows_k as $sw_row): ?>
					<div class="col-sm-4 unit_weapon unit_weapon-<?=$sw_row['sw_is_change']?>-<?=$sw_row['sw_is_transform']?>-<?=$sw_row['sw_sort']?>">
						<?=$sw_row['card']?>
						<div><button class="btn btn-warning btn-sm" onclick="units_detail.load_weapon_info('<?=$sw_row['sw_key']?>');" data-toggle="modal" data-target="#edit_weapon">추가정보입력</button></div>
					</div>
				<? endforeach; ?>
				</div>
			</dd>
			<? endif; ?>
			
			<? if(isset($sw_rows['1']['1'][0])): 
			$sw_rows_k = $sw_rows['1']['1'];
			?>
			<!-- 추가 무기 (변신후) -->
			<dt>추가 무기 (변신후)</dt>
			<dd>
				<div class="row">
				<? foreach($sw_rows_k as $sw_row): ?>
					<div class="col-sm-4 unit_weapon unit_weapon-<?=$sw_row['sw_is_change']?>-<?=$sw_row['sw_is_transform']?>-<?=$sw_row['sw_sort']?>">
						<?=$sw_row['card']?>
						<div><button class="btn btn-warning btn-sm" onclick="units_detail.load_weapon_info('<?=$sw_row['sw_key']?>');" data-toggle="modal" data-target="#edit_weapon">추가정보</button></div>
					</div>
				<? endforeach; ?>
				</div>
			</dd>
			<? endif; ?>
		</dl>
	</li>
	<li class="list-group-item text-right">
		<a class="btn btn-info btn-sm" href="<?=html_escape(isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/sdgn/units')?>">목록</a>
	</li>
</ul>
<?=$html_comment?>

<div class="modal" id="edit_weapon" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
			<form action="" name="form_weapon" onsubmit="units_detail.submit(this);return false;">
				<input type="hidden" class="" name="sw_key" value="">
				<input type="hidden" class="" name="unit_idx" value="<?=$su_row['unit_idx']?>">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">무기 <span style="display:inline-block">[<span class="sw_name"></span>]</span> 추가 정보</h4>
					
				</div>
				<div class="modal-body ">
					<div class="form-inline">
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">코스트</div>
								<input type="number"  class="form-control" name="sw_cost" laceholder="코스트" min="0" max="200"  value="30">
								<div class="input-group-addon">Cost</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">최대공격수</div>
								<select class="form-control" name="sw_atack_count">
										<option value="">선택해주세요</option>
										<option selected value="1">1 (사격,단타)</option>
										<option value="0">0 (특수기)</option>
										<? for($i=2,$m=10;$i<=$m;$i++): ?>
										<option value="<?=$i?>"><?=$i?></option>
										<? endfor; ?>
								</select>
								<div class="input-group-addon">회</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">리로드타입</div>
									<select class="form-control" name="sw_reload_type">
										<option value="">선택해주세요</option>
										<option value="0">무한(기본격투무기)</option>
										<option value="1">에너지(실시간)</option>
										<option value="2">탄창</option>
									</select>
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">잔탄</div>
								<input type="number"  class="form-control" name="sw_bullet_count" laceholder="sw_bullet_count"  value="1">
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">리로드시간</div>
								<input type="number"  class="form-control" name="sw_reload_time" min="0" max="60" laceholder="sw_reload_time" value="5">
								<div class="input-group-addon">초</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">사거리<small><small>(5m이하는 근거리표시)</small></small></div>
								<input type="number"  class="form-control" name="sw_range" laceholder="sw_range" value="50">
								 <div class="input-group-addon">m</div>
							</div>
						</div>
						
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">사거리타입</div>
								<select class="form-control" name="sw_range_type">
										<option value="">선택해주세요</option>
										<option value="근거리">근거리</option>
										<option value="중거리">중거리</option>
										<option value="원거리">원거리</option>
										<option value="기타">기타</option>
									</select>
							</div>
						</div>
					</div>
					<div>
						<div class="form-group">
							<div class="input-group">
								<div class="input-group-addon">부가효과<small><small>(다운,슬로우 등)</small></small></div>
								<input type="text" list="data_list_sw_effect" style="min-width:8em" class="form-control" name="sw_effect" laceholder="sw_effect" value="없음">
							</div>
							<datalist id="data_list_sw_effect">
									<option value="없음">
									<option value="다운">
									<option value="경직">
									<option value="스턴">
									<option value="자신방어">
									<option value="호밍">
									<option value="슬로우">
									<option value="방어력감소">
									<option value="공격력감소">
									
								</datalist>
						</div>
					</div>
					
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-addon">설명</div>
							<textarea class="form-control" name="sw_desc" laceholder="설명" rows="5" cols="5" ></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					
					<button type="button" class="btn btn-default" data-dismiss="modal">닫기</button>
					<button type="submit" class="btn btn-primary" >저장</button>
				</div>
			</form>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<?
//$is_admin = false;
?>
<script>
var units_detail = {
	load_weapon_info:function(sw_key){
		if(!sw_rows[sw_key]){
			alert('존재하지 않는 무기입니다.');
			return false;
		}
		var sw_row = sw_rows[sw_key];
		var f = document.form_weapon;
		f.reset();
		$(f).find('span.sw_name').html(sw_row.sw_name)
		var disabled_false_cnt = 0;
		for(var x in sw_row){
			if(!f[x]){ continue; }
			f[x].value=(sw_row[x]==null)?'':sw_row[x];
			var t = !!(sw_row[x]!=null && sw_row[x].toString().length>0);
			<? if(!$is_admin): ?>
			if(x!='sw_key' && x!='unit_idx') f[x].disabled = t;
			<?  endif; ?>
			if(!t){
				disabled_false_cnt++;
			}
		}
		<? if(!$is_admin): ?>
		$(f).find('button.btn-primary').prop('disabled',!!!disabled_false_cnt)
		<?  endif; ?>
	}
	,submit:function(f){
		var post_date = $(f).serialize();
		var url = '/sdgn/json/update_weapons_add';
		$.post(url,post_date,function(data){
			if(data.msg){
				alert(data.msg);
			}
			
			if(data.is_error){
				
			}else{
				setTimeout(function(){ document.location.reload(true)},0);
			}
			
		},'json').fail(function(){
			alert('ERROR : 통신에러');
		}
		)
	}
}
</script>







