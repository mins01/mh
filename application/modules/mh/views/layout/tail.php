<?
//var_dump($login_info);
?>
<? if(!$hide): ?>
	</div><!-- /.container-fluid -->
	<footer>
		<div class="container-fluid" style="background-color:#fff;color:#999">
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
<div style="width:300px;margin:2px auto" class="google_ad"><script>
ForGoogle.ads.ads300x250()
</script></div>
<? endif; ?>
</body>
</html>