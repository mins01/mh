<!doctype html>
<html lang="ko">
<head>
	<title><?=isset($opgs['og:site_name'][0])?$opgs['og:site_name']:''?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<style>
	body{padding:5px; background-color: #fff; overflow: hidden;}
	.og-image{max-width:100%;}
	</style>
	</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<?
			if(isset($opgs['og:image'][0])){
				$src = $opgs['og:image'];
				?><div class="col-xs-12 text-center"><a href="<?=html_escape($url)?>" target="_blank"><img class="og-image"  src="<?=html_escape($src)?>"  class="img-rounded" alt="image"></a></div><?
			}
			?>
			<div class="col-xs-12  text-center">
				<?
				if(isset($opgs['og:site_name'][0])){
					?><h3><a href="<?=html_escape($url)?>" target="_blank"><?=html_escape($opgs['og:site_name'])?></a></h3><?
				}
				?>
				<?
				if(isset($opgs['og:title'][0])){
					?><h4><a href="<?=html_escape($url)?>" target="_blank"><?=html_escape($opgs['og:title'])?></a></h4><?
				}
				?>
			</div>
		</div>

	</div>

</body>
</html>
