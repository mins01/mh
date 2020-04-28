
<h2 class="text-right">캐시 관리</h2>
<ul class="list-group">
  <li class="list-group-item">전역 캐시 설정 : <?=$USE_CACHE?'ON':'OFF'?></li>
  <li class="list-group-item">mh_cache 설정 : <?=$USE_MH_CACHE?'ON':'OFF'?></li>
  <li class="list-group-item">
    <a href="?process=clean" class="btn btn-danger">캐시 클리어</a>
    <a href="?process=readjust" class="btn btn-warning">캐시 정리(오래된 캐시 삭제)</a>
  </li>
</ul>
<?
$cnt_cache_info = count($cache_info);
?>
<ul class="list-group">
  <li class="list-group-item active">캐싱 목록 [ All: <?=count($cache_info)?> / cached: <?=($cnt_cached)?> (<?=round($cnt_cached/$cnt_cache_info*100,2)?>%) / expired: <?=($cnt_expired)?> (<?=round($cnt_expired/$cnt_cache_info*100,2)?>%) ] </li>
  <? foreach($cache_info as $r): ?>
    <li class="list-group-item">
      <span>date: <?=date('Y-m-d H:i:s',$r['date'])?></span>
      / <span>expire: <?=date('Y-m-d H:i:s',$r['expire'])?> ( <?=$r['expire']-time()?> sec )</span>
      / <span>TTL: <?=$r['ttl']?> sec</span>
      / <span>size: <?=number_format($r['size'])?> Byte</span>
      /
      <?
      if($r['expired']):
        ?>
        <b class="text-danger">expired!</b>
        <?
      else:
        ?>
        <b class="text-success">cached</b>
        <?
      endif;
      ?>
      <div>
        <span>name: <?=html_escape($r['name'])?></span>
      </div>


      <? // print_r($r); ?>
    </li>
  <? endforeach; ?>

</ul>
