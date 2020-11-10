<?
// $keyword
// $search_totals
// $managedKeyword
// $keywordstool
?>
<h2 class="text-right">키워드 상세정보</h2>
<div>
	<h3>검색</h3>
	<form action="" method="get">
		<div class="input-group">
			<span class="input-group-addon">키워드</span>
			<input type="text" name="keyword" class="form-control" placeholder="키워드" maxlength="20" value="<?=html_escape($keyword)?>">
			<span class="input-group-btn">
				<button class="btn btn-info" type="button">확인</button>
			</span>
		</div>
	</form>
</div>
<div>
	<h3>검색정보</h3>
	<?
	if(isset($search_totals)):
		// print_r($search_totals);
		?>
		<ul>
			<li>블로그: <?=number_format($search_totals['blog'])?></li>
			<li>카페: <?=number_format($search_totals['cafearticle'])?></li>
			<li>지식인: <?=number_format($search_totals['kin'])?></li>
			<li>웹문서: <?=number_format($search_totals['webkr'])?></li>
			<li>쇼핑: <?=number_format($search_totals['shop'])?></li>
		</ul>
		<?
	endif;
	?>
</div>
<div>
	<hr>
	<h4>네이버 검색광고 키워드 정보</h4>
	<?
	if(isset($managedKeyword)):
		?>
		<ul>
			<li>keyword: <?=$managedKeyword['keyword']?></li>
			<li>성인키워드 여부: <?=$managedKeyword['isAdult']?'O':'X'?></li>
			<li>노출 제한 키워드 여부: <?=$managedKeyword['isRestricted']?'O':'X'?></li>
			<li>시즌 키워드 여부: <?=$managedKeyword['isSeason']?'O':'X'?></li>
			<li>판매금지 키워드 여부: <?=$managedKeyword['isSellProhibit']?'O':'X'?></li>
			<li>검색이 적은 키워드 여부: <?=$managedKeyword['isLowSearchVolume']?'O':'X'?></li>
			<li>등록일: <?=$managedKeyword['regTm']?></li>
			<li>수정 : <?=$managedKeyword['editTm']?></li>
			<li>pc 최대 노출 가능한 광고 갯수: <?=$managedKeyword['PCPLMaxDepth']?></li>
		</ul>
		<?
	endif;
	?>
	<hr>
	<h4>네이버 검색광고 연관 키워드 정보 <small>( 30일,4주 기준, 최대 500 연관 키워드 )</small></h4>

	<?
	if(isset($keywordstool['keywordList'])):
		?>
		<table class="table table-bordered table-hover table-striped table-condensed">
			<colgroup>
				<col width="40">
				<col width="160"  style="min-width:100px;max-width:180px;">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup>
			<thead>
				<tr class="success">
					<th class="text-center" rowspan="2">NO</th>
					<th class="text-center" rowspan="2">연관키워드</th>
					<th class="text-center" colspan="3" title="10미만이면 '&lt; 10'으로 표기">합 검색수<sub>/30일</sub></th>
					<!-- <th class="text-center" width="80">합 MO 검색수<sub>/30일</sub></th> -->
					<th class="text-center" colspan="3">평균 클릭수<sub>/4주</sub></th>
					<!-- <th class="text-center" width="80">평균 MO 클릭수<sub>/4주</sub></th> -->
					<th class="text-center" colspan="2">평균 클릭률(%)<sub>/4주</sub></th>
					<!-- <th class="text-center" width="80">평균 MO 클릭률<sub>/4주</sub></th> -->
					<th class="text-center">평균 깊이<sub>/4주</sub></th>
					<th class="text-center" rowspan="2">경쟁지수</th>
				</tr>
				<tr >
					<!-- <th class="text-center" width="40">NO</th>
					<th class="text-center" style="min-width:100px;max-width:180px;">연관키워드</th> -->
					<th class="text-center bg-info">PC</th>
					<th class="text-center bg-warning">MO</th>
					<th class="text-center ">Total</th>
					<th class="text-center bg-info">PC</th>
					<th class="text-center bg-warning">MO</th>
					<th class="text-center ">Total</th>
					<th class="text-center bg-info">PC</th>
					<th class="text-center bg-warning">MO</th>
					<th class="text-center bg-info">PC</th>
					<!-- <th class="text-center" width="80">경쟁지수</th> -->
				</tr>
				<!-- <tr>
					<th>NO</th>
					<th>relKeyword</th>
					<th>monthlyPcQcCnt</th>
					<th>monthlyMobileQcCnt</th>
					<th>monthlyAvePcClkCnt</th>
					<th>monthlyAveMobileClkCnt</th>
					<th>monthlyAvePcCtr</th>
					<th>monthlyAveMobileCtr</th>
					<th>plAvgDepth</th>
					<th>compIdx</th>
				</tr> -->
			</thead>
			<tbody>
				<?
				foreach ($keywordstool['keywordList'] as $k => $r):
					if($k>=500){break;}
					?>
					<tr>
						<td class="text-center"><?=$k+1?></td>
						<td class="text-center bg-success"><a href='?keyword=<?=urlencode(html_escape($r['relKeyword']))?>'><?=html_escape($r['relKeyword'])?></a></td>
						<td class="text-right"><?=$r['monthlyPcQcCnt']?></td>
						<td class="text-right"><?=$r['monthlyMobileQcCnt']?></td>
						<td class="text-right"><?=$r['monthlyTotalQcCnt']?></td>
						<td class="text-right"><?=$r['monthlyAvePcClkCnt']?></td>
						<td class="text-right"><?=$r['monthlyAveMobileClkCnt']?></td>
						<td class="text-right"><?=$r['monthlyAveTotalClkCnt']?></td>
						<td class="text-right"><?=$r['monthlyAvePcCtr']?></td>
						<td class="text-right"><?=$r['monthlyAveMobileCtr']?></td>
						<td class="text-right"><?=$r['plAvgDepth']?></td>
						<td class="text-center"><?=$r['compIdx']?></td>
					</tr>
					<?
				endforeach;
				?>
			</tbody>
		</table>
		<?
	endif;
	?>
</div>
<div>
	네이버 데이터랩쪽 정보
</div>
<div>
</div>
