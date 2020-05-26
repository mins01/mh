<!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
<!-- <script src="<?=SITE_URI_ASSET_PREFIX?>dome_recommend/recoCtrl.js?t=<?=REFLESH_TIME?>"></script> -->
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>email_sender/email_sender.css?t=<?=REFLESH_TIME?>">

<form action="?" method="post" class="form_email">
  <input name="process" type="hidden" value="send">
  <ul class="list-group">
    <li class="list-group-item">

      <div class="input-group">
        <span class="input-group-addon" >보내는사람 이메일</span>
        <input type="email" name="email_from" class="form-control" placeholder="***@mail.com" required value="<?=html_escape(SITE_ADMIN_MAIL)?>" >
        <span class="input-group-btn">
          <button class="btn btn-success btn-add"  type="submit">발송</button>
        </span>
      </div>
      <div class="text-danger">서버에 따라서 보내는 이메일 주소는 바뀔 수 있습니다.</div>

    </li>
    <li class="list-group-item">
      <div id="div_mail_tos">
        <div class="input-group input-group_mail_tos" id="input-group_mail_tos">
          <span class="input-group-addon" >받는 그룹 이메일</span>
          <input type="text" name="email_tos[]" class="form-control" placeholder="***@mail.com,*****@mail.com" value=""  >
          <span class="input-group-btn">
            <button class="btn btn-danger btn-del" style="width:3em" type="button" onclick="del_mail_tos(this.parentNode.parentNode)">-</button>
            <button class="btn btn-warning btn-add" style="width:3em" type="button" onclick="add_mail_tos()">+</button>
          </span>
        </div>
        <div class="input-group input-group_mail_tos" >
          <span class="input-group-addon" >받는 그룹 이메일</span>
          <input type="text" name="email_tos[]" class="form-control" placeholder="***@mail.com,*****@mail.com" value=""  >
          <span class="input-group-btn">
            <button class="btn btn-danger btn-del" style="width:3em" type="button" onclick="del_mail_tos(this.parentNode.parentNode)">-</button>
            <button class="btn btn-warning btn-add" style="width:3em" type="button" onclick="add_mail_tos()">+</button>
          </span>
        </div>
      </div>
      <div class="text-danger">한번 발송시 ,로 여러명에게 발송 가능.</div>
    </li>
    <li class="list-group-item">
      <div>
        <div class="input-group input-group_mail_tos" id="input-group_mail_tos">
          <span class="input-group-addon" >메일 제목</span>
          <input type="text" name="email_subject" class="form-control" placeholder="[***] *** 안내 메일입니다." >
        </div>
      </div>
      <div style="margin-top:0.5em">
        <div>
          mailtype: <label><input type="radio" name="email_mailtype" value="html" checked>html</label>, <label><input type="radio" name="email_mailtype" value="text">text</label>
        </div>
        <textarea type="text" name="email_message" rows="10" class="form-control" placeholder="메일내용" ></textarea>
      </div>
    </li>
  </ul>
</form>
<script>
function add_mail_tos(){
  var igmt = document.querySelector("#input-group_mail_tos");
  var dmt = document.querySelector("#div_mail_tos");
  var igmt2 = igmt.cloneNode(true);
  igmt2.id = null;
  igmt2.querySelector('input').value="";
  dmt.append(igmt2);
}
function del_mail_tos(igmt){
  igmt.parentNode.removeChild(igmt);
}
</script>
