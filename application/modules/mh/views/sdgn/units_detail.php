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
					<div class="col-sm-6 unit_skill unit_skill-4">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skill4_img'])?>">
						<div class="unit_skill_name"><?=html_escape($su_row['unit_skill4'])?> (변신후)</div>
						<div class="unit_skill_desc"><?=html_escape($su_row['unit_skill4_desc'])?></div>
					</div>
					<? endif; ?>
				</div>
			</dd>
		</dl>
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>무기</dt>
			<dd>
				<div class="row">
					<div class="col-sm-4 unit_weapon unit_weapon-1">
						<img src="<?=html_escape($su_row['unit_weapon1_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon1'])?></div>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-2">
						<img src="<?=html_escape($su_row['unit_weapon2_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon2'])?></div>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-3">
						<img src="<?=html_escape($su_row['unit_weapon3_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon3'])?></div>
					</div>
				</div>
			</dd>
			<? if($su_row['unit_is_transform']): ?>
			<dt>무기 (변신 후)</dt>
			<dd>
				<div class="row">
					<div class="col-sm-4 unit_weapon unit_weapon-4">
						<img src="<?=html_escape($su_row['unit_weapon4_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon4'])?></div>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-5">
						<img src="<?=html_escape($su_row['unit_weapon5_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon5'])?></div>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-6">
						<img src="<?=html_escape($su_row['unit_weapon6_img'])?>">
						<div class="unit_weapon_name"><?=html_escape($su_row['unit_weapon6'])?></div>
					</div>
				</div>
			</dd>
			<? endif; ?>
		</dl>
	</li>
	<li class="list-group-item text-right">
		<a class="btn btn-info btn-sm" href="./units">목록</a>
	</li>
</ul>
<?=$html_comment?>