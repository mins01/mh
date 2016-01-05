<!doctype html>
<html lang="ko">
<head>
<title><?=html_escape($title)?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<meta name="description" content="<?=html_escape($title)?>" />
	<!--
	<meta name="keywords" content="공대여자,웹,프로그래밍,DB,PHP,MySQL,ORACLE,SD건담캡슐파이터,캡파,SD건담,sdgo,sdgd" />
	<meta name="classification" content="웹 프로그래밍" />
	-->
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	
	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<!-- 부가적인 테마 -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css">
	<!-- Jquery : not support < IE9-->
	<script src="//code.jquery.com/jquery-2.1.4.min.js"></script>
	<!-- 합쳐지고 최소화된 최신 자바스크립트 -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>
	
	<!-- https://angularjs.org/ -->
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular.min.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-sanitize.js"></script>
	<script src="//ajax.googleapis.com/ajax/libs/angularjs/1.4.7/angular-route.js"></script>


	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	
	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/bootstrap/bootstrap-select.min.css">
	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/mh.css">
	<link rel="stylesheet" href="<?=SITE_URI_PREFIX?>css/mh_admin.css">
	
	<script src="<?=SITE_URI_PREFIX?>js/bootstrap/bootstrap-select.min.js"></script>
	<script src="<?=SITE_URI_PREFIX?>js/mh_lib.js"></script>
	<script src="<?=SITE_URI_PREFIX?>js/mh_def.js"></script>
	
	
	<?=$head_contents?>
	<? //var_dump($menu_tree); ?>
</head>
<body>
<? if(!$hide):?>
	<nav class="navbar navbar-default ">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=ADMIN_URI_PREFIX?>">HOME</a>
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
					-
					</p>
					<? else: ?>
					<p class="navbar-text text-right "><strong><span class="glyphicon glyphicon-user"></span> <?=html_escape($login_label)?></strong>님  <button class="btn  btn-info btn-xs" onclick="window.open('<?=ADMIN_URI_PREFIX.'logout'?>','_self')">로그아웃</button></p>
					<? endif;?>
				</div>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
	
	<div class="container-fluid contents">
		<div class="row">
			<nav class="col-lg-2 col-md-3">
				<div class="list-group">
				<? 
					if(isset($menu['breadcrumbs'][1])):
						$tmenu = $menu_rows[$menu['breadcrumbs'][1]];//1단계 메뉴 기준으로 출력한다.
						foreach($tmenu['child'] as $k=>$mr): 
							if($mr['mn_hide']!='0'){continue;}
							$class = $mr['active']?'active':'';
				?>
					
						<a class="list-group-item  <?=$class?>" href="<?=html_escape($mr['url'])?>" <?=$mr['mn_attr']?>><?=html_escape($mr['mn_text'])?></a>
					
				<? 
						endforeach;
					endif;
				?>
				
				<?
					if(!isset($tmenu['child'][0])):
					?><a class="list-group-item active" href="" ><?=html_escape($menu['mn_text'])?></a><?
					endif;
				?>
				</div>
			</nav>

			
			
			<div class="col-lg-10 col-md-9">
				<h3 class="text-right"><?=$top_html?></h3>
	
	
	
	
	
	
<? endif; ?>