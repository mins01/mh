암호화 정보 디코딩
<form action="" method="post">
	암호화된 회원정보<input type="text" name="enc_str" value="<?=html_escape($enc_str)?>"><br>
	<button type="submit">디코딩</button>
	<hr>
	<pre><? var_dump($dec_arr) ?></pre>
</form>