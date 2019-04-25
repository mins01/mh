<?php

require(APPPATH.'/libraries/html2markdown.php');

class Tools extends CI_Controller {

        public function message($to = 'World')
        {
                echo "Hello {$to}!".PHP_EOL;
        }
				public function getDbData(){
					$query = $this->db->query("show processlist");
					$rows = $query->result_array();
					print_r($rows);
				}
				
				/*
---
title: "WC2"
date: 2015-04-20 10:16:34 +0900
categories: post
---
WebCanvas2 (Web Image Editor) 웹 이미지 에디터

# [WC2](http://www.mins01.com/WC2/WC2.html)

![img](http://mins01.github.io/WC2/WC2.png)

				 */
				
				public function savePostsMdFromBbs(){
					$query = $this->db->query("
					SELECT 
					b.b_idx,
					CONCAT(SUBSTR(b_insert_date,1,10),'-tech-',b_idx,'-post.md') AS filename,
					b_title AS 'title',
					CONCAT(b_insert_date,' +0900') AS 'date' ,
					(SELECT GROUP_CONCAT(bt_tag SEPARATOR ', ') FROM `mh_bbs_tech_tag` bt WHERE b.b_idx = bt.b_idx AND bt_isdel = 0) categories,
          (SELECT GROUP_CONCAT(CONCAT('+ [',bf_save,'](',bf_save,')') SEPARATOR '  ') FROM `mh_bbs_tech_file` bf WHERE b.b_idx = bf.b_idx AND bf_isdel = 0 AND bf_type='external/url') urls,
					b_text AS 'contents'
					FROM `mh_bbs_tech_data` b 
					WHERE b_isdel = 0 AND b_secret=0
					-- and b.b_idx = 1268
					ORDER BY  b_idx DESC
					LIMIT 500;
					");
					$rows = $query->result_array();
					foreach ($rows as $key => $row) {
						$filecontent = $this->toPostsMd($row);
						// echo $row['filename'],"\n";
						// echo $filecontent;
						$path = APPPATH.'/../_temp/'.$row['filename'];
						file_put_contents($path,$filecontent);
					}
				}
				private function removeSpanDiv($str){
					$str = preg_replace('~<div[^>]*>|<span[^>]*>|</span[^>]*>~iu', '', $str);
					// return $str;
					return preg_replace('~</div>|</p[^>]*>~iu', "\n", $str);
				}
				private function toPostsMd($row){
					$arr = array();
					$arr[] = '---';
					$arr[] = 'title: "'.$row['title'].'"';
					$arr[] = 'date: '.$row['date'];
					// $arr[] = 'categories: ['.$row['categories'].']';
					$arr[] = 'categories: ';
					$arr[] = '---';
					$arr[] ="  ";
					// $arr[] = strip_tags(html2markdown(htmlspecialchars_decode(htmlspecialchars_decode($row['contents'],ENT_HTML5))));
					$arr[] = html2markdown($this->removeSpanDiv(($row['contents'])));
          if(isset($row['urls'][1])){
            $arr[] =PHP_EOL.PHP_EOL."***";
            $arr[] = $row['urls'];  
          }
					$arr[] =PHP_EOL.PHP_EOL."***";
					$arr[] ="[🔗original-link](http://www.mins01.com/mh/tech/read/{$row['b_idx']})";
					
					
					return implode(PHP_EOL,$arr);
				}
}