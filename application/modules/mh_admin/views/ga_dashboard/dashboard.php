<?
// $rowss = array(
// 	'per_date' => $res_rowss[0],
// 	'pages' => $res_rowss[1],
// 	'searchs' => $res_rowss[2],
// 	'total_per_date' => $res[0]['data']['totals'],
// 	'total_pages' => $res[1]['data']['totals'],
// 	'total_searchs' => $res[2]['data']['totals'],
// 	'createdAt' => date('Y-m-h H:i:s'),
// );
// $rowss['searchs']
// $rowss['total_search']
// $rowss['pages']
// $rowss['total_page']
// $rowss['users']
// $rowss['total_user']
// $rowss['createdAt']
//
// print_r($rowss['total_page']);
?>
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_FixedFrameOnTable/FixedFrameOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_FixedFrameOnTable/fixedFrameOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<style>
.ffot-container-left {
    background-color: #fff;
}
</style>
<script>
var ffot11 = null;
$(function(){
	//-- 수동 생성용
	// ffot11 = new FixedFrameOnTable(document.querySelector('#ffot11'));
	//-- 자동 변환용
	ffot01= FixedFrameOnTable.tableToFfot(document.querySelector('#table_data'),1,2,document.querySelector('#ffot11'))
	var x = document.querySelector('#table_data');
	x.parentNode.removeChild(x)

})
</script>
<h2 style="margin-bottom:0em" class="text-center">Google analytics 대쉬보드 ( <?=$daysAgo?>일간 )</h2>
<div>
	<div class="text-center">
		<div class="text-right"><a class="btn btn-danger btn-sm" href="?nocache=1">캐시 강제 갱신</a></div>
		<table class="table table-striped table-bordered table-condensed" style="width:auto; margin:0 auto;">
			<thead>
				<tr>
					<th class="text-center">방문자</th>
					<th class="text-center">신규방문자</th>
					<th class="text-center">세션</th>
					<th class="text-center">UPV</th>
					<th class="text-center">PV</th>
				</tr>
			</thead>

			<tr>
				<td><?=number_format($rowss['total_per_date'][0])?></td>
				<td><?=number_format($rowss['total_per_date'][1])?></td>
				<td><?=number_format($rowss['total_per_date'][2])?></td>
				<td><?=number_format($rowss['total_per_date'][3])?></td>
				<td><?=number_format($rowss['total_per_date'][4])?></td>
			</tr>
			<tr>
				<td>100%</td>
				<td><?=$rowss['total_per_date'][1]!=0?round($rowss['total_per_date'][1]/$rowss['total_per_date'][0]*100):0?>%</td>
				<td><?=$rowss['total_per_date'][2]!=0?round($rowss['total_per_date'][2]/$rowss['total_per_date'][0]*100):0?>%</td>
				<td><?=$rowss['total_per_date'][2]!=0?round($rowss['total_per_date'][3]/$rowss['total_per_date'][0]*100):0?>%</td>
				<td><?=$rowss['total_per_date'][3]!=0?round($rowss['total_per_date'][4]/$rowss['total_per_date'][0]*100):0?>%</td>
			</tr>
		</table>
	</div>
	<div id="curve_chart" style="width: 100%; max-width: 1000px;height: 450px;margin:5px auto; overflow-x:auto; overflow-y:hidden"></div>
	<div id="ffot11"></div>
	<div class="table-responsive" style="margin-bottom:1em;">
		<table class="table table-condensed table-bordered" style="table-layout:fixed" id='table_data'>
			<!-- <colgroup>
				<col width="30">
				<col>
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup> -->
			<thead >
				<tr class="active">
					<th class="text-center" width='80'>날짜</th>
					<th class="text-center" width='80'>총</th>
					<?
					foreach ($rowss['per_date'] as $k => $r):
						?>
						<th class="text-center" width="80"><?=preg_replace('/(?:\d{4})(\d{2})(\d{2})/','$1-$2',$r[0])?></th>
						<?
					endforeach;
					?>
				</tr>
			</thead>
			<!-- <tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_pages'][0]))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_pages'][1]))?></th>
					<th class="text-center"><?=html_escape(sprintf('%.2f',$rowss['total_pages'][2]))?>%</th>
				</tr>
			</tfoot> -->
			<!-- <th class="text-center">방문자</th>
			<th class="text-center">신규방문자</th>
			<th class="text-center">세션</th>
			<th class="text-center">UPV</th>
			<th class="text-center">PV</th> -->

			<tr class="">
				<th class="text-center">방문자</th>
				<th class="text-center"><?=number_format($rowss['total_per_date'][0])?></th>
				<?
				foreach ($rowss['per_date'] as $k => $r):
					?>
					<td class="text-center"><?=number_format($r[1])?></td>
					<?
				endforeach;
				?>
			</tr>
			<tr class="">
				<th class="text-center">신규방문자</th>
				<th class="text-center"><?=number_format($rowss['total_per_date'][1])?></th>
				<?
				foreach ($rowss['per_date'] as $k => $r):
					?>
					<td class="text-center"><?=number_format($r[2])?></td>
					<?
				endforeach;
				?>
			</tr>
			<tr class="">
				<th class="text-center">세션</th>
				<th class="text-center"><?=number_format($rowss['total_per_date'][2])?></th>
				<?
				foreach ($rowss['per_date'] as $k => $r):
					?>
					<td class="text-center"><?=number_format($r[3])?></td>
					<?
				endforeach;
				?>
			</tr>
			<tr class="">
				<th class="text-center">UPV</th>
				<th class="text-center"><?=number_format($rowss['total_per_date'][3])?></th>
				<?
				foreach ($rowss['per_date'] as $k => $r):
					?>
					<td class="text-center"><?=number_format($r[4])?></td>
					<?
				endforeach;
				?>
			</tr>
			<tr class="">
				<th class="text-center">PV</th>
				<th class="text-center"><?=number_format($rowss['total_per_date'][4])?></th>
				<?
				foreach ($rowss['per_date'] as $k => $r):
					?>
					<td class="text-center"><?=number_format($r[5])?></td>
					<?
				endforeach;
				?>
			</tr>
		</table>
	</div>
</div>

<div class="row">
	<!-- pages -->
	<div class="col-lg-6">
		<table class="table table-striped" style="table-layout:fixed">
			<colgroup>
				<col width="30">
				<col>
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup>
			<thead >
				<tr class="success">
					<th class="text-center">no</th>
					<th class="text-center">페이지</th>
					<th class="text-center">PV</th>
					<th class="text-center">UPV</th>
					<th class="text-center">이탈률</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_pages'][0]))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_pages'][1]))?></th>
					<th class="text-center"><?=html_escape(sprintf('%.2f',$rowss['total_pages'][2]))?>%</th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['pages'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left"><div class="text-overflow-ellipsis"><?=html_escape($row[0])?></div></td>
					<td class="text-center"><?=html_escape(number_format($row[1]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(sprintf('%.2f',$row[3]))?>%</td>
				</tr>
				<?
			endforeach;
			?>
		</table>

	</div>
	<!-- searchs -->
	<div class="col-lg-6">
		<table class="table table-striped" style="table-layout:fixed">
			<colgroup>
				<col width="30">
				<col>
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup>
			<thead>
				<tr class="info">
					<th class="text-center">no</th>
					<th class="text-center">내부 검색어</th>
					<th class="text-center">PV</th>
					<th class="text-center">UPV</th>
					<th class="text-center">이탈률</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_searchs'][0]))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_searchs'][1]))?></th>
					<th class="text-center"><?=html_escape(sprintf('%.2f',$rowss['total_searchs'][2]))?>%</th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['searchs'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left text-overflow-ellipsis"><?=html_escape($row[0])?></td>
					<td class="text-center"><?=html_escape(number_format($row[1]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(sprintf('%.2f',$row[3]))?>%</td>
				</tr>
				<?
			endforeach;
			?>
		</table>
	</div>
</div>
<div class="row">
	<!-- source -->
	<div class="col-lg-6">
		<table class="table table-striped" style="table-layout:fixed">
			<colgroup>
				<col width="30">
				<col>
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup>
			<thead>
				<tr class="warning">
					<th class="text-center">no</th>
					<th class="text-center">방문소스</th>
					<th class="text-center">세션</th>
					<th class="text-center">검색방문</th>
					<th class="text-center">이탈률</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_sources'][0]))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_sources'][1]))?></th>
					<th class="text-center"><?=html_escape(sprintf('%.2f',$rowss['total_sources'][2]))?>%</th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['sources'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left text-overflow-ellipsis"><?=html_escape($row[0])?></td>
					<td class="text-center"><?=html_escape(number_format($row[1]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(sprintf('%.2f',$row[3]))?>%</td>
				</tr>
				<?
			endforeach;
			?>
		</table>
	</div>
	<!-- source -->
	<div class="col-lg-6">
		<table class="table table-striped" style="table-layout:fixed">
			<colgroup>
				<col width="30">
				<col>
				<col width="80">
				<col width="80">
				<col width="80">
			</colgroup>
			<thead>
				<tr class="danger">
					<th class="text-center">no</th>
					<th class="text-center">방문 검색어</th>
					<th class="text-center">세션</th>
					<th class="text-center">검색방문</th>
					<th class="text-center">이탈률</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_keywords'][0]))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_keywords'][1]))?></th>
					<th class="text-center"><?=html_escape(sprintf('%.2f',$rowss['total_keywords'][2]))?>%</th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['keywords'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left text-overflow-ellipsis"><?=html_escape($row[0])?></td>
					<td class="text-center"><?=html_escape(number_format($row[1]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(sprintf('%.2f',$row[3]))?>%</td>
				</tr>
				<?
			endforeach;
			?>
		</table>
	</div>
</div>
<div class="text-right">data date : <?=html_escape($rowss['createdAt'])?></div>

<?
// print_r($rowss['users']);
$chart_title = array('날짜','방문자','신규방문자','세션','UPV','PV');
$chart_value = array();
foreach ($rowss['per_date'] as $k => $r) {
	$chart_value[] = array(date('m-d',strtotime($r[0])),(int)$r[1],(int)$r[2],(int)$r[3],(int)$r[4],(int)$r[5]);
}
$chart_data = array_merge(array($chart_title),$chart_value);
// print_r($chart_data);
$json = json_encode($chart_data);
?>
<!-- 차트용 -->
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script type="text/javascript">
	google.charts.load('current', {'packages':['corechart']});
	google.charts.setOnLoadCallback(drawChart);
	var chart = null;
	var chart_data = <?=$json?>;
	function drawChart() {
		// var chart_data = [];
		var data = google.visualization.arrayToDataTable(chart_data);

		var options = {
			title: '방문자/PV 변화량',
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
<!-- 차트용 -->
