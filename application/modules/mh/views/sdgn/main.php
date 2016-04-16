<?
// bc_rows , su_rows, units_cards
?>




<h3 class="text-center" >
	건넥한마디
</h3>
<div class="row">
	<div class="col-sm-6">
		<div class="list-group">
			<div class="list-group-item text-center">
				<h4 class="text-center" ><a href="http://sdgn.co.kr/" target="_blank"><!--<img src="http://static.sdgn.co.kr/next/images/common/logo.png">--><img style="max-width:100%" src="http://static.sdgn.co.kr/next/images/intro/160205/logo.png"><br>공식게임사이트 방문하기</a></h4>
			</div>
			<div class="list-group-item text-center">
				<div class="text-center" style="margin:1em; auto; max-width:100%"><a class="btn btn-default btn-lg" href="https://play.google.com/store/apps/details?id=com.mins01.app001" target="_blank"><img style="max-width:100%" src="https://www.gstatic.com/android/market_images/web/play_one_bar_logo.png"><br><img style="width:32px;max-width:100%" src="https://lh3.googleusercontent.com/y-Q8e1HEOIDP6Je5mwMp2D-_cbWWa8E99tRf1V5QQg01_thpRcy3Qhv2X8eVrTYA3g=w300"> 건넥 한마디</a></div>
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="list-group">
			<a href="/sdgn/plan" class="list-group-item active text-center" title="일정페이지로 바로가기"><strong>일정</strong> [<small class=""><?=html_escape($plan_dt_st)?> ~ <?=html_escape($plan_dt_ed)?></small>]</a>
			<? 
			foreach($plan_b_rows as $b_row): 
			$t = ($b_row['b_etc_0']<=DATE_YMD && DATE_YMD<=$b_row['b_etc_1']);
			$t1 = $t?'list-group-item-info':'';
			?>
			<a href="/sdgn/plan/read/<?=$b_row['b_idx']?>" class="list-group-item text-overflow-ellipsis <?=$t1?>">
				<? if($t): ?>
				<span class="badge">진행중</span>
				<? endif; ?>
				<?
				if($b_row['b_etc_0']==$b_row['b_etc_1']):
				?>
				<span class="label label-success"><?=html_escape($b_row['b_etc_0'])?></span>
				<? 
				else:
				?>
				<span class="label label-success"><?=html_escape($b_row['b_etc_0'])?> ~ <?=html_escape($b_row['b_etc_1'])?></span>
				<?
				endif;
				?>
				<?
				if(isset($b_row['b_category'][0])):
				?>
				<span class="label label-info"><?=html_escape($b_row['b_category'])?></span>
				<?
				endif;
				?>
				<?=html_escape($b_row['b_title'])?>
			</a>
			<? endforeach; ?>
		</div>
	</div>
</div>



<hr>
<div class="row">
	<? /* 
	<div class="col-sm-4">
		<ul class="list-group">
			<li class="list-group-item active text-center"><strong>한마디 TOP 10</strong></li>
			<? 
			$i_cnt = 1;
			foreach($bc_rows as $bc_row): 
			?>
			<li class="list-group-item">
				<strong><?=$i_cnt++?></strong>. <?=html_escape($bc_row['bc_name'])?>
				<span class="badge"><?=$bc_row['cnt']?> 마디</span>
			</li>
			<? endforeach; ?>
			
		</ul>
	</div>
	*/ ?>
	<div class="col-sm-4">
		<div class="list-group">
			<div class="list-group-item active text-center"><strong>최근 무기 수정</strong></div>
			<? 
			$i_cnt = 1;
			foreach($sw_rows as $sw_row): 
			?>
			<a href="/sdgn/units?unit_idx=<?=$sw_row['unit_idx']?>"  class="list-group-item text-overflow-ellipsis">
				<span class="label  unit_properties_num unit_properties_num-<?=$sw_row['unit_properties_num']?>"><?=$sw_row['unit_name']?></span> : 
				<span class="label label-default"><?=html_escape($sw_row['sw_name'])?></span>
				 
				<small>by <?=html_escape($sw_row['m_nick'])?></small>
			</a>
			<? endforeach; ?>
			
		</div>
	</div>
	<div class="col-sm-4">
		<div class="list-group">
			<a href="/sdgn/last_comments" class="list-group-item active text-center"><strong>최근 한마디</strong></a>
			<? 
			$i_cnt = 1;
			foreach($last_bc_rows as $last_bc_row): 
			?>
		<a href="/sdgn/units?unit_idx=<?=$last_bc_row['unit_idx']?>#cmt_<?=$last_bc_row['bc_idx']?>"  class="list-group-item text-overflow-ellipsis">
				<span class="label  unit_properties_num unit_properties_num-<?=$last_bc_row['unit_properties_num']?>"><?=$last_bc_row['unit_name']?></span>
				<?=html_escape($last_bc_row['bc_comment'])?>
				
			</a>
			<? endforeach; ?>
			
		</div>
	</div>
	<div class="col-sm-4">
		<div class="list-group">
			<a href="/sdgn/last_comments" class="list-group-item active text-center"><strong>유닛 TOP 10</strong></a>
			<? 
			$i_cnt = 1;
			foreach($su_rows as $su_row): 
			?>
			<a href="/sdgn/units?unit_idx=<?=$su_row['unit_idx']?>" class="list-group-item">
				<strong><?=$i_cnt++?></strong>. <?=html_escape($su_row['unit_name'])?>
				<span class="badge"><?=$su_row['cnt']?> 마디</span>
			</a>
			<? endforeach; ?>
			
		</div>
	</div>
	<div class="text-center">
		<? foreach($units_cards as $units_card): ?>
		<div class="unit_card_box">
			<?=$units_card?>
		</div>
		<? endforeach; ?>
	</div>
	<hr>
	<div class="text-center">
	<small> 
		<div>유닛이나 스킬 등의 이미지 저작권은 <a href="http://sdgn.co.kr/" target="blank">sdgn.co.kr</a>에 문의해주시기 바랍니다. (즉, 저쪽 사이트꺼라는 소리다.)</div>
		<div>이곳은 SD건담넥스트에볼루션 팬사이트일 뿐입니다. 이곳을 사용함으로 발생되는 불이익에 대해서는 책임지지 않습니다. (중요 정보는 공식사이트를 한번 더 체크!)</div>
		<div>즐겁게 게임과 사이트를 즐깁시다. (분쟁 발생 시 어떻게 될지 모릅니다.)</div>

	</small>
	</div>
</div>