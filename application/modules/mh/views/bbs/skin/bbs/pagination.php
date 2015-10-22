<?
//$page,$max_page
//def_url

$def_url = preg_replace('|\?$|','',$def_url);

if(!isset($page)) $page = 1;
$page = (int)$page;
if(!isset($cut)) $cut = 5;

$st = floor(($page-1)/$cut)*$cut+1;
$ed = min($st+($cut-1),$max_page);
//echo $st,':',$ed,';',$max_page;
if($page>1){
	$first_url = str_replace('{{page}}',1,$def_url);
}else{
	$first_url = '';
}
if($st-1>1){
	$pre_url = str_replace('{{page}}',$st-1,$def_url);
}else{
	$pre_url = '';
}

if($ed<$max_page){
	$next_url = str_replace('{{page}}',$ed+1,$def_url);
}else{
	$next_url = '';
}
if($page<$max_page){
	$last_url = str_replace('{{page}}',$max_page,$def_url);
}else{
	$last_url = '';
}

?>


	<ul class="pagination">

		<? 
		$url = $first_url;
		if(isset($url[2])){
			$tmp_class = ''; 
			$aria_hidden = ''; 
			$tag = 'a'; 
		}else{
			$tmp_class = 'class="disabled"'; 
			$aria_hidden = 'aria-hidden="true"'; 
			$tag = 'span'; 
		}
		$text = '처음';
		$aria_label = 'First';
		?>
		<li <?=$tmp_class?>>
			<<?=$tag?> href="<?=html_escape($url)?>" aria-label="<?=html_escape($aria_label)?>">
				<span <?=$aria_hidden?>><?=html_escape($text)?></span>
			</<?=$tag?>>
		</li>
		<? 
		$url = $pre_url;
		if(isset($url[2])){
			$tmp_class = ''; 
			$aria_hidden = ''; 
			$tag = 'a'; 
		}else{
			$tmp_class = 'class="disabled"'; 
			$aria_hidden = 'aria-hidden="true"'; 
			$tag = 'span'; 
		}
		$text = '&laquo;';
		$aria_label = 'Previous';
		?>
		<li <?=$tmp_class?>>
			<<?=$tag?> href="<?=html_escape($url)?>" aria-label="<?=html_escape($aria_label)?>">
				<span <?=$aria_hidden?>><?=($text)?></span>
			</<?=$tag?>>
		</li>
		<?
		for($i = $st,$m = $ed;$i<=$m;$i++): 
			$tmp_class = $page==$i?'class="active"':'';
			$sr_only = $page==$i?'<span class="sr-only">(current)</span>':'';
			$url = str_replace('{{page}}',$i,$def_url);
		?>
				<li <?=$tmp_class?>><a href="<?=html_escape($url)?>"><?=($i),$sr_only?></a></li>
		<?
		endfor; 
		?>
		
		<? 
		$url = $next_url;
		if(isset($url[2])){
			$tmp_class = ''; 
			$aria_hidden = ''; 
			$tag = 'a'; 
		}else{
			$tmp_class = 'class="disabled"'; 
			$aria_hidden = 'aria-hidden="true"'; 
			$tag = 'span'; 
		}
		$text = '&raquo;';
		$aria_label = 'Next';
		?>
		<li <?=$tmp_class?>>
			<<?=$tag?> href="<?=html_escape($url)?>" aria-label="<?=html_escape($aria_label)?>">
				<span <?=$aria_hidden?>><?=($text)?></span>
			</<?=$tag?>>
		</li>
		
		<?
		$url = $last_url;
		if(isset($url[2])){
			$tmp_class = ''; 
			$aria_hidden = ''; 
			$tag = 'a'; 
		}else{
			$tmp_class = 'class="disabled"'; 
			$aria_hidden = 'aria-hidden="true"'; 
			$tag = 'span'; 
		}
		$text = '끝';
		$aria_label = 'Last';
		?>
		<li <?=$tmp_class?>>
			<<?=$tag?> href="<?=html_escape($url)?>" aria-label="<?=html_escape($aria_label)?>">
				<span <?=$aria_hidden?>><?=html_escape($text)?></span>
			</<?=$tag?>>
		</li>
	
	</ul>




