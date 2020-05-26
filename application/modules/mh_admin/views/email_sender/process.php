<!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
<!-- <script src="<?=SITE_URI_ASSET_PREFIX?>dome_recommend/recoCtrl.js?t=<?=REFLESH_TIME?>"></script> -->
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>email_sender/email_sender.css?t=<?=REFLESH_TIME?>">

<ul class="list-group">
  <li class="list-group-item text-right">
    <button class="btn btn-info" type="button" onclick="window.location.replace(window.location.href);return false;">확인</button>
  </li>
  <?
  foreach ($ress as $key => $res):
    ?>
    <li class="list-group-item">
      <div><h2>발송 결과: <?=$res['result']?'성공':'실패'?></h2></div>
      <div>To: <?=html_escape($res['to'])?></div>
      <div>발송 해더: <?=$res['headers']?></div>
    </li>
    <?
  endforeach;
  ?>
  <li class="list-group-item text-right">
    <button class="btn btn-info" type="button" onclick="window.location.replace(window.location.href);return false;">확인</button>
  </li>
</ul>
