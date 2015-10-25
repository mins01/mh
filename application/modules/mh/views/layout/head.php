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



	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->
	
	
	<link rel="stylesheet" href="<?=base_url()?>css/bootstrap/bootstrap-select.min.css">
	<link rel="stylesheet" href="<?=base_url()?>css/mh.css">
	
	<script src="<?=base_url()?>js/bootstrap/bootstrap-select.min.js"></script>
	<script src="<?=base_url()?>js/mh_lib.js"></script>
	<script src="<?=base_url()?>js/mh_def.js"></script>
	
	
	<?=$head_contents?>
	<? //print_r($menu_tree); ?>
</head>
<body>
<? if(!$hide):?>
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="<?=base_url()?>">HOME</a>
			</div>
			<div id="navbar" class="collapse navbar-collapse">
				<ul class="nav navbar-nav navbar-left">
					<? foreach($menu_tree['child'] as $mr): 
						$class = $mr['mn_uri']==$menu['mn_uri']?'class="active"':'';
					?>
					<li <?=$class?>><a href="<?=html_escape($mr['url'])?>"><?=html_escape($mr['mn_text'])?></a></li>
					<? endforeach; ?>
				</ul>
				<ul class="nav navbar-nav pull-right">
					<? if(!$logedin):?>
					<li class="text-right"><button class="btn btn-default navbar-btn" onclick="window.open('<?=base_url().'join'?>','_self')">회원가입</button> <button class="btn btn-default navbar-btn" onclick="window.open('<?=base_url().'search_id'?>','_self')">아이디/비밀번호 찾기</button> <button class="btn btn-default navbar-btn" onclick="window.open('<?=base_url().'login'?>','_self')">로그인</button></li>
					<? else: ?>
					<li><span class="navbar-text glyphicon glyphicon-user"> <a href="<?=base_url().'user_info'?>"><?=html_escape($login_label)?></a>님</span> <button class="btn btn-default navbar-btn" onclick="window.open('<?=base_url().'logout'?>','_self')">로그아웃</button></li>
					<? endif;?>
				</ul>
			</div><!--/.nav-collapse -->
		</div>
	</nav>
	
	<div class="container-fluid">
<? endif; ?>