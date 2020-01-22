
<h2 class="text-right">캐시 관리</h2>
<ul class="list-group">
  <li class="list-group-item">전역 캐시 설정 : <?=$USE_CACHE?'ON':'OFF'?></li>
  <li class="list-group-item">mh_cache 설정 : <?=$USE_MH_CACHE?'ON':'OFF'?></li>
  <li class="list-group-item">
    <a href="?process=clean" class="btn btn-info">캐시 클리어</a>
  </li>
</ul>

<ul class="list-group">
  <li class="list-group-item active">캐싱 목록 (<?=count($cache_info)?>)</li>
  <? foreach($cache_info as $r): ?>
    <li class="list-group-item">
      <div>~<?=date('Y-m-d H:i:s',$r['date'])?> ( <?=$r['date']-time()?> sec )</div>
      <? print_r($r); ?>
    </li>
  <? endforeach; ?>

</ul>
