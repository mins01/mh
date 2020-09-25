<?
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
<h2 style="margin-bottom:2em" class="text-center">Google analytics 통계</h2>
<div id="curve_chart" style="width: 100%; max-width: 1000px;height: 400px;margin:10px auto"></div>
<div class="row">
	<div class="col-lg-6">
		<table class="table" style="table-layout:fixed">
			<colgroup>
				<col width="80">
				<col>
				<col width="80">
				<col width="80">
			</colgroup>
			<thead>
				<tr>
					<th class="text-center">번호</th>
					<th class="text-center">검색어</th>
					<th class="text-center">PV</th>
					<th class="text-center">UPV</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_search']['ga:searchResultViews']))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_search']['ga:searchUniques']))?></th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['searchs'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left text-overflow-ellipsis"><a href="<?=html_escape($row[1])?>" target="_blank"><?=html_escape($row[0])?></a></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[3]))?></td>
				</tr>
				<?
			endforeach;
			?>
		</table>
	</div>
	<div class="col-lg-6">
		<table class="table" style="table-layout:fixed">
			<colgroup>
				<col width="80">
				<col>
				<col width="80">
				<col width="80">
			</colgroup>
			<thead >
				<tr >
					<th class="text-center">번호</th>
					<th class="text-center">페이지 타이틀</th>
					<th class="text-center">PV</th>
					<th class="text-center">UPV</th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th class="text-center">총</th>
					<th class="text-center"></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_page']['ga:pageviews']))?></th>
					<th class="text-center"><?=html_escape(number_format($rowss['total_page']['ga:uniquePageviews']))?></th>
				</tr>
			</tfoot>
			<?
			foreach ($rowss['pages'] as $k => $row):
				?>
				<tr>
					<td class="text-center"><?=$k+1?></td>
					<td class="text-left"><div class="text-overflow-ellipsis"><a href="<?=html_escape($row[0])?>" target="_blank" title="<?=html_escape($row[1])?>"><?=html_escape($row[0])?></a></div></td>
					<td class="text-center"><?=html_escape(number_format($row[2]))?></td>
					<td class="text-center"><?=html_escape(number_format($row[3]))?></td>
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
$chart_title = array('날짜','방문자','신규방문자');
$chart_value = array();
foreach ($rowss['users'] as $k => $r) {
	$chart_value[] = array(date('m-d',strtotime($r[0])),(int)$r[1],(int)$r[2]);
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
			title: '방문자 변화량',
			// curveType: 'function',
			legend: { position: 'top', maxLines:2, fontSize:12, },
			chartArea: {
				// leave room for y-axis labels
				width: '90%',height:'300'
			},
		};

		chart = new google.visualization.LineChart(document.getElementById('curve_chart'));
		chart.draw(data, options);
	}
</script>
<!-- 차트용 -->
