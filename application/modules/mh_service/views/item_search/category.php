<?
//$nsc_rows
$t_ed = strtotime($date_ed);
$t_st = strtotime($date_st);
?>
<?
require(dirname(__FILE__).'/menu.php');
?>
<? /*
<!-- 달력 -->
<link href="<?=SITE_URI_ASSET_PREFIX?>css/vendor/bootstrap-datepicker/bootstrap-datepicker3.min.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<script src="<?=SITE_URI_ASSET_PREFIX?>js/vendor/bootstrap-datepicker/bootstrap-datepicker.min.js?t=<?=REFLESH_TIME?>"></script>
<script src="<?=SITE_URI_ASSET_PREFIX?>js/vendor/bootstrap-datepicker/locales/bootstrap-datepicker.kr.js?t=<?=REFLESH_TIME?>"></script>
<script>
$(
	function(){
		$('.input-daterange').datepicker({
			format: "yyyy-mm-dd",
			language: "kr",
			autoclose: true,
			todayHighlight: true,
			// todayBtn: "linked",
			endDate:"<?=date('Y-m-d',time()-86400)?>",
			startDate:"2019-01-01",
		});
		return;
	}
);
</script>

*/ ?>
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/StickyOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/stickyOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<link href="<?=SITE_URI_ASSET_PREFIX?>item_search/item_search.css?t=<?=REFLESH_TIME?>" rel="stylesheet">


<style>
.c-rank{background-color: #eee; font-size: 11px; vertical-align: middle !important;}
<?
for($i=1,$m=50;$i<=$m;$i++):

	if($i<=25){
		$t = 255-round(127*($i)/25) ;
		// $t =  round(255*($i)/25)+127 ;

		$r = $t;
		$g = round($t/2);
		$b = $g;
	}else{
		// $t =  round(255*($i-25)/25)+127 ;
		$t = 255-round(127*($i-25)/25) ;
		$r = round($t/2);
		$g = $t;
		$b = $r;
	}
	?>
	.c-rank-<?=$i?>{background-color: rgb(<?=$r?>,<?=$g?>,<?=$b?>);}
	<?
endfor;
?>


.c-slope-p{color: red;}
.c-slope-m{color: blue;}

.c-step-m{color: red;}
.c-step-m::before{content: "⤴️ "; }
.c-step-p{color: blue;}
.c-step-p::before{content: "⤵️ ";}


.c-dev-10{background-color: #ffc83c;}


.c-step-OUT{background-color: #aaa;padding-left:0 !important;padding-right:0 !important}
.c-step-OUT::before{content: ""}
.c-step-NEW{background-color: #ffc83c;padding-left:0 !important;padding-right:0 !important}
.c-step-NEW::before{content: ""}

.c-step-0{color: green; }
.c-step-0::before{content: "➡️";}
.c-rank-999{text-decoration: line-through;background-color: #aaa; color: transparent;}
.c-view{font-size: 8px;}

.sot label{
	display: block;
	overflow: hidden;
	white-space: nowrap;
	text-overflow: ellipsis;
}
.sot table{
	background: #fff;
}

.sot .sot-left{
	background-color: #dec;
}
.sot .sot-top{
	background-color: #eee;
}
</style>
<div  class="container-fluid text-center ">
	<h2><b>카테고리 키워드</b></h2>
	<h5>기간별 쇼핑 키워드의 변화량을 알려드립니다.</h5>
</div>
<div class="container-fluid ">
	<!-- <h2><?=isset($group_types[$group_type])?$group_types[$group_type]:'--'?>통계 -->
	</h2>
</div>
<div class="container-fluid ">
	<hr>
	<form class="form-inline text-center" name="form_filter">
		<div>
			<div class="input-group" style="width: auto;">
				<span class="input-group-addon" >카테고리</span>
				<select name="cid" class="form-control"  required>
					<option value="">카테고리선택</option>
					<?
					$t0 = '';
					foreach ($nsc_rows as $k => $v):
						?>
						<?
						if($t0 != $v['nsc_id_1']):
							?>
								<? if($t0 != ''):?></optgroup><? endif; ?>
								<optgroup label="<?=html_escape($v['nsc_name_1'])?>">
							<?
							$t0 = $v['nsc_id_1'];
						endif;
						?>
						<?
						// print_r($v);
						$selected = $cid==$v['nsc_id']?'selected':'';
						if($v['nsc_depth']=='1'){
							$t = 	$v['nsc_name_1'].' 전체';
						}
						if($v['nsc_depth']=='2'){
							$t = 	$v['nsc_name_1'].' > '.$v['nsc_name_2'];
						}
						?>
							<option value="<?=html_escape($v['nsc_id'])?>" <?=$selected?>><?=html_escape($t)?></option>
						<?
					endforeach;

					?>
					</optgroup>

				</select>
			</div>
			<? /* ?>
			<div class="input-group input-daterange" style="width: auto;">
				<span class="input-group-addon" style="border-width: 1px;">날짜</span>
				<input class="form-control input-date" name="date_st" value="<?=$date_st?>" placeholder="YYYY-MM-DD" style="width: 8em;"  required>
				<span class="input-group-addon">-</span>
				<input class="form-control input-date" name="date_ed" value="<?=$date_ed?>" placeholder="YYYY-MM-DD" style="width: 8em;"  required>
			</div>
			<? //*/ ?>
			<? //* ?>
			<div class="input-group input-daterange" style="width: auto;">
				<span class="input-group-addon" style="border-width: 1px;">기간</span>
				<select class="form-control" name="date_period" required>
					<option value="">기간</option>
					<option value="30" <?=$date_period==30?'selected':''?> >30일</option>
					<option value="60" <?=$date_period==60?'selected':''?> >60일</option>
					<option value="90" <?=$date_period==90?'selected':''?> >90일</option>
					<option value="180" <?=$date_period==180?'selected':''?>>180일</option>
					<option value="365" <?=$date_period==365?'selected':''?>>365일 (월별 전용)</option>
					<option value="730" <?=$date_period==730?'selected':''?>>730일 (월별 전용)</option>
				</select>
			</div>
			<? //*/ ?>
			<? //* ?>
			<div class="input-group" >
				<span class="input-group-addon" style="border-width: 1px;">날짜단위</span>
				<select name="group_type" class="form-control"  required>
					<option value="">#날짜단위</option>
					<?
					foreach ($group_types as $k => $v):
						$selected = $group_type==$k?'selected':'';
						?>
						<option value="<?=html_escape($k)?>" <?=$selected?>><?=html_escape($v)?></option>
						<?
					endforeach;
					?>
				</select>
			</div>
			<? //*/ ?>

			<div class="input-group" >
				<span class="input-group-addon" style="border-width: 1px;">키워드</span>
				<input class="form-control" name="shw" value="<?=$shw?>" placeholder=",로 구분" style="min-width: 8em;" maxlength="100" >
			</div>

			<button class="btn btn-info" type="submit">확인</button>
		</div>


	</form>
</div>

<?
if($rowss):
	?>
	<hr>
	<div class="container-fluid ">
		<h3 class="text-center">
			<div style="display:inline-block;color:#274fa7; font-weight:bold"><?=html_escape($cids[$cid])?></div>
			<div style="display:inline-block">(<?=html_escape($date_st.' ~ '.$date_ed)?>)</div>
		</h3>
		<!-- <div class="text-right">
			<button class="btn btn-success" type="button" onclick="save_chart_image()" >차트 PNG 다운로드</button>
			<button class="btn btn-warning" type="button" onclick="save_table_csv()" >테이블 CSV(UTF-8) 다운로드</button>
		</div> -->
		<div id="curve_chart" style="width: 100%; height: 400px;margin:10px 0"></div>
	</div>
	<div>
		<div class="">
			<form name="form_table">
				<script>
				function search_form_table(f){
					var arr = [];
					for(var i=0,m=f.keyword.length;i<m;i++){
						if(f.keyword[i].checked) arr.push(f.keyword[i].value)
					}
					document.form_filter.shw.value = arr.join(',');
					document.form_filter.querySelector('button[type="submit"]').click();
				}
				</script>


				<script>
					$(function(){
						StickyOnTable.apply(document.querySelector('#sot1'))
					})
				</script>
				<div id="sot1" class="sot" data-sot-top="2" data-sot-left="1" style="max-width:100%;max-height:600px;height:<?=count($rowss)<3?'auto':'600px;'?>; margin:10px;">
					<table class="table table-condensed table-bordered table-va-m" style="background-color:#fff;width:20px !important; margin:0 auto;">
						<colgroup>
							<col width="180" />
							<col width="60" />
							<col width="60" />
							<col width="60" />
							<col width="40" />
							<col width="60" />
							<col width="60" />
							<?
							foreach ($def_date_array as $k => $v):
								?>
								<col width="40" />
								<?
							endforeach;
							?>
						</colgroup>
						<thead style="background-color:#eee;">
							<tr>
								<th class="text-center align-middle" style="min-width:8em" height="60" rowspan="2">키워드<br><button class="btn btn-sm btn-info" type="button" onclick="search_form_table(this.form)">검색</button></th>

								<th class="text-center align-middle" colspan="6">정보</th>
								<th class="text-center align-middle" colspan="<?=count($def_date_array)?>">
									트렌드 점수<br><?=html_escape($date_st.' ~ '.$date_ed)?> (<?=$period?>일간)</th>
							</tr>
							<tr>
								<th class="text-center align-middle" rowspan="">평균<br>점수</th>
								<th class="text-center align-middle" rowspan="">표준<br>편차</th>
								<th class="text-center align-middle" rowspan="">상승세</th>
								<th class="text-center align-middle" rowspan="">기간</th>
								<th class="text-center align-middle" rowspan="">도매꾹<br>점수</th>
								<th class="text-center align-middle" rowspan="">도매매<br>점수</th>
								<?
								foreach ($def_date_array as $k => $v):
									if($group_type=='day'){$t = date("m\nd",strtotime($k));}
									else{$t = str_replace('-',"\n",$v);}
									?>
									<th style="min-width:2.5em;"  class="text-center align-middle" ><?=nl2br($t)?></th>
									<?
								endforeach;
								?>
							</tr>
						</thead>
						<tbody class="text-center">

							<?

							foreach ($rowss as $k => $r):
								$euckr_keyword = iconv('utf-8','euc-kr',$r['keyword']);
								$domeggook_url = 'http://domeggook.com/main/item/itemList.php?sfc=ttl&sf=ttl&sw='.urlencode($euckr_keyword);
								$domemedb_url = 'https://domemedb.domeggook.com/index/item/supplyList.php?sf=subject&enc=utf8&fromOversea=0&mode=search&sw='.urlencode($r['keyword']);
								// print_r($r);

								?>
								<tr class="text-center">
									<td class="text-left" style="padding:0 5px; vertical-align:middle" title="<?=html_escape($r['keyword'])?>">
										<input type="checkbox" name="keyword" value="<?=html_escape($r['keyword'])?>" <?=in_array($r['keyword'],$shws)?'checked':''?> >
										<a class="inline-block" target="_blank" href="./keyword?keyword=<?=urlencode($r['keyword'])?>"><?=html_escape($r['keyword'])?></a>
									</td>
									<td class="c-rank c-rank-<?=floor($r['avg_rank'])?>"><?=round($r['avg_view'],0)?></td>
									<td class="c-rank <?=$r['dev_rank']>=50?'c-dev-10':''?>"><?=round($r['dev_view'],0)?></td>
									<td class="c-rank  c-step-<?=round($r['slope_view'])?> c-step-<?=is_numeric($r['slope_view'])?($r['slope_view']>0?'p':'m'):'nonum'?>"><?=round(abs($r['slope_view']))?>%</td>
									<td><?=$r['count_rank']?></td>
									<td><a class="btn btn-success btn-xs" style="min-width:3em;" title="도매꾹에서 검색" href="<?=html_escape($domeggook_url)?>" target="_blank"><?=$r['sum_upv_domeggook']?></a></td>
									<td><a class="btn btn-warning btn-xs" style="min-width:3em;" title="도매매에서 검색" href="<?=html_escape($domemedb_url)?>" target="_blank"><?=$r['sum_upv_domeme']?></a></td>
									<?
									foreach ($def_date_array as $k => $v):
										?>
										<td height="30" class="c-rank c-rank-<?=floor($r['ranks'][$k])?>"><?=$r['views'][$k]?></td>
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
			</form>
		</div>
		<!-- 차트 처리 -->
		<?
		//-- 차트용 데이터 생성
		$chart_title = array('날짜');
		$chart_value = array();

		foreach ($def_date_array as $k => $v) {
			$chart_value[$k] = array($v);
		}

		$i_limit = 10;
		foreach ($rowss as $k => $v) {
			if(--$i_limit < 0 ) break;
			$chart_title[]=$k;
			foreach ($chart_value as $k_date => $v_r) {
				$chart_value[$k_date][] = (int)$v['views'][$k_date];
			}

		}
		// print_r($chart_title);
		// print_r($chart_value);
		$chart_data = array($chart_title);
		$chart_data = array_merge($chart_data,array_values($chart_value));
		$json = json_encode($chart_data,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
		?>

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
					title: 'TOP10 키워드 변화량',
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
		<!-- //차트 처리 -->
	</div>
<?
else:
	?>
	<h3 class="text-center">검색 정보를 설정해주세요.</h3>
	<?
endif;
?>

<hr>
<div  class="container-fluid " style="margin-bottom:2em;">
	<div class="text-danger">
		* 주의<br>
		* 데이터의 신뢰도를 보장하지 않습니다.<br>
		* 데이터 사용에 결과는 사용자 본인의 책임입니다.<br>
		* 화면이 이상하게 보일 경우, <a href="https://www.google.com/intl/ko/chrome/" target="_blank">구글 크롬 브라우저</a>를 사용해주세요.<br>
		* 2depth 카테고리는 2020년 10월 01일 부터 조회가 가능합니다.<br>
	</div>
</div>
