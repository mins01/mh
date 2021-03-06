<?
/**
* Mh_util
*/
class Mh_util{



	static function cvt_html($text,$mode){
		switch($mode){
			case 'h':	//HTML
				$rTag = array('script','style','xmp','xml');
				return self::convertXSS(self::stringToHTML($text),$rTag,1);
			break;
			case 'p':	//PRE
				$rTag = array('script','style','xmp','xml');
				return self::convertXSS(self::stringToPRE($text),$rTag,0);
			break;
			case 'r':	//realHTML
				return $text;
			break;
			case 't':	//Text
			default:
				return self::autoLink(self::stringToText($text),false);
			break;
		}
		return $text;
	}

	//=================
	// 문자열 변환
	//=================
	/** 문자열 변환 (STRING -> TEXT)
	* @param string $str 입력문자열
	* @return string Text형식으로 바뀐 문자열(오토링크 처리)
	*/
	static function stringToText($str){
		$search = array("\t",'&amp;');
		$reaplce = array('&nbsp;&nbsp;&nbsp;&nbsp;','&');
		return str_replace($search,$reaplce, nl2br(htmlspecialchars($str)));
	}
	/** 문자열 변환 (STRING HTML)
	* @param string $str 입력문자열
	* @return string HTML형식으로 바뀐 문자열(오토링크 처리)
	*/
	static function stringToHTML($str){
		return $str;
		// $search = array('<br>','<BR>');
		// $reaplce = array('<br />','<BR />');
		// return str_replace($search,$reaplce,$str);
	}
	/** 문자열 변환 (STRING HTML+<BR/>)
	* - (실제로는 HTML이다. <table>등에 적용하면 이상해짐!)
	* @param string $str 입력문자열
	* @return string PRE(HTML+BR)형식으로 바뀐 문자열(오토링크 처리)
	*/
	static function stringToPRE($str){
		$search = array('<br>','<BR>',"\t");
		$reaplce = array('<br />','<BR />','&nbsp;&nbsp;&nbsp;&nbsp;');
		$str=nl2br(str_replace($search,$reaplce,$str));
		return $str;
	}
	/**
	* 오토링크
	* @param string $str 문자열
	* @param bool $amp & 변환작업을 하는가?
	* @return string 오토링크 처리된 문자열
	*/
	static function autoLink($str,$amp=false) {	//문자열의 내용중 링크를 자동처리
		if($amp) $str = str_replace('&amp;','&',$str);
	//http://www.phpschool.com/gnuboard4/bbs/board.php?bo_table=tipntech&wr_id=32517&sca=&sfl=wr_subject%7C%7Cwr_content&stx=%C0%DA%B5%BF+%B8%B5%C5%A9&sop=and&page=3
		$pattern = "/(http|https|ftp|mms):\/\/[0-9a-z-]+(\.[_0-9a-z-]+)+(:[0-9]{2,4})?\/?";// domain+port
		$pattern .= "([\.~_0-9a-z-]+\/?)*";// sub roots
		$pattern .= "(\S+\.[_0-9a-z]+)?";// file & extension string
		$pattern .= "(\?[_0-9a-z#%&=\-\+]+)*/i";// parameters
		$replacement = "<a href=\"\\0\" target=\"_blank\">\\0</a>";
		$str = preg_replace($pattern, $replacement, $str, -1);
		if($amp) $str = str_replace('&','&amp;',$str);
		return $str;
	}
	/** XSS 회피용 (tag,onevent,style을 한번에)
	*/
	static function convertXSS($str,$rTags=array(),$type=0,$rStyles=array()){
		return self::convertOneventXSS(self::convertStyleXSS(self::convertTags($str,$rTags,$type),$rStyles));
	}
	/** XSS 이벤트 회피용 (onYYY 이벤트 구문을 xssonYYY 로 바꾼다)
	* @paran string $str 변환할 문자열
	* 최대 10번 재처리
	*/
	static function convertOneventXSS($str){
		$p = '/(<[^\s>]*[^>]*(\s|\'|"))(on)([^>="\']+)(\s*=[^>]*>)/im';
		$p = '#(<(?!/)[^\s<>]+[^<>]*(?:\s|\'|"))(on)([^<>="\']{2,})(\s*=[^<>]*>)#im';
		$r = '$1xsson$3$4';
		$shp = '#(<(?!/)[^\s<>]+[^<>]*(?:\s|\'|"))(on)([^<>="\']{2,})(\s*=[^<>]*>)#im';

		$limit = 100;
		while(preg_match($shp,$str) && $limit-- > 0){
			$str = preg_replace($p,$r,$str);
		}
		return $str;
	}
	/** XSS Style 회피용
	* @paran string $str 변환할 문자열
	* @param array $rStyles 태그 배열
	*/
	static function convertStyleXSS($str,$rStyles=array()){
		$rStyles = (count($rStyles)>0)?array_merge(array('absolute', 'behavior', 'behaviour', 'content', 'expression', 'fixed', 'include-source', 'moz-binding' ),$rStyles):array('absolute', 'behavior', 'behaviour', 'content', 'expression', 'fixed', 'include-source', 'moz-binding' );
		$t = implode('|',$rStyles);
		$p = "/(\bstyle\s*=\s*)(\")([^<\"]*)({$t})(;?)([^<\"]*)(\")/mi";
		$p2 = "/(\bstyle\s*=\s*)(')([^<']*)({$t})(;?)([^<']*)(')/mi";
		//echo $p,"\n";
		// $shp = $p;
		$r = '$1$2$3xss__$5$6$7';
		$limit = count($rStyles);
		//echo $str,"\n";
		while(preg_match($p,$str) && $limit-- > 0){
			$str = preg_replace($p,$r,$str);
			//print_r($x);	echo $str,"\n";	echo":",$limit,"\n";
		}
		while(preg_match($p2,$str) && $limit-- > 0){
			$str = preg_replace($p2,$r,$str);
			//print_r($x);	echo $str,"\n";	echo":",$limit,"\n";
		}
		return $str;
	}
	/** 태그 변환
	* @param string $str 문자열
	* @param array $rTags 태그 배열
	* @param int $type 변환타입 0(기본값)이면 <등을 &lt;로 바꾼다. 1이면 태그자체를 지운다. 2면 <>만 지운다.
	* @return string 오토링크 처리된 문자열
	*/

	static function convertTags($str,$rTags,$type=0){
		$rTags = (count($rTags)>0)?array_merge(array('applet','base','basefont','bgsound','blink','body','embed','frame','frameset','head','html','ilayer','layer','link','meta','object','style','title','script','xml','xmp'),$rTags):array('applet','base','basefont','bgsound','blink','body','embed','frame','frameset','head','html','ilayer','iframe','layer','link','meta','object','style','title','script','xml','xmp');
		foreach($rTags as $val){
			$search[] = "'(<)([\/\!]*?)({$val})([^<>]*?)(>)'si";
		}
		if(isset($search[0])){
			switch($type){
				case 0 : $replace = '&lt;\\2\\3\\4&gt;';break; //<> 변환
				case 1 : $replace = '';break; //삭제
				case 2 : $replace = '\\3\\4';break; // < / >제거
			}
			return preg_replace($search,$replace, $str);
		}else{
			return $str;
		}
	}
	/** 글자치환
	* @param string $str 문자열
	* @param array|string $replace 바꿀 문자열 배열 또는 글자수에 맞춰 변환할 문자열
	* @return string 오토링크 처리된 문자열
	*/
	static function replaceWrods($str,$search,$replace){
		if(!is_array($replace)){
			$t = $replace;
			foreach($search as $val){
				$replace = str_repeat($t,str_strlen($val));
			}
		}
		if(isset($search[0])){
			return str_replace($search,$replace, $str);
		}else{
			return $str;
		}
	}

	/**
	 * OGP parser
	 * <meta property="og:title" content="공대여자 홈 : 메인">
	 * <meta property="og:description" content="공대여자 홈 : 메인">
	 * <meta property="og:image" content="http://www.mins01.com/img/logo.gif">
	 * <meta property="og:image:width" content="190">
	 * <meta property="og:image:height" content="70" />
	 * <meta property="og:site_name" content="공대여자 홈" />
	 * <meta property="og:type" content="website">

	 */
	static function parseOgp($content,$url=''){
		// if(!class_exists('XML2Array')){
		// 	require_once(dirname(__FILE__).'XML2Array.php');
		// }

		$ogp = array();
		$metas = array();
		$match = array();
		preg_match('@<title[^>]*>(.*)</title>@m',$content,$match);
		$metas['title'] = isset($match[1][0])?$match[1]:$url;
		$match = array();
		preg_match_all('@<meta [^>]*/?>@m',$content,$match);
		// echo $content;
		// print_r($match[0]);
		foreach ($match[0] as $key => $v) {
			$ts=array();
			preg_match_all('/(name|property|content)(?:\s*)=(?:\s*)(?:"|\')([^"\']+)(?:"|\')/',$v,$ts,PREG_SET_ORDER);
			// print_r($ts);
			$meta = array();
			foreach($ts as $t ){
				$meta[$t[1]] = $t[2];
			}
			$property = isset($meta['property'])?$meta['property']:(isset($meta['name'])?$meta['name']:'');
			if(!isset($property[0])) continue;
			if(strpos($property,'og')===0 ||strpos($property,'fb')===0 ||strpos($property,'twitter')===0 ){
				// print_r($meta);
				if(!isset($ogp[$property])){
					$ogp[$property]=isset($meta['content'])?htmlspecialchars_decode(html_entity_decode($meta['content'],ENT_QUOTES)):'';
					$ogp[$property.'s'] = array();
				}

				$ogp[$property.'s'][]=isset($meta['content'])?htmlspecialchars_decode(html_entity_decode($meta['content'],ENT_QUOTES)):'';
			}else{
				$metas[$property]=isset($meta['content'])?htmlspecialchars_decode(html_entity_decode($meta['content'],ENT_QUOTES)):'';
			}
		}
		// print_r($metas);
		$ogp['title'] = htmlspecialchars_decode(html_entity_decode($metas['title'],ENT_QUOTES));
		if(!isset($ogp['og:title'])){
			$ogp['og:title'] = $ogp['title'];
		}
		if(!isset($ogp['og:description']) && isset($metas['description'])){
			$ogp['og:description'] = htmlspecialchars_decode(html_entity_decode($metas['description'],ENT_QUOTES));
		}

		return $ogp;
	}
}
