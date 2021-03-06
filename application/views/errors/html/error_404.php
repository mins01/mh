<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="ko">
	<head>
	<title>404 Page Not Found</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<style>
	.icon-box{
		text-align: center;
		height: 300px;
	}
	.icon-unit{
		display: inline-block;
		overflow: visible;
		height: 300px;
		width: 300px;
		border:0px solid black;
	}
	.icon-unit .circle{

		border-radius: 50%;
		border:30px solid #fc5d56;
		background-color: #edd776;
		width:240px;height: 240px;
		!box-sizing: border-box;
		box-sizing: content-box;

		font-size: 240px;
		line-height: 1em;
		vertical-align: top;
		text-align: center;
	}
	.animation .animation-target{
		animation-duration: 2s;
		animation-name: rolypoly;
		animation-iteration-count: 1;

	}
	.animation:hover .animation-target{
		animation-iteration-count: infinite;
	}

	.icon-unit .exclamation-mark{
		margin-top:20px;
		display: inline-block;
	}
	.exclamation-mark .exclamation-mark-1{
		margin: 0 auto;
		width:60px;height: 60px;
		background-color: #2b2b2b;
		border-radius: 50%;
		margin-bottom: -30px;
	}

	.exclamation-mark .exclamation-mark-2{
		margin: 0 auto;
		width: 30px;
		height: 0;
		border-left: 15px solid transparent;
		border-right: 15px solid transparent;
		border-top: 100px solid #2b2b2b;
		margin-bottom: -15px;
	}
	.exclamation-mark .exclamation-mark-3{
		margin: 0 auto;
		width:30px;height: 30px;
		background-color: #2b2b2b;
		border-radius: 50%;
	}
	.exclamation-mark .exclamation-mark-4{
		margin: 0 auto;
		margin-top:10px;
		width:40px;height: 40px;
		background-color: #2b2b2b;
		border-radius: 50%;
	}

	@keyframes rolypoly {
		from {margin-left: 0%;transform: rotate(0deg);}
		7%{margin-left: 10%; transform: rotate(10deg);}
		14%{margin-left: -10%; transform: rotate(-10deg);}
		21%{margin-left: 6.67%; transform: rotate(6.67deg);}
		29%{margin-left: -6.67%; transform: rotate(-6.67deg);}
		36%{margin-left: 4.44%; transform: rotate(4.44deg);}
		43%{margin-left: -4.44%; transform: rotate(-4.44deg);}
		50%{margin-left: 2.96%; transform: rotate(2.96deg);}
		57%{margin-left: -2.96%; transform: rotate(-2.96deg);}
		64%{margin-left: 1.98%; transform: rotate(1.98deg);}
		71%{margin-left: -1.98%; transform: rotate(-1.98deg);}
		79%{margin-left: 1.32%; transform: rotate(1.32deg);}
		86%{margin-left: -1.32%; transform: rotate(-1.32deg);}
		93%{margin-left: 0.00%; transform: rotate(0.00deg);}
		100%{margin-left: 0.00%; transform: rotate(0.00deg);}
		to {margin-left: 0%;transform: rotate(0deg);}
	}

	/* ---- */
	h1{
		text-align: center; color: red;
		text-shadow: 2px 2px 5px #c33;
	}
	#container{
		text-align: center; color: black;
	}

	body{min-width: 320px;}

	</style>

	</head>
	<body>
		<div class="icon-box animation" >
			<div class="icon-unit animation-target">
				<div class="circle ">
					<div class="exclamation-mark">
						<div class="exclamation-mark-1"></div>
						<div class="exclamation-mark-2"></div>
						<div class="exclamation-mark-3"></div>
						<div class="exclamation-mark-4"></div>
					</div>
				</div>
			</div>
		</div>
		<div id="container">
			<h1><?php echo $heading; ?></h1>
			<?php echo $message; ?>
			<h2><a href="/">[root]</a></h2>
		</div>
	</body>
</html>
