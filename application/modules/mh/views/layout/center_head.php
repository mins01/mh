<!doctype html>
<html lang="ko">
<head>
	<title><?=html_escape($title)?></title>
	<meta charset="utf-8">
	<meta http-equiv="Content-Script-Type" content="text/javascript">
	<meta http-equiv="Content-Style-Type" content="text/css">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta name="description" content="<?=html_escape($title)?>" />
	<meta name="keywords" content="<?=html_escape($layout_keywords)?>" />
	<meta name="classification" content="웹 프로그래밍" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<script src="http://www.mins01.com/js/ForGoogle.js?t=<?=REFLESH_TIME?>"></script>
	<!-- google analytics -->
	<script>ForGoogle.analytics()</script>

	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" class="mb_wysiwyg_head_css">
	<!-- 부가적인 테마 -->
	<!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css"  class="mb_wysiwyg_head_css"> -->
	<!-- Jquery : not support < IE9-->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<!-- https://angularjs.org/ -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-sanitize.js"></script>




	<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>css/vendor/mins01.com/m-onoff.css?t=<?=REFLESH_TIME?>">
	<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>css/vendor/hoverZoom/hoverZoom.css?t=<?=REFLESH_TIME?>">
	<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>css/bootstrap/bootstrap-select.min.css?t=<?=REFLESH_TIME?>">
	<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>css/mh.css?t=<?=REFLESH_TIME?>">

	<script src="<?=SITE_URI_ASSET_PREFIX?>js/bootstrap/bootstrap-select.min.js?t=<?=REFLESH_TIME?>"></script>
	<script src="<?=SITE_URI_ASSET_PREFIX?>js/mh_lib.js?t=<?=REFLESH_TIME?>"></script>
	<script src="<?=SITE_URI_ASSET_PREFIX?>js/mh_def.js?t=<?=REFLESH_TIME?>"></script>
	<!-- seo_contents -->
	<?=$seo_contents?>
	<!-- head_contents -->
	<?=$head_contents?>
	<!-- head_banners -->
	<?=$head_banners?>
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<? //print_r($menu_tree); ?>
	<style>
		.flex-center-center{
			display: flex ;
			justify-content: center;  /* 가로 중앙 */
			align-items: center; /* 세로 중앙 */
		}
	</style>
</head>
<body>
	<!-- 배너용 기준위치 -->
	<div class="container-fluid mh-banner-pos-box">
		<div id="banner_pos_top"></div>
	</div>
	<!-- //배너용 기준위치 -->
<? if(!$hide):?>
	<div class="flex-center-center" style="height:100vh">
		<div class="container">
		<?=$top_html?>
<? endif; ?>
