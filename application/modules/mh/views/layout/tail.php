<?
//var_dump($login_info);
?>
<? if(!$hide): ?>
	</div><!-- /.container-fluid -->
	<footer>
		<div class="container-fluid" style="background-color:#fff;color:#999">
			<div style="margin:2px auto" class="google_ad"><script>
			ForGoogle.ads.adsReactive02()
			</script></div>
			<div class="text-right">
			<small>
			[{elapsed_time} sec]/[{memory_usage} Byte]
			<? if(isset($login_info['is_admin']) && $login_info['is_admin']): ?>
			<a href="<?=html_escape(ADMIN_URI_PREFIX)?>" target="_blank" class="label label-warning">관리자</a>
			<?  endif; ?>
			</small>
			</div>


		</div>
	</footer>
<?=$tail_contents?>
<? endif; ?>
<!-- 배너용 기준위치 -->
<div class="container-fluid mh-banner-pos-box">
	<div id="banner_pos_bottom"></div>
</div>
<!-- //배너용 기준위치 -->
</body>
</html>
