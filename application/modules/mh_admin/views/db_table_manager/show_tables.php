<?
// print_r($rows);
?>
<div class="list-group">
	<? 
		foreach($rows as $name): 
		$qstrs = array('tbl_name'=>$name,'mode'=>'lists');
		$qstr = '?'.http_build_query($qstrs);
	?>
  	<a href="<?=html_escape($base_url.$qstr)?>" class="list-group-item"><?=$name?></a>
	<? endforeach; ?>
</div>