<?

function generate_paging($get,$max_page,$uri='',$i_conf=array()){
	$conf = array_merge(array(
		'cut'=>10
	),$i_conf);
	
	$page = $get['page'];
	$tmp_get = $get;
	// $page_s = floor(($PCFG['page']-1)/$PCFG['pnum'])*$PCFG['pnum']+1;	//루프시작
	// $page_e = (floor(($PCFG['page']-1)/$PCFG['pnum'])+1)*$PCFG['pnum'];	//루프마지막
	$st = floor(($page-1)/$conf['cut'])*$conf['cut']+1;
	$ed = min($st+9,$max_page);
	//echo $st,':',$ed,';',$max_page;
	if($page>1){
		$tmp_get['page']=1;
		$first_url = $uri.'?'.http_build_query($tmp_get);
	}else{
		$first_url = '';
	}
	if($st-1>1){
		$tmp_get['page']=$st-1;
		$pre_url = $uri.'?'.http_build_query($tmp_get);
	}else{
		$pre_url = '';
	}
	
	if($ed<$max_page){
		$tmp_get['page']=$ed+1;
		$next_url = $uri.'?'.http_build_query($tmp_get);
	}else{
		$next_url = '';
	}
	if($page<$max_page){
		$tmp_get['page']=$max_page;
		$last_url = $uri.'?'.http_build_query($tmp_get);
	}else{
		$last_url = '';
	}
	$r_arr = array();
	
	$r_arr[] = '<ul class="pagination">';

	$tmp_class = isset($first_url[2])?'':'class="disabled"';
	$r_arr[] = '<li '.$tmp_class.'>';
	$r_arr[] = '<a href="'.htmlspecialchars($first_url).'" aria-label="First">';
	$r_arr[] = '<span aria-hidden="true">&laquo;</span>';
	$r_arr[] = '</a>';
	$r_arr[] = '</li>';

	$tmp_class = isset($pre_url[2])?'':'class="disabled"';
	$r_arr[] = '<li '.$tmp_class.'>';
	$r_arr[] = '<a href="'.htmlspecialchars($pre_url).'" aria-label="Previous">';
	$r_arr[] = '<span aria-hidden="true">&laquo;</span>';
	$r_arr[] = '</a>';
	$r_arr[] = '</li>';
		for($i = $st,$m = $ed;$i<=$m;$i++): 
			$tmp_class = $page==$i?'class="active"':'';
			$tmp_get['page']=$i;
			$url = $uri.'?'.http_build_query($tmp_get);
			$r_arr[] = '<li '.$tmp_class.'><a href="'.htmlspecialchars($url).'">'.html_escape($i).'</a></li>';
		endfor; 
	$tmp_class = isset($next_url[2])?'':'class="disabled"';
	
	$r_arr[] = '<li '.$tmp_class.'>';
	$r_arr[] = '<a href="'.htmlspecialchars($next_url).'" aria-label="Next">';
	$r_arr[] = '<span aria-hidden="true">&raquo;</span>';
	$r_arr[] = '</a>';
	$r_arr[] = '</li>';

	$tmp_class = isset($last_url[2])?'':'class="disabled"';
	$r_arr[] = '<li '.$tmp_class.'>';
	$r_arr[] = '<a href="'.htmlspecialchars($last_url).'" aria-label="Last">';
	$r_arr[] = '<span aria-hidden="true">&raquo;</span>';
	$r_arr[] = '</a>';
	$r_arr[] = '</li>';
	
		$r_arr[] = '</ul>';
	return implode("\n",$r_arr);
}


function cvt_text($text,$text_type){
	switch($text_type){
		case 't':
			return nl2br(html_escape($text));
		break;
		case 'p':
			return nl2br($text);
		break;
		case 'h':
			return ($text);
		break;
		case 'r':
			return ($text);
		break;
	}
	return $text;
}









