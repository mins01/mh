<?
require(dirname(__FILE__).'/menu.php');
?>
<?
// $ca_tree
?>
<?
$dir_url = dirname($conf['base_url']);
?>
<!-- vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/StickyOnTable.js?t=<?=REFLESH_TIME?>"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_StickyOnTable/stickyOnTable.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<link href="<?=SITE_URI_ASSET_PREFIX?>item_search/item_search.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<link href="<?=SITE_URI_ASSET_PREFIX?>item_search/common_keyword.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<script src="<?=SITE_URI_ASSET_PREFIX?>item_search/rel_keyword.js?t=<?=REFLESH_TIME?>"></script>


<script>
var rs_keywords = <?=json_encode($rs_keywords)?>;
var ajax_recommend_keyword_url = './ajax_recommend_keyword';
var relKeywordApp = null;
$(function(){
	relKeywordApp = rel_keyword_init();
	relKeywordApp.rs_keywords =rs_keywords
});
</script>

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
<div  class="container-fluid text-center ">
	<h1 style="font-size:32px"><b>추천 연관 키워드</b></h1>
</div>
<div class="container-fluid " >
	<div>
		<h3>입력</h3>
		<form action="" method="get">
			<div class="input-group input-group-lg">
				<span class="input-group-addon">키워드</span>
				<input type="text" name="keyword" class="form-control" placeholder="키워드" maxlength="100" value="<?=html_escape($keyword)?>"  >
				<span class="input-group-btn">
					<button class="btn btn-info" type="submit">분석</button>
				</span>
			</div>
		</form>
	</div>
</div>
<div  class="container-fluid " id="relKeywordApp">
	<div>
		<hr>
		<h3>추천 키워드</h3>
		<div>
			<form action="parse_keyword" method="get" target="parse_keyword">
				<div class="input-group">
					<span class="input-group-addon">선택된 키워드 (<span v-text="selected_keyword_length"></span>)</span>
					<input type="text" name="keyword_str" class="form-control" v-model="selected_keyword" placeholder="선택된 키워드가 표기 됩니다. 중복은 제거됩니다.">
					<span class="input-group-btn">
						<button class="btn btn-success" type="submit">분석</button>
					</span>
				</div>
			</form>
		</div>
		<div>
			<div class="input-group">
				<span class="input-group-addon ">필터 키워드</span>
				<input type="text" v-model="filter_keyword" class="form-control" placeholder="추천 키워드 결과에서 필터 키워드가 포함된 것만 표기 됩니다.">
				<span class="input-group-btn">
					<button class="btn btn-warning" type="button">필터</button>
				</span>
			</div>
		</div>
		<div class="sot " style="height:500px;max-height:50vh" >
			<table class="table table-hover sot-table " style="margin:0 auto;min-width: 600px;">
				<thead>
					<tr>
						<th width="" class="sot-top text-center" >키워드</th>
						<th width="80" class="sot-top text-center">
							연관 랭크
							<button class="btn btn-default btn-xs btn_sort" id="btn_kr_rank" data-sort_key="kr_rank" data-order_type="0" @click="btn_sort(event)"></button>
						</th>
						<th width="80" class="sot-top text-center">
							월검색수
							<button class="btn btn-default btn-xs btn_sort" id="btn_kr_monthlyQcCnt" data-sort_key="kr_monthlyQcCnt" data-order_type="0" @click="btn_sort(event)"></button>
						</th>
						<th width="80" class="sot-top text-center">
							상품수
							<button class="btn btn-default btn-xs btn_sort" id="btn_kr_search_total_shop" data-sort_key="kr_search_total_shop" data-order_type="0" @click="btn_sort(event)"></button>
						</th>
						<th width="80" class="sot-top text-center" >
							경쟁강도
							<button class="btn btn-default btn-xs btn_sort" id="btn_kr_competitive_strength" data-sort_key="kr_competitive_strength" data-order_type="1" @click="btn_sort(event)"></button>
						</th>
						<th width="100" class="sot-top text-center">정보수집일</th>
					</tr>
				</thead>
				<tbody>
					<template v-for="(r,k) in rs_keywords" >
						<tr v-if="filter_keyword==='' || r.kr_keyword.indexOf(filter_keyword.toUpperCase())!==-1" class="text-center" style="vertical-align:middle">
							<td class="add_selected_keyword text-bold" @click="add_selected_keyword(r.kr_keyword)">{{r.kr_keyword}}</div></td>
							<td>{{r.kr_rank}}</td>
							<td>{{r.kr_monthlyQcCnt}}</td>
							<td>{{r.kr_search_total_shop}}</td>
							<td>
								<span v-if="r.kr_competitive_strength" :class="parseFloat(r.kr_competitive_strength) <= 1.6?'reco_keyword':''">{{parseFloat(r.kr_competitive_strength).toFixed(parseFloat(r.kr_competitive_strength)<=10?3:0)}}</span>
								<span v-else>정보 수집 전</span>
							</td>
							<td><small>{{  r.kr_update_at>'2000-01-01 00:00:00'?Math.round(((new Date()).getTime() - Date.parse(r.kr_update_at))/1000/86400,2)+'일 전':'수집 전'  }}</small></td>
						</tr>
					</template>
				</tbody>
			</table>
		</div>
	</div>

</div>
