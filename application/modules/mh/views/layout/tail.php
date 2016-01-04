<?
//var_dump($login_info);
?>
<? if(!$hide): ?>
	</div><!-- /.container-fluid -->
	<footer>
		<div class="container-fluid" style="background-color:#fff;color:#999">
			<div class="text-right"> 
			<? if(isset($login_info['is_admin']) && $login_info['is_admin']): ?>
			<a href="<?=html_escape(ADMIN_URI_PREFIX)?>" target="_blank" class="label label-warning">관리자</a>
			<?  endif; ?>
			<small>[{elapsed_time} sec]/[{memory_usage} Byte]</small></div>
		</div>
	</footer>
<?=$tail_contents?>

<? endif; ?>
</body>
</html>