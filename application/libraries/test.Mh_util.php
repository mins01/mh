<?
require('Mh_util.php');



$dom = new DOMDocument('1.0', 'UTF-8');
$internalErrors = libxml_use_internal_errors(true);

$html = '
<a href="http://mins01.com?aaa=onclick&bbb=b&amp;ccc=%ED%95%9C%EA%B5%AD%EC%96%B4" onclick="alert(\'x\')" onMouseover="console.log(\'x\')" >onclick=""</a>
<div onclikc="ssdad" oclass=" onzcxzxc ontt" xxonxx ="asd" onxxx ="asd" src="onerror=\'alert(3)\' onasdasd-asdas /on/sasd asd zx on the  "> onclick=""</div>
<div class="btn"onclick="alert(1)" title="onclick=\'alert(123)\'" data-label=btnonclick="alert(3)">xss</div>
<div class=btn onclick="alert(1)" >onclick="alert(1)"</div>
<div class=btn onclick=\'alert(1)\' >onclick=\'alert(1)\'</div>
<div class=btn onclick=alert(1) >onclick=alert(1)</div>
<div class=btnonclick="alert(1)" >class=btnonclick="alert(1)"</div>
<div class=btn src="onXXX/bbb/ccc">src="onXXX/bbb/ccc"</div>
<div class=btn src="abc?onclick=yyy&amp;onerror=zzz">src="abc?onclick=yyy&amp;onerror=zzz"</div>
<div class=btn src="onXXX=123 onerror=alert(2)\'">src="onXXX=123 onerror=alert(2)\'"</div>
';
$chtml = Mh_util::convertOneventXSS($html);

echo $html,PHP_EOL;
echo $chtml,PHP_EOL;
$dom->loadHTML('<?xml encoding="UTF-8">' .$chtml);
$xpath = new DOMXpath($dom);
$onclick_nodes = $xpath->query('//*[@onclick]');
$onmouseover_nodes = $xpath->query('//*[@onmouseover]');
$onerror_nodes = $xpath->query('//*[@onerror]');
assert($onclick_nodes->length==0);
assert($onmouseover_nodes->length==0);
assert($onerror_nodes->length==0);
