<?
require(dirname(__FILE__).'/menu.php');
?>
<?
// $keyword
?>
<?
$dir_url = dirname($conf['base_url']);
?>
<!-- vue.js -->
<script src="https://cdn.jsdelivr.net/npm/vue"></script>
<link href="<?=SITE_URI_ASSET_PREFIX?>item_search/item_search.css?t=<?=REFLESH_TIME?>" rel="stylesheet">
<script src="<?=SITE_URI_ASSET_PREFIX?>item_search/parse_keyword.js?t=<?=REFLESH_TIME?>"></script>

<script>
let vpka;
$(function(){
	let keyword_str = <?=json_encode($keyword_str)?>;
	let keywords = parse_keyword.split_keyword(keyword_str).slice(0,10);
	keyword_str =  keywords.join(',');
	// let keywords = parse_keyword.split_keyword(keyword);
	// let kp = parse_keyword.pickup_key_point(keywords);
	// let reco_names = parse_keyword.recommend_names(keywords, kp);
	// console.log(reco_names);

	vpka = init_parseKeywordApp();
	vpka.keyword = keyword_str
	vpka.parseKeyword();
})
</script>
<div  class="container-fluid text-center ">
	<h1 style="font-size:32px"><b>키워드 분석</b></h1>
</div>
<div class="container-fluid " id="parseKeywordApp">
	<div>
		<h3>입력</h3>
		<form action="" method="get" onsubmit="return false;" @submit="return formSubmit(event);">
			<div class="input-group input-group-lg">
				<span class="input-group-addon">키워드</span>
				<input type="text" name="keyword_str" class="form-control" placeholder="키워드1,키워드2,키워드3,키워드4" maxlength="100" v-model="keyword">
				<span class="input-group-btn">
					<button class="btn btn-info" type="submit">분석</button>
				</span>
			</div>
		</form>
	</div>
	<!-- <div>
		<hr>
		<h3>키포인트</h3>
		<ul class="list-group" v-if="kps.length>0">
			<template v-for="kp in kps">
				<li class="list-group-item"><span class="badge">{{kp[1]}}</span>	{{kp[0]}}</li>
			</template>
		</ul>
		<div v-else>
			분석을 진행해주세요.
		</div>
	</div> -->
	<div>
		<hr>
		<h3>추천 상품명</h3>
		<ul class="list-group list-unstyled" v-if="reco_names.length>0">
			<template v-for="r in reco_names.slice(0,21)">
				<li style="padding:0.2em 0" >
					<div class="input-group">
						<span class="input-group-addon">{{r[0]}}</span>
						<input type="text" name="keyword" class="form-control" :value="r[1]" onfocus="this.select()">
					</div>
				</li>
			</template>
		</ul>
		<div v-else>
			분석을 진행해주세요.
		</div>
	</div>
</div>
