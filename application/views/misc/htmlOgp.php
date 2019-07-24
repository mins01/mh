<?
$ogp_url = $url;
if(isset($opgs['og:url'][0])){
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
	<base href="<?=html_escape($ogp_url)?>" />
	<!-- 합쳐지고 최소화된 최신 CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css">
	<style>
	html{padding:0;margin:0;height: 100%;}
	body{padding:0;margin: 0; background-color: #fff; overflow: hidden; height:100%;}
	.og-image{width:100%;height:100%; position: fixed;top:0;left: 0;right: 0;bottom: 0; z-index: 10;}
	a.url{display: block; position: relative; color: #000}
	.text-ellipsis{text-overflow: ellipsis; white-space: nowrap; width: 100%; overflow: hidden;}
	.og-site_name{font-size:larger}
	.og-title{}
	.og-description{font-size:smaller}
	.box-info{position: fixed;top: auto;bottom: 0;right:0;left:0; background-color: rgba(255,255,255,0.8);z-index: 20;}
	</style>
	</head>
<body>
	<div class="">
			<a class="url" href="<?=html_escape($ogp_url)?>" target="_blank" >
				<div>
			<?
			if(isset($opgs['og:video:url'][0])){
				$poster = isset($opgs['og:image'][0])?'poster="'.html_escape($opgs['og:image']).'"':'';
			?>
			<div class="text-center">
				<iframe width="100%" height="150px" border="0" src="<?=html_escape($opgs['og:video:url'])?>" style="border-style:none;">
				  Your browser does not support the iframe tag.
				</iframe>
			</div>
			<?
			}else if(isset($opgs['og:image'][0])){
				$src = $opgs['og:image'];
				?><div class="text-center"><img class="og-image"  src="<?=html_escape($src)?>"  class="img-rounded" alt="image"></div><?
			}
			?>
			<div class="text-center box-info">
				<?
				if(isset($opgs['og:site_name'][0])){
					?><div class="og-site_name text-ellipsis" title="<?=html_escape($opgs['og:site_name'])?>"><?=html_escape($opgs['og:site_name'])?></div><?
				}
				?>
				<?
				if(isset($opgs['og:title'][0])){
					?><div class="og-title text-ellipsis" title="<?=html_escape($opgs['og:title'])?>"><?=html_escape($opgs['og:title'])?></div><?
				}
				?>
				<?
				if(isset($opgs['og:description'][0])){
					?><div class="og-description text-ellipsis" title="<?=html_escape($opgs['og:description'])?>"><?=html_escape($opgs['og:description'])?></div><?
				}
				?>
				</div>

			</div>
			</a>
		

	</div>
	<!-- 
	<? print_r($opgs); ?>
	-->
</body>
</html>

