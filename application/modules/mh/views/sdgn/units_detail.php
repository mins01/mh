<?
//$su_row
?>

<?
switch($su_row['unit_properties']){
	case '어썰트':$unit_properties_class = 'label-danger';break;
	case '밸런스':$unit_properties_class = 'label-info';break;
	case '슈터':$unit_properties_class = 'label-success';break;
}
?>
<ul class="list-group">
	<li class="list-group-item  active"><?=html_escape($su_row['unit_name'])?></li>
	<li class="list-group-item">
		<div class="row">
			<div class="col-sm-3 text-center">
				<?=$units_card?>
			</div>
			<div class="col-sm-9">
				<dl class="">
					<dt>출연작</dt>
					<dd><img src="<?=html_escape($su_row['unit_anime_img'])?>" /><?=html_escape($su_row['unit_anime'])?></dd>
				</dl>
				<dl class="">
					<dt>소개</dt>
					<dd><?=html_escape($su_row['unit_txt'])?></dd>
				</dl>
			</div>
		</div>
		
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>속성</dt>
			<dd>
			<span class="unit_rank unit_rank-<?=html_escape($su_row['unit_rank'])?>" ><?=html_escape($su_row['unit_rank'])?></span>랭크 / 
			<span class="label label-default <?=$unit_properties_class?>"><?=html_escape($su_row['unit_properties'])?></span> / 
			<?=html_escape($su_row['unit_movetype'])?> 
			
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
						<span><?=html_escape($su_row['unit_weapon1'])?></span>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-2">
						<img src="<?=html_escape($su_row['unit_weapon2_img'])?>">
						<span><?=html_escape($su_row['unit_weapon2'])?></span>
					</div>
					<div class="col-sm-4 unit_weapon unit_weapon-3">
						<img src="<?=html_escape($su_row['unit_weapon3_img'])?>">
						<span><?=html_escape($su_row['unit_weapon3'])?></span>
					</div>
				</div>
			</dd>
		</dl>
	</li>
	<li class="list-group-item">
		<dl class="dl-horizontal">
			<dt>스킬</dt>
			<dd>
				<div class="row">
					<div class="col-sm-4 unit_skil unit_skil-1">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skil1_img'])?>">
						<span><?=html_escape($su_row['unit_skil1'])?></span>
					</div>
					<div class="col-sm-4 unit_skil unit_skil-2">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skil2_img'])?>">
						<span><?=html_escape($su_row['unit_skil2'])?></span>
					</div>
					<div class="col-sm-4 unit_skil unit_skil-3">
						<img class="img-rounded" src="<?=html_escape($su_row['unit_skil3_img'])?>">
						<span><?=html_escape($su_row['unit_skil3'])?></span>
					</div>
				</div>
			</dd>
		</dl>
	</li>
	<li class="list-group-item text-right">
		<a class="btn btn-info btn-sm" href="./units">목록</a>
	</li>
</ul>
<?=$html_comment?>