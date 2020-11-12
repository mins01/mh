<?
// $keyword
// $search_totals
// $managedKeyword
// $keywordstool
// $datalab_search
// $datalab_shops
// $search_shop_catetories
?>

<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/StickyOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/stickyOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<link href="<?=SITE_URI_ASSET_PREFIX?>item_search/item_search.css?t=<?=REFLESH_TIME?>" rel="stylesheet">


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
		document.querySelectorAll('.sot-onload').forEach((item, i) => {
				StickyOnTable.apply(item);
		});
	})
</script>
<h2 class="text-right">키워드 상세정보</h2>
<div>
	<h3>검색</h3>
	<form action="" method="get">
		<div class="input-group input-group-lg">
			<span class="input-group-addon">키워드</span>
			<input type="text" name="keyword" class="form-control" placeholder="키워드" maxlength="20" value="<?=html_escape($keyword)?>">
			<span class="input-group-btn">
				<button class="btn btn-info" type="button">확인</button>
			</span>
		</div>
	</form>
</div>
<div>
	<hr>
	<h3>검색정보</h3>

	<?
	if(isset($search_totals)):
		// print_r($search_totals);
		$urls = array();
		$urls['blog'] = 'https://search.naver.com/search.naver?where=blog&query='.urlencode($keyword);
		$urls['cafearticle'] = 'https://search.naver.com/search.naver?where=article&query='.urlencode($keyword);
		$urls['kin'] = 'https://search.naver.com/search.naver?where=kin&query='.urlencode($keyword);
		$urls['webkr'] = 'https://search.naver.com/search.naver?where=nexearch&query='.urlencode($keyword);
		$urls['shop'] = 'https://search.shopping.naver.com/search/all?where=all&frm=NVSCTAB&query='.urlencode($keyword);
		?>
		<div class="flex-row-container">
			<dl class="infobox">
				<dt><a href="<?=html_escape($urls['blog'])?>" target="_blank">블로그</a></dt>
				<dd><?=number_format($search_totals['blog'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><a href="<?=html_escape($urls['cafearticle'])?>" target="_blank">카페</a></dt>
				<dd><?=number_format($search_totals['cafearticle'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><a href="<?=html_escape($urls['kin'])?>" target="_blank">지식인</a></dt>
				<dd><?=number_format($search_totals['kin'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><a href="<?=html_escape($urls['webkr'])?>" target="_blank">웹문서</a></dt>
				<dd><?=number_format($search_totals['webkr'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><a href="<?=html_escape($urls['shop'])?>" target="_blank">쇼핑</a></dt>
				<dd><?=number_format($search_totals['shop'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><span>검색수</span></dt>
				<dd><?=number_format($search_totals['search'])?></dd>
			</dl>
			<dl class="infobox">
				<dt><span>경쟁강도</span></dt>
				<dd><?=round($search_totals['competitive_strength'],3)?></dd>
			</dl>
		</div>
		<?
	endif;
	?>
</div>
<div>
	<hr>
	<h3>네이버 검색광고 키워드 정보</h3>
	<?
	if(isset($managedKeyword)):
		?>
		<div class="infobox-container">
			<dl class="infobox">
				<dt>성인 키워드</dt>
				<dd class="dd-v-<?=$managedKeyword['isAdult']?'O':'X'?>"><?=$managedKeyword['isAdult']?'O':'X'?></dd>
			</dl>
			<dl class="infobox">
				<dt>노출제한 키워드</dt>
				<dd class="dd-v-<?=$managedKeyword['isRestricted']?'O':'X'?>"><?=$managedKeyword['isRestricted']?'O':'X'?></dd>
			</dl>
			<dl class="infobox">
				<dt>시즌 키워드</dt>
				<dd class="dd-v-<?=$managedKeyword['isSeason']?'O':'X'?>"><?=$managedKeyword['isSeason']?'O':'X'?></dd>
			</dl>
			<dl class="infobox">
				<dt>판매금지 키워드</dt>
				<dd class="dd-v-<?=$managedKeyword['isSellProhibit']?'O':'X'?>"><?=$managedKeyword['isSellProhibit']?'O':'X'?></dd>
			</dl>
			<dl class="infobox">
				<dt>낮은검색률</dt>
				<dd class="dd-v-<?=$managedKeyword['isLowSearchVolume']?'O':'X'?>"><?=$managedKeyword['isLowSearchVolume']?'O':'X'?></dd>
			</dl>
			<dl class="infobox">
				<dt>등록일</dt>
				<dd><?=isset($managedKeyword['regTm'][0])?substr($managedKeyword['regTm'],0,10):'등록없음'?></dd>
			</dl>
			<dl class="infobox">
				<dt>수정일</dt>
				<dd><?=isset($managedKeyword['editTm'][0])?substr($managedKeyword['editTm'],0,10):'수정없음'?></dd>
			</dl>
			<dl class="infobox">
				<dt>최대노출수</dt>
				<dd class="dd-v-<?=$managedKeyword['PCPLMaxDepth']?>"><?=$managedKeyword['PCPLMaxDepth']?></dd>
			</dl>
		</div>
		<?
	endif;
	?>
</div>
<div>
	<hr>
	<h3>관련 쇼핑 카테고리 정보</h3>
	<?
	if(isset($search_shop_catetories)):
		?>
		<ul  class="list-group">
			<?
			foreach ($search_shop_catetories as $r):
				// print_r($r);
				?>
				<li class="list-group-item">
					<a  href="./category?cid=<?=urlencode($r['cid1'])?>"><?=html_escape($r['category1'])?></a>
					&gt; <span><?=html_escape($r['category2'])?></span>
					&gt; <span><?=html_escape($r['category3'])?></span>
					<? if(isset($r['category4'][0])):?> &gt; <span><?=html_escape($r['category4'])?></a><? endif; ?>
				</li>
				<?
			endforeach;
			?>
		</ul>
		<?
	endif;
	?>
</div>
<div>
	<hr>
	<h4>네이버 데이터랩 쇼핑 정보</h4>
	<div class="row">
		<div class="col-lg-12">
			<div id="gChart5" style="width: 100%; max-width: 1000px;height: 450px;margin:20px auto; overflow-x:auto; overflow-y:hidden"></div>
		</div>
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
	<hr>
	<h4>네이버 검색광고 연관 키워드 정보 <small>( 30일,4주 기준, 최대 500 연관 키워드 )</small></h4>

	<?
	if(isset($keywordstool['keywordList'])):
		?>
		<div id="sot1" class="sot sot-onload" data-sot-top="2" data-sot-left="2"  style="width:100%;height:400px;">
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
	<h4>네이버 데이터랩 검색 정보(연관키워드 TOP5 검색률(%))</h4>
	<?
	if(isset($datalab_search)):
		// print_r($datalab_search);exit;
		?>
		<div id="gChart1" style="width: 100%; max-width: 1000px;height: 450px;margin:20px auto; overflow-x:auto; overflow-y:hidden"></div>
		<div id="sot2" class="sot sot-onload" data-sot-top="1" data-sot-left="2" style="width:100%;max-height:400px;">
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
						<td class="text-center"><a href='?keyword=<?=urlencode(html_escape($$r['keywords'][0]))?>'><?=html_escape($r['keywords'][0])?></a></td>
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





<?
	//---- 데이터랩 검색
	$datalab_search_chart_data = null;
	if(isset($datalab_search['results'][0]['data'])){
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
	}
	$datalab_search_chart_json = json_encode($datalab_search_chart_data);
	unset($datalab_search_chart_data);
	//---- 데이터랩 쇼핑 device
	// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
	$datalab_shops_device_data = null;
	if(isset($datalab_shops['results']['device'])){
		$datalab_shops_device_data = array();
		$datalab_shops_device_data[] = array('device','비율');
		$datalab_shops_device_data[] = array('모바일',round($datalab_shops['results']['device'][0]['ratio']['mo']*100,2));
		$datalab_shops_device_data[] = array('PC',round($datalab_shops['results']['device'][0]['ratio']['pc']*100,2));
	}
	$datalab_shops_device_json = json_encode($datalab_shops_device_data);
	unset($datalab_shops_device_data);
	//---- 데이터랩 쇼핑 device
	// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
	$datalab_shops_gender_data = null;
	if(isset($datalab_shops['results']['gender'])){
		$datalab_shops_gender_data = array();
		$datalab_shops_gender_data[] = array('device','비율');
		$datalab_shops_gender_data[] = array('여성',round($datalab_shops['results']['gender'][0]['ratio']['f']*100,2));
		$datalab_shops_gender_data[] = array('남성',round($datalab_shops['results']['gender'][0]['ratio']['m']*100,2));
	}
	$datalab_shops_gender_json = json_encode($datalab_shops_gender_data);
	unset($datalab_shops_gender_data);
	//---- 데이터랩 쇼핑 device
	// print_r($datalab_shops['results']['device'][0]['ratio']);			exit;
	$datalab_shops_age_data = null;
	if(isset($datalab_shops['results']['age'])){
		$datalab_shops_age_data = array();
		$datalab_shops_age_data[] = array('연령','비율',array('role'=>'style'));
		$datalab_shops_age_data[] = array('10대',round($datalab_shops['results']['age'][0]['ratio']['10']*100,2),'#ff9c00');
		$datalab_shops_age_data[] = array('20대',round($datalab_shops['results']['age'][0]['ratio']['20']*100,2),'#83ff95');
		$datalab_shops_age_data[] = array('30대',round($datalab_shops['results']['age'][0]['ratio']['30']*100,2),'#83c4ff');
		$datalab_shops_age_data[] = array('40대',round($datalab_shops['results']['age'][0]['ratio']['40']*100,2),'#ff6d6d');
		$datalab_shops_age_data[] = array('50대',round($datalab_shops['results']['age'][0]['ratio']['50']*100,2),'#d16dff');
		$datalab_shops_age_data[] = array('60대',round($datalab_shops['results']['age'][0]['ratio']['60']*100,2),'#CCCCCC');
	}
	// print_r($datalab_shops['results']['age'])
	$datalab_shops_age_json = json_encode($datalab_shops_age_data);
	unset($datalab_shops_age_data);
	//---- 쇼핑/검색 트랜드
	// print_r($datalab_search['results'][0]['data']);
	// print_r($datalab_shops['results']['keywords'][0]['data']);
	// exit;
	$gChart5_data = null;
	if(isset($datalab_shops['results']['keywords'][0]['data'])){
		$gChart5_data = array();
		$gChart5_data[] = array('트렌트','쇼핑트렌트','검색트렌트');
		foreach ($datalab_shops['results']['keywords'][0]['data'] as $period => $v) {
			$gChart5_data[]=array($period,(float)$v,(float)isset($datalab_search['results'][0]['data'][$period])?$datalab_search['results'][0]['data'][$period]:0);
		}
	}

	// $gChart5_json = pretty_json_encode($gChart5_data);
	$gChart5_json = json_encode($gChart5_data);
	// print_r($gChart5_json);exit;
?>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	var gChart1 = null,gChart2 = null,gChart3 = null,gChart4 = null,gChart5 = null;
	function drawChart() {
		// var chart_data = [];
		let data = null,options = null;
		var datalab_search_chart_json = <?=$datalab_search_chart_json?>;
		if(datalab_search_chart_json){
			data = google.visualization.arrayToDataTable(datalab_search_chart_json);

			options = {
				title: '연관키워드 TOP5 검색률(%)',
				// curveType: 'function',
				legend: { position: 'top', maxLines:2, fontSize:10, },
				chartArea: {
					// leave room for y-axis labels
					width: '90%',height:'300'
				},
				pointSize:10,
				series: {
					0: {
						lineWidth: 4,	// lineDashStyle: [5, 1, 5]
					},
					1: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					2: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					3: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					4: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					}
				},
			};
			gChart1 = new google.visualization.LineChart(document.getElementById('gChart1'));
			gChart1.draw(data, options);
		}

		// https://developers.google.com/chart/interactive/docs/gallery/piechart
		//-----------------------------------------------------------------
		options = {
			title: '모바일,PC',
			titleTextStyle:{ fontSize: 20,  bold: true,  italic: false },
			legend: { alignment:'center', position: 'bottom',  textStyle: { fontSize: 16,bold:true},maxLines:1 },
			pieSliceTextStyle:{fontSize: 20},
			chartArea: {
				// leave room for y-axis labels
				width: '80%',height:'80%'
			},
			// is3D: true,
			pieHole: 0.3,
			pieStartAngle: 180,
		};
		var datalab_shops_device_json = <?=$datalab_shops_device_json?>;

		if(datalab_shops_device_json){
			data = google.visualization.arrayToDataTable(datalab_shops_device_json);
			options.title='모바일,PC'
			options.slices ={
				0: { color: 'rgb(255, 156, 0)' },
				1: { color: 'rgb(0, 217, 149)' }
			}
			gChart2 = new google.visualization.PieChart(document.getElementById('gChart2'));
			gChart2.draw(data, options);
		}

		//----

		var datalab_shops_gender_json = <?=$datalab_shops_gender_json?>;
		if(datalab_shops_gender_json){
			options.title='여성,남성'
			options.slices ={
				0: { color: 'rgb(131, 196, 255)' },
				1: { color: 'rgb(255, 109, 109)' }
			}
			data = google.visualization.arrayToDataTable(datalab_shops_gender_json);
			gChart3 = new google.visualization.PieChart(document.getElementById('gChart3'));
			gChart3.draw(data, options);
		}


		// https://developers.google.com/chart/interactive/docs/gallery/columnchart
		// ---------------------------------------

		var datalab_shops_age_json = <?=$datalab_shops_age_json?>;
		if(datalab_shops_age_json){
			data = google.visualization.arrayToDataTable(datalab_shops_age_json);

			options = {
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
			gChart4 = new google.visualization.ColumnChart(document.getElementById('gChart4'));
			gChart4.draw(data, options);
		}

		//---- gChart 5
		var gChart5_json = <?=$gChart5_json?>;
		if(gChart5_json){
			data = google.visualization.arrayToDataTable(gChart5_json);

			options = {
				title: '쇼핑/검색 트렌드',
				titleTextStyle:{ fontSize: 20,  bold: true,  italic: false },
				// curveType: 'function',
				legend: { position: 'top', maxLines:2, fontSize:10, },
				chartArea: {
					// leave room for y-axis labels
					width: '90%',height:'300'
				},
				pointSize:10,
				series: {
					0: {
						lineWidth: 4,	// lineDashStyle: [5, 1, 5]
					},
					1: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					2: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					3: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					},
					4: {
						lineWidth: 4,	// lineDashStyle: [7, 2, 4, 3]
					}
				},
			};
			gChart5 = new google.visualization.LineChart(document.getElementById('gChart5'));
			gChart5.draw(data, options);
		}


	}
</script>
