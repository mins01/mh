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
	<meta name="keywords" content="공대여자,웹,프로그래밍,DB,PHP,MySQL,ORACLE" />
	<meta name="classification" content="웹 프로그래밍" />

	<meta name="viewport" content="width=device-width, initial-scale=1.0" />

	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" class="mb_wysiwyg_head_css">
	<!-- 부가적인 테마 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css"  class="mb_wysiwyg_head_css">
	<!-- Jquery : not support < IE9-->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

	<!-- https://angularjs.org/ -->
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-sanitize.js"></script>




	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/vendor/mins01.com/m-onoff.css">
	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/bootstrap/bootstrap-select.min.css">
	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/mh.css">

	<script src="<?=SITE_URI_PREFIX?>js/bootstrap/bootstrap-select.min.js"></script>
	<script src="<?=SITE_URI_PREFIX?>js/mh_lib.js"></script>
	<script src="<?=SITE_URI_PREFIX?>js/mh_def.js"></script>


	<!-- meta og -->

	<? if(isset($layout_og_title[0])) : ?><meta property="og:title" content="<?=html_escape($layout_og_title)?>">
	<? endif; ?>
	<? if(isset($layout_og_description[0])) : ?><meta property="og:description" content="<?=html_escape($layout_og_description)?>">
	<? endif; ?>
	<? if(isset($layout_og_image[0])) : ?><meta name="og:image" content="<?=html_escape($layout_og_image)?>">
	<? endif; ?>
	<? if(isset($layout_og_image_width[0])) : ?><meta property="og:image:width" content="<?=html_escape($layout_og_image_width)?>">
	<? endif; ?>
	<? if(isset($layout_og_image_height[0])) : ?><meta property="og:image:height" content="<?=html_escape($layout_og_image_height)?>" />
	<? endif; ?>
	<? if(isset($layout_og_site_name[0])) : ?><meta property="og:site_name" content="<?=html_escape($layout_og_site_name)?>" />
	<? endif; ?>
	<? if(isset($layout_og_type[0])) : ?><meta property="og:type" content="<?=html_escape($layout_og_type)?>">
	<? endif; ?>

	<!-- //meta og -->

	<?=$head_contents?>


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->

	<? //print_r($menu_tree); ?>
</head>
<body>
<? if(!$hide):?>
	<nav class="navbar navbar-default  navbar-absolute-top" role="navigation">
		<div class="container-fluid ">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="/" style="margin: 0;padding: 3px"><img src="/img/logo.gif" style="max-height: 100%;" alt="logo image"></a>
				<a class="navbar-brand hidden-sm hidden-md hidden-lg " href="<?=SITE_URI_PREFIX?>">메인</a>
				<a class="navbar-brand hidden-sm hidden-md hidden-lg " href="<?=SITE_URI_PREFIX?>tech">기술</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-left">
					<? foreach($menu_tree[0]['child'] as $mr):
						if($mr['mn_hide']!='0'){continue;}
						$class = $mr['active']?'class="active"':'';
					?>
					<li <?=$class?>><a href="<?=html_escape($mr['url'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a></li>
					<? endforeach; ?>
				</ul>
				<div class="navbar-right">
					<? if(!$logedin):?>
					<p class="navbar-text text-right ">
					<button type="button" class="btn btn-success btn-xs" onclick="window.open('<?=base_url('member/join')?>','_self')">회원가입</button>
					<button type="button" class="btn btn-warning btn-xs" onclick="window.open('<?=base_url('member/search_id')?>','_self')">계정 찾기</button>
					<button type="button" class="btn btn-primary btn-xs" onclick="window.open('<?=base_url('member/login')?>','_self')">로그인</button>
					</p>
					<? else: ?>
					<p class="navbar-text text-right "><a href="<?=base_url('member/user_info')?>"><span class="glyphicon glyphicon-user"></span><?=html_escape($login_label)?></a>님 <button class="btn btn-info btn-xs" onclick="window.open('<?=base_url('member/logout')?>','_self')">로그아웃</button></p>
					<? endif;?>
				</div>
			</div><!--/.nav-collapse -->
		</div>
	</nav>

	<div style="width:320px;margin:2px auto" class="google_ad"><?=GoogleAds::print_google_adsense_320x50()?></div>

	<div class="container-fluid contents">

	<?=$top_html?>
<? endif; ?>
