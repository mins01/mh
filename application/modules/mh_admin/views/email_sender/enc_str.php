<!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
<!-- <script src="<?=SITE_URI_ASSET_PREFIX?>dome_recommend/recoCtrl.js?t=<?=REFLESH_TIME?>"></script> -->
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>email_sender/email_sender.css?t=<?=REFLESH_TIME?>">

<form action="?" method="post" class="form_email" >
  <ul class="list-group">
    <li class="list-group-item">
      <h4>문자열 암호화</h4>
      <div class="input-group">
        <span class="input-group-addon" >입력 문자열</span>
        <input type="password" class="form-control" name="input_plain_text" placeholder="******" >
        <span class="input-group-btn">
          <button class="btn btn-success btn-add"  type="submit">암호화</button>
        </span>
      </div>
      <h4>결과</h4>
      <div>
        <textarea readonly rows="3"  class="form-control"><?=html_escape($cipher_text)?></textarea>
      </div>
      <div>
        검증결과: <?=$checked_enc?'OK':'FAIL'?>
      </div>
    </li>
    <li class="list-group-item">

    </li>
    <li class="list-group-item">
      <h4>문자열 복호화</h4>
      <div class="input-group">
        <span class="input-group-addon" >입력 문자열</span>
        <input type="password" class="form-control" name="input_cipher_text" placeholder="******" >
        <span class="input-group-btn">
          <button class="btn btn-success btn-add"  type="submit">암호화</button>
        </span>
      </div>
      <h4>결과</h4>
      <div>
        <textarea readonly rows="3"  class="form-control"><?=html_escape($plain_text)?></textarea>
      </div>
    </li>
  </ul>
</form>
