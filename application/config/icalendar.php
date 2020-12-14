<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== ics 설정
/*
1년에 한번은 갱신하시오.
한국 : https://p03-calendars.icloud.com/holidays/kr_ko.ics
일본 : https://p03-calendars.icloud.com/holidays/jp_ja.ics
중국 : https://p03-calendars.icloud.com/holidays/cn_zh.ics
대만 : https://p03-calendars.icloud.com/holidays/tw_zh.ics
미국 : https://p03-calendars.icloud.com/holidays/us_en.ics
영국 : https://p03-calendars.icloud.com/holidays/gb_en.ics
캐나다 : https://p03-calendars.icloud.com/holidays/ca_en.ics
독일 : https://p03-calendars.icloud.com/holidays/de_de.ics
*/
$config['icalendar'] = array(
	'dir'=>SERVER_PATH_ASSET.'ics',
	'default_ics'=>'kr_ko.ics', //ca_en.ics,cn_zh.ics,de_de.ics,gb_en.ics,jp_ja.ics,kr_ko.ics,tw_zh.ics,us_en.ics
);
