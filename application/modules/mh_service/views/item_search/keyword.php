<?
// $keyword
// $search_totals
// $managedKeyword
// $keywordstool
// $datalab_search
?>

<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/StickyOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/stickyOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>

<style>
	.sot table{
		background: #fff;
	}
	.sot .sot-left{
		background-color: #efc;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
		vertical-align: middle;
	}
	.sot .sot-top{
		background-color: #eee;
		white-space: nowrap;
		text-overflow: ellipsis;
		overflow: hidden;
		vertical-align: middle;
	}

	.sot .bg-MO{
		color: #369;
	}
	.sot .bg-PC{
		color: #396;
	}
	.sot .bg-TOTAL{
		color: #933;
	}
	.sot .compIdx-낮음{
		color:#3a0
	}
	.sot .compIdx-중간{
		color:#06a
	}
	.sot .compIdx-높음{
		color:#a00
	}
</style>
<script>
	$(function(){
		StickyOnTable.apply(document.querySelector('#sot1'))
		StickyOnTable.apply(document.querySelector('#sot2'))
	})
</script>
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
			<li>검색: <?=number_format($search_totals['search'])?></li>
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
			<li>등록일: <?=isset($managedKeyword['regTm'][0])?$managedKeyword['regTm']:'등록없음'?></li>
			<li>수정일 : <?=isset($managedKeyword['editTm'][0])?$managedKeyword['editTm']:'수정없음'?></li>
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
		<div id="sot1" class="sot" data-sot-top="2" data-sot-left="2"  style="width:100%;height:400px;">
			<table class="table table-bordered table-hover table-striped table-condensed">
				<colgroup>
					<col width="40">
					<col width="160">
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
					<tr>
						<th class="text-center" rowspan="2" >NO</th>
						<th class="text-center" rowspan="2">연관키워드</th>
						<th class="text-center" colspan="3" title="10미만이면 '&lt; 10'으로 표기">합 검색수</th>
						<!-- <th class="text-center" width="80">합 MO 검색수</th> -->
						<th class="text-center" colspan="3">평균 클릭수</th>
						<!-- <th class="text-center" width="80">평균 MO 클릭수</th> -->
						<th class="text-center" colspan="2">평균 클릭률(%)</th>
						<!-- <th class="text-center" width="80">평균 MO 클릭률</th> -->
						<th class="text-center">평균 깊이</th>
						<th class="text-center" rowspan="2">경쟁지수</th>
					</tr>
					<tr >
						<!-- <th class="text-center" width="40">NO</th>
						<th class="text-center" style="min-width:100px;max-width:180px;">연관키워드</th> -->
						<th class="text-center bg-PC">PC</th>
						<th class="text-center bg-MO">MO</th>
						<th class="text-center bg-TOTAL">Total</th>
						<th class="text-center bg-PC">PC</th>
						<th class="text-center bg-MO">MO</th>
						<th class="text-center bg-TOTAL">Total</th>
						<th class="text-center bg-PC">PC</th>
						<th class="text-center bg-MO">MO</th>
						<th class="text-center bg-PC">PC</th>
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
							<td class="text-right bg-PC"><?=$r['monthlyPcQcCnt']?></td>
							<td class="text-right bg-MO"><?=$r['monthlyMobileQcCnt']?></td>
							<td class="text-right bg-TOTAL"><?=$r['monthlyTotalQcCnt']?></td>
							<td class="text-right bg-PC"><?=$r['monthlyAvePcClkCnt']?></td>
							<td class="text-right bg-MO"><?=$r['monthlyAveMobileClkCnt']?></td>
							<td class="text-right bg-TOTAL"><?=$r['monthlyAveTotalClkCnt']?></td>
							<td class="text-right bg-PC"><?=$r['monthlyAvePcCtr']?></td>
							<td class="text-right bg-MO"><?=$r['monthlyAveMobileCtr']?></td>
							<td class="text-right bg-PC"><?=$r['plAvgDepth']?></td>
							<td class="text-center compIdx-<?=$r['compIdx']?>"><?=$r['compIdx']?></td>
						</tr>
						<?
					endforeach;
					?>
				</tbody>
			</table>
		</div>
		<?
	endif;
	?>
</div>
<div>
	<hr>
	<h4>네이버 데이터랩 정보(연관키워드 TOP5 검색률(%))</h4>
	<?
	if(isset($datalab_search)):
		// print_r($datalab_search);exit;
		?>
		<?
			// 차트용 데이터 처리
			// print_r($rowss['users']);
			$chart_title = array('키워드');
			foreach($datalab_search['results'] as $r){
				$chart_title[]=$r['keywords'][0];
			}




			$chart_value = array();
			foreach($datalab_search['results'][0]['data'] as $period => $ratio){
				$chart_value[$period]=array();
				$chart_value[$period][] = $period;
			}

			foreach($datalab_search['results'] as $k => $r){
				foreach($r['data'] as $period => $ratio){
					$chart_value[$period][] = round($ratio,2);
				}
			}
			$chart_data = array_merge(array($chart_title),array_values($chart_value));
			// print_r($chart_data);
			// exit;
			$json = json_encode($chart_data);
		?>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
			var chart = null;
			var chart_data = <?=$json?>;
			function drawChart() {
				// var chart_data = [];
				var data = google.visualization.arrayToDataTable(chart_data);

				var options = {
					title: '연관키워드 TOP5 검색률(%)',
					// curveType: 'function',
					legend: { position: 'top', maxLines:2, fontSize:10, },
					chartArea: {
						// leave room for y-axis labels
						width: '70%',height:'300'
					},
				};

				chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
				chart.draw(data, options);
			}
		</script>
		<div id="curve_chart" style="width: 100%; max-width: 1000px;height: 450px;margin:5px auto; overflow-x:auto; overflow-y:hidden"></div>
		<div id="sot2" class="sot" data-sot-top="1" data-sot-left="2" style="width:100%;max-height:400px;">
			<table class="table table-bordered table-hover table-striped table-condensed">
			<colgroup>
				<col width="40">
				<col width="160">
				<?
				foreach($datalab_search['results'][0]['data'] as $v):
					?>
					<col width="80">
					<?
				endforeach;
				?>
			</colgroup>
			<thead>
				<tr>
					<th class="text-center">NO</th>
					<th class="text-center">키워드</th>
					<?
					foreach($datalab_search['results'][0]['data'] as $period => $ratio):
						?>
						<th class="text-center"><?=$period?></th>
						<?
					endforeach;
					?>
				</tr>
			</thead>
			<tbody>
				<?
				foreach($datalab_search['results'] as $k => $r):
					?>
					<tr>
						<td class="text-center"><?=$k+1?></td>
						<td class="text-center"><?=html_escape($r['keywords'][0])?></td>
						<?
						foreach($r['data'] as $period => $ratio):
							?>
							<td class="text-right"><?=round($ratio,2)?></td>
							<?
						endforeach;
						?>
					</tr>
					<?
				endforeach;
				?>
			</tbody>
		</table>
		</div>
		<?
	endif;
	?>
</div>
<div>
</div>
