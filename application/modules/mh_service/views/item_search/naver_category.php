<?
// $ca_rows
?>
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/StickyOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/stickyOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">



<style>
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
<script>
	$(function(){
		document.querySelectorAll('.sot-onload').forEach((item, i) => {
				StickyOnTable.apply(item);
		});
	})
</script>

<div  class="container-fluid text-center ">
	<h1 style="font-size:32px"><b>네이버 쇼핑 카테고리 테이블</b></h1>
</div>
<div class="container-fluid ">
	<div class="sot sot-onload sot-table-width-100px" data-sot-top="2" data-sot-left="0"  style="width:100%;height:400px;">
		<table class="table table-bordered table-hover table-striped table-condensed" style="margin:0 auto">
			<colgroup>
				<col width="120">
				<col width="60">
				<col width="150">
				<col width="150">
				<col width="150">
				<col width="150">
				<col width="320">

			</colgroup>
			<thead>
				<tr>
					<th class="text-center" rowspan="2" >cat_id</th>
					<th class="text-center" rowspan="2" >depth</th>
					<th class="text-center" colspan="4">카테고리</th>
					<th class="text-center" rowspan="2" >경로</th>
				</tr>
				<tr >
					<th class="text-center">1단</th>
					<th class="text-center">2단</th>
					<th class="text-center">3단</th>
					<th class="text-center">4단</th>
					<!-- <th class="text-center">4단</th> -->
				</tr>
			</thead>
			<tbody>
				<?
				foreach ($ca_rows as $k => $r):
					$t = array();
					if(isset($r['nsc_name_1'][0])) $t[]=$r['nsc_name_1'];
					if(isset($r['nsc_name_2'][0])) $t[]=$r['nsc_name_2'];
					if(isset($r['nsc_name_3'][0])) $t[]=$r['nsc_name_3'];
					if(isset($r['nsc_name_4'][0])) $t[]=$r['nsc_name_4'];
					?>
					<tr>
						<td class="text-center" style="position:relative"><a style="position:absolute;top:-80px;visibility: hidden;" id="cid_<?=html_escape($r['nsc_id'])?>"></a><?=html_escape($r['nsc_id'])?></td>
						<td class="text-center"><?=html_escape($r['nsc_depth'])?></td>
						<td class="text-center"><a href="#cid_<?=html_escape($r['nsc_id_1'])?>"><?=html_escape($r['nsc_name_1'])?></a></td>
						<td class="text-center"><a href="#cid_<?=html_escape($r['nsc_id_2'])?>"><?=html_escape($r['nsc_name_2'])?></a></td>
						<td class="text-center"><a href="#cid_<?=html_escape($r['nsc_id_3'])?>"><?=html_escape($r['nsc_name_3'])?></a></td>
						<td class="text-center"><a href="#cid_<?=html_escape($r['nsc_id_4'])?>"><?=html_escape($r['nsc_name_4'])?></a></td>
						<td class="text-left"><?=html_escape(implode(" > ",$t))?></td>
					</tr>
					<?
				endforeach;
				?>
			</tbody>
		</table>
	</div>
</div>
