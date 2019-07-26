<!-- meta seo -->
	<? if(isset($layout_og_title[0])) : ?><meta property="og:title" content="<?=html_escape($layout_og_title)?>">
	<? endif; ?>
	<? if(isset($layout_og_description[0])) : ?><meta property="og:description" content="<?=html_escape($layout_og_description)?>">
	<? endif; ?>
	<? if(isset($layout_og_image[0])) : ?><meta property="og:image" content="<?=html_escape($layout_og_image)?>">
	<? endif; ?>
	<? if(isset($layout_og_image_width[0])) : ?><meta property="og:image:width" content="<?=html_escape($layout_og_image_width)?>">
	<? endif; ?>
	<? if(isset($layout_og_image_height[0])) : ?><meta property="og:image:height" content="<?=html_escape($layout_og_image_height)?>" />
	<? endif; ?>
	<? if(isset($layout_og_site_name[0])) : ?><meta property="og:site_name" content="<?=html_escape($layout_og_site_name)?>" />
	<? endif; ?>
	<? if(isset($layout_og_type[0])) : ?><meta property="og:type" content="<?=html_escape($layout_og_type)?>">
	<? endif; ?>
	
	<link rel="canonical" href="<?=html_escape($canonical_url)?>">
<!-- //meta seo -->