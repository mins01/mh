<?
// $keyword
// $search_totals
// $managedKeyword
// $keywordstool
// $datalab_search
// $datalab_shops
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
			//---- 데이터랩 검색
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
			$datalab_search_chart_data = array_merge(array($chart_title),array_values($chart_value));
			$datalab_search_chart_json = json_encode($datalab_search_chart_data);
			unset($datalab_search_chart_data);
			//---- 데이터랩 쇼핑 device
			// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
			$datalab_shops_device_data = array();
			$datalab_shops_device_data[] = array('device','ratio(%)');
			$datalab_shops_device_data[] = array('모바일',round($datalab_shops['results']['device'][0]['ratio']['mo']*100,2));
			$datalab_shops_device_data[] = array('PC',round($datalab_shops['results']['device'][0]['ratio']['pc']*100,2));
			$datalab_shops_device_json = json_encode($datalab_shops_device_data);
			unset($datalab_shops_device_data);
			//---- 데이터랩 쇼핑 device
			// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
			$datalab_shops_gender_data = array();
			$datalab_shops_gender_data[] = array('device','ratio(%)');
			$datalab_shops_gender_data[] = array('여성',round($datalab_shops['results']['gender'][0]['ratio']['f']*100,2));
			$datalab_shops_gender_data[] = array('남성',round($datalab_shops['results']['gender'][0]['ratio']['m']*100,2));
			$datalab_shops_gender_json = json_encode($datalab_shops_gender_data);
			unset($datalab_shops_gender_data);
			//---- 데이터랩 쇼핑 device
			// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
			$datalab_shops_age_data = array();
			$datalab_shops_age_data[] = array('연령','ratio(%)',array('role'=>'style'));
			$datalab_shops_age_data[] = array('10대',round($datalab_shops['results']['age'][0]['ratio']['10']*100,2),'#CCAB4C');
			$datalab_shops_age_data[] = array('20대',round($datalab_shops['results']['age'][0]['ratio']['20']*100,2),'#BD6236');
			$datalab_shops_age_data[] = array('30대',round($datalab_shops['results']['age'][0]['ratio']['30']*100,2),'#466333');
			$datalab_shops_age_data[] = array('40대',round($datalab_shops['results']['age'][0]['ratio']['40']*100,2),'#346773');
			$datalab_shops_age_data[] = array('50대',round($datalab_shops['results']['age'][0]['ratio']['50']*100,2),'#AF342D');
			$datalab_shops_age_data[] = array('60대',round($datalab_shops['results']['age'][0]['ratio']['60']*100,2),'#CCCCCC');
			$datalab_shops_age_json = json_encode($datalab_shops_age_data);
			unset($datalab_shops_age_data);
		?>
		<script type="text/javascript">
			google.charts.load('current', {'packages':['corechart']});
			google.charts.setOnLoadCallback(drawChart);
			var gChart1 = null,gChart2 = null,gChart3 = null;
			var datalab_search_chart_json = <?=$datalab_search_chart_json?>;
			function drawChart() {
				// var chart_data = [];
				var data = google.visualization.arrayToDataTable(datalab_search_chart_json);

				var options = {
					title: '연관키워드 TOP5 검색률(%)',
					// curveType: 'function',
					legend: { position: 'top', maxLines:2, fontSize:10, },
					chartArea: {
						// leave room for y-axis labels
						width: '70%',height:'300'
					},
				};
				gChart1 = new google.visualization.LineChart(document.getElementById('gChart1'));
				gChart1.draw(data, options);

				// https://developers.google.com/chart/interactive/docs/gallery/piechart
				//-----------------------------------------------------------------
				var datalab_shops_device_json = <?=$datalab_shops_device_json?>;
				var data = google.visualization.arrayToDataTable(datalab_shops_device_json);

				var options = {
					title: '모바일,PC',
					titleTextStyle:{ fontSize: 20,  bold: true,  italic: false },
					legend: { alignment:'center', position: 'bottom',  textStyle: { fontSize: 16,bold:true},maxLines:1 },
					pieSliceTextStyle:{fontSize: 20},
					chartArea: {
						// leave room for y-axis labels
						width: '80%',height:'80%'
					},
					// is3D: true,
					pieHole: 0.2,
					pieStartAngle: 180,
				};
				options.title='모바일,PC'
				options.slices ={
					0: { color: 'rgb(255, 156, 0)' },
					1: { color: 'rgb(0, 217, 149)' }
				}
				gChart2 = new google.visualization.PieChart(document.getElementById('gChart2'));
        gChart2.draw(data, options);
				//----
				options.title='여성,남성'
				options.slices ={
					0: { color: 'rgb(131, 196, 255)' },
					1: { color: 'rgb(255, 109, 109)' }
				}
				var datalab_shops_gender_json = <?=$datalab_shops_gender_json?>;
				data = google.visualization.arrayToDataTable(datalab_shops_gender_json);
				gChart3 = new google.visualization.PieChart(document.getElementById('gChart3'));
        gChart3.draw(data, options);

				// https://developers.google.com/chart/interactive/docs/gallery/columnchart
				// ---------------------------------------

				var datalab_shops_age_json = <?=$datalab_shops_age_json?>;
				var data = google.visualization.arrayToDataTable(datalab_shops_age_json);

				var options = {
					title: '연령별',
					titleTextStyle:{ fontSize: 20,  bold: true,  italic: false },
					// legend: { alignment:'center', position: 'bottom',  textStyle: { fontSize: 16,bold:true},maxLines:1 },
					legend: { position: "none"},
					pieSliceTextStyle:{fontSize: 20},
					chartArea: {
						// leave room for y-axis labels
						width: '80%',height:'80%'
					},
					animation:{
						duration: 1000,
						easing: 'out',
						startup:false
					},
					// is3D: true,
				};
				options.title='연령별'
				options.slices ={
					0: { color: 'rgb(255, 156, 0)' },
					1: { color: 'rgb(0, 217, 149)' }
				}
				gChart2 = new google.visualization.ColumnChart(document.getElementById('gChart4'));
        gChart2.draw(data, options);

			}
		</script>
		<div id="gChart1" style="width: 100%; max-width: 1000px;height: 450px;margin:5px auto; overflow-x:auto; overflow-y:hidden"></div>
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
	<hr>
	<h4>네이버 데이터랩 쇼핑 정보</h4>
	<div class="rows">
		<div class="col-lg-4 col-sm-6">
			<div id="gChart2" style="width: 300px; max-width: 300px;height: 300px;margin:20px auto; overflow-x:auto; overflow-y:hidden"></div>
		</div>
		<div class="col-lg-4 col-sm-6">
			<div id="gChart3" style="width: 300px; max-width: 300px;height: 300px;margin:20px auto; overflow-x:auto; overflow-y:hidden"></div>
		</div>
		<div class="col-lg-4 col-sm-6">
			<div id="gChart4" style="width: 300px; max-width: 300px;height: 300px;margin:20px auto; overflow-x:auto; overflow-y:hidden"></div>
		</div>
	</div>

</div>
<div>
</div>
