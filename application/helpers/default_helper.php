<?
//-- base_url에서 /로 시작할 경우 따로 처리하도록 한다.
function mh_base_url($url){
	if(isset($url[0]) && $url[0]=='/'){
		return $url;
	}else{
		return base_url($url);
	}
}
function mh_get_url($url,$get,$appendArr=''){
	// parse_str($appendStr, $appendArr);

	$get = remove_empty(array_merge($get,$appendArr));
	return $url.'?'.http_build_query($get);
}

function remove_empty($array) {
  return array_filter($array, '_remove_empty_internal');
}

function _remove_empty_internal($value) {
  return !empty($value) || $value === 0;
}


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



//페이지 값으로 offset 계산
function get_offset_by_page($page,$limit=10){
	if(!isset($page) || !is_numeric($page) || $page < 0){
			$page = 1;
	}
	$page = (int)$page;
	//$limit = $this->bm_row['bm_page_limit'];
	//$limit = 5;
	$offset = ($page-1)*$limit;
	return $offset;
}
function pretty_json_encode($res){
	if(defined('JSON_UNESCAPED_UNICODE')){
		return json_encode($res,JSON_UNESCAPED_UNICODE|JSON_PRETTY_PRINT);
	}else{
		return json_encode($res);
	}
}
function exit_json($res){
	header('Content-Type: application/json');
	echo pretty_json_encode($res);
	exit();
}

// onoff
function print_onoff($name,$val,$label_on='사용',$label_off='금지',$label=''){
	if(isset($label[0])) $label .= ' ';
	?>
	<label class="m-onoff m-onoff-success m-with-label btn btn-success"><input type="radio" name="<?=html_escape($name)?>" value="1" autocomplete="off" <?=$val=='1'?'checked':''?>><div class="m-layout" data-label-on="<?=html_escape($label.$label_on)?>" data-label-off="<?=html_escape($label_on)?>"></div>
	</label>
	<label class="m-onoff m-onoff-warning m-with-label btn btn-warning"><input type="radio" name="<?=html_escape($name)?>" value="0" autocomplete="off" <?=!$val?'checked':''?>><div class="m-layout" data-label-on="<?=html_escape($label.$label_off)?>" data-label-off="<?=html_escape($label_off)?>"></div>
	</label>
	<?
}

// onoff
function print_onoff_type2($name,$val,$label_on='사용',$label_off='금지',$label_require='필수',$label=''){
	if(isset($label[0])) $label .= ' ';
	?>
	<label class="m-onoff m-onoff-success m-with-label btn btn-success"><input type="radio" name="<?=html_escape($name)?>" value="1" autocomplete="off" <?=$val=='1'?'checked':''?>><div class="m-layout" data-label-on="<?=html_escape($label.$label_on)?>" data-label-off="<?=html_escape($label_on)?>"></div>
	</label>
	<label class="m-onoff m-onoff-danger m-with-label btn btn-danger"><input type="radio" name="<?=html_escape($name)?>" value="2" autocomplete="off" <?=$val=='2'?'checked':''?>><div class="m-layout" data-label-on="<?=html_escape($label.$label_require)?>" data-label-off="<?=html_escape($label_require)?>"></div>
	</label>
	<label class="m-onoff m-onoff-warning m-with-label btn btn-warning"><input type="radio" name="<?=html_escape($name)?>" value="0" autocomplete="off" <?=!$val?'checked':''?>><div class="m-layout" data-label-on="<?=html_escape($label.$label_off)?>" data-label-off="<?=html_escape($label_off)?>"></div>
	</label>
	<?
}

function bbs_date_former($form,$dtstr){
	return date($form,strtotime($dtstr));
}

// http://php.net/manual/en/function.parse-url.php
function unparse_url($parsed_url) {
  $scheme   = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
  $host     = isset($parsed_url['host']) ? $parsed_url['host'] : '';
  $port     = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
  $user     = isset($parsed_url['user']) ? $parsed_url['user'] : '';
  $pass     = isset($parsed_url['pass']) ? ':' . $parsed_url['pass']  : '';
  $pass     = ($user || $pass) ? "$pass@" : '';
  $path     = isset($parsed_url['path']) ? $parsed_url['path'] : '';
  $query    = isset($parsed_url['query']) ? '?' . $parsed_url['query'] : '';
  $fragment = isset($parsed_url['fragment']) ? '#' . $parsed_url['fragment'] : '';
  return "$scheme$user$pass$host$port$path$query$fragment";
}

function split_tags_string($bt_tags_string){
	$matched = array();
	preg_match_all('/([^#\t\s\n\x00-\x2C\x2E-\x2F\x3A-\x40\x5B-\x5E\x60\x7B~\x7F]{1,30})/u',strtolower($bt_tags_string),$matched);
	return isset($matched[1])?array_unique($matched[1]):array();
}

/**
 * 모바일 디바이스 상태인가?
 * @return boolean [description]
 */
function is_mobile(){
	return preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", isset($_SERVER["HTTP_USER_AGENT"])?$_SERVER["HTTP_USER_AGENT"]:'');
}


/**
 * IP 체크용 (cli는 무조건 통과)
 * @param  [type]  $pattern [description]
 * @return boolean          allowd=1,true / other is not allowd.
 */
function is_allowd_ip($pattern,$ip=null){
	if(is_cli()){ //CLI는 무조건 통과
		return true;
	}
	if($ip==null){
		$ip = isset($_SERVER['REMOTE_ADDR'][0])?$_SERVER['REMOTE_ADDR']:null;
	}
	if($ip==null){
		trigger_error("Where is a IP?", E_USER_WARNING);
		return null;
	}
	if(!isset($pattern[2])){ //패턴 길이가 2 미만이면 무조건 통과
		return true;
	}
	return @preg_match($pattern,$ip);
}

function only_https($https_port=null){
	$r = is_https();
	if(!$r){
		$url_parts = array();
		$url_parts[]='https://';
		$url_parts[]=$_SERVER['HTTP_HOST'];
		if(isset($https_port)){
			$url_parts[]=':'.$https_port;
		}
		$url_parts[]=$_SERVER['REQUEST_URI'];
		$to_url = implode('',$url_parts);
		redirect($to_url);
		return true;
	}
	return false;
}
function only_http(){
	$r = is_https();
	if($r){
		$url_parts = array();
		$url_parts[]='http://';
		$url_parts[]=$_SERVER['HTTP_HOST'];
		$url_parts[]=$_SERVER['REQUEST_URI'];
		$to_url = implode('',$url_parts);
		redirect($to_url);
		return true;
	}
	return false;
}

//-- 배열용 통계 함수
function array_min( &$arr )
{
    $min = FALSE;
    foreach( $arr as $a )
        if( $min === FALSE || $a < $min ) $min = $a;
    return $min;
}

function array_max( &$arr )
{
    $max = FALSE;
    foreach( $arr as $a )
        if( $max === FALSE || $a > $max ) $max = $a;
    return $max;
}

function array_avg( &$arr )
{
    $sum = 0;
    foreach( $arr as $a )
        $sum += $a;
    return $sum / count($arr);
}

function array_dev( &$arr, $avg = NULL )
{
    if( $avg == NULL ) $avg = array_avg($arr);

    $dev = 0;
    foreach( $arr as $a )
        $dev += pow(($a - $avg),2);
    return sqrt($dev);
}

// -- 날짜 범위 기준으로 날짜기준값으로 연관 배열 생성
function array_date_key($group_type,$date_st,$date_ed,$v){
	$def_ranks_array = array();
	$tm1 = strtotime($date_st);
	$tm2 = strtotime($date_ed);

	if($group_type=='day'){
		$i_limit = 1200;
		while($tm1<=$tm2 && $i_limit-- > 0){
			$t = date('Y-m-d',$tm1);
			$def_ranks_array[$t]= ($v==='text'?date('m-d',$tm1):$v);
			$tm1 += 60*60*24;
		}
	}else if($group_type=='week'){
		$i_limit = 1200;
		$tm2 = $tm2+86400*(7-date('N',$tm2));
		while($tm1<=$tm2 && $i_limit-- > 0){
			$t = date('o-W',$tm1);
			$def_ranks_array[$t]= ($v==='text'?date('o-W주',$tm1):$v);
			$tm1 += 60*60*24*7;
		}
	}else if($group_type=='month'){
		$i_limit = 1200;
		$tm2 = mktime(0,0,0,date('n',$tm2)+1,0,date('Y',$tm2));
		while($tm1<=$tm2 && $i_limit-- > 0){
			$t = date('Y-m',$tm1);
			$def_ranks_array[$t]= ($v==='text'?date('Y-m월',$tm1):$v);
			$tm1 = mktime(0,0,0,date('n',$tm1)+1,1,date('Y',$tm1));
		}
	}
	return $def_ranks_array;
}
