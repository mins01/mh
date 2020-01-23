<? if(!$use_banners): ?>
<? else: ?>

  <!-- 배너 처리부 -->
  <link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>css/mh_banners.css?t=<?=REFLESH_TIME?>">
  <script src="<?=SITE_URI_PREFIX?>banners/js?t=<?=REFLESH_TIME?>"></script>
  <script src="<?=SITE_URI_ASSET_PREFIX?>js/mh_banners.js?t=<?=REFLESH_TIME?>"></script>
  <script>
    mh_banners.attach_window_load();
  </script>
  <!-- 배너 처리부 -->

<? endif; ?>
