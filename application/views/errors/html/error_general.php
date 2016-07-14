<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!doctype html>
<html lang="ko">
	<head>
	<title>ERROR</title>

	<style>
	.unit_icon{
		display: inline-block;
		overflow: visible;
		height: 300px;
		width: 300px;
	}
	.unit_icon .circle{
		animation-duration: 2s;
		animation-name: slidein;
		animation-iteration-count: 1;
		animation-direction: alternate;

		border-radius: 50%;
		border:30px solid #fc5d56;
		background-color: #edd776;
		width:240px;height: 240px;
		!box-sizing: border-box;
		box-sizing: content-box;

		font-size: 240px;
		line-height: 1.2em;
		text-align: center;
	}

	.unit_icon .exclamation-mark{
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

	@keyframes slidein {
		from {
			margin-top: -100%;
		}
		45%{
			
			margin-left: -10%;
			margin-top: 40%;
			transform: rotate(10deg);
		}
		50%{
			margin-left: 10%;
			margin-top: -40%;
			transform: rotate(-10deg);
		}
		55%{
			margin-left: -10%;
			margin-top: -55%;
		}
		60%{
			margin-left: 0%;
			transform: rotate(0deg);
		}
		to {
		margin-top: 0%;
		}
	}
	/* ---- */
	h1{
		text-align: center; color: red;
		text-shadow: 2px 2px 5px #f99;
	}
	#container{
		text-align: center; color: black;
	}

	body{min-width: 320px;}

	</style>

	</head>
	<body>
		<div class="" style="text-align:center;">
			<div class="unit_icon">
				<div class="circle">
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
		</div>
	</body>
</html>