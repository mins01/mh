<?
$ogp_url = $url;
if($opgs['og:url']){
	$ogp_url = $opgs['og:url'];
}
$ogp_locale = isset($ogp['og:locale'][0])?$ogp['og:locale']:'';

// print_r($opgs);
// exit;
?><!doctype html>
<html lang="<?=$ogp_locale?>">
<head>
	<title><?=isset($opgs['og:site_name'][0])?$opgs['og:site_name']:''?></title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<style>
	body{padding:5px; background-color: #fff; overflow: hidden;}
	.og-image{max-width:100%;max-height:150px;}
	a.url{display: block;}
	</style>
	</head>
<body>
	<div class="container-fluid">
		<div class="row">
			<a class="url" href="<?=html_escape($ogp_url)?>" target="_blank">
				<div>
			<?
			if(isset($opgs['og:image'][0])){
				if(is_array($opgs['og:images'])){
					$src = $opgs['og:images'][0];
				}else{
					$src = $opgs['og:image'];	
				}
				?><div class="col-xs-12 text-center"><img class="og-image"  src="<?=html_escape($src)?>"  class="img-rounded" alt="image"></div><?
			}
			?>
			<div class="col-xs-12  text-center">
				<?
				if(isset($opgs['og:site_name'][0])){
					?><h3><?=html_escape($opgs['og:site_name'])?></h3><?
				}
				?>
				<?
				if(isset($opgs['og:title'][0])){
					?><h4><?=html_escape($opgs['og:title'])?></h4><?
				}
				?>
				</div>

			</div>
			</a>
		</div>

	</div>

</body>
</html>
