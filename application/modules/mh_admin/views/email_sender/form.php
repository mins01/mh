<!-- <script src="https://cdn.jsdelivr.net/npm/vue"></script> -->
<!-- <script src="<?=SITE_URI_ASSET_PREFIX?>dome_recommend/recoCtrl.js?t=<?=REFLESH_TIME?>"></script> -->
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>email_sender/email_sender.css?t=<?=REFLESH_TIME?>">

<form action="?" method="post" class="form_email" >
  <input name="process" type="hidden" value="send">
  <ul class="list-group">
    <li class="list-group-item">
      <h4>보내는사람 이메일</h4>
      <div class="input-group">
        <span class="input-group-addon" >보내는사람 이메일</span>
        <input type="email" name="from" class="form-control" placeholder="***@mail.com" required value="<?=html_escape(SITE_ADMIN_MAIL)?>" >
        <span class="input-group-btn">
          <button class="btn btn-success btn-add"  type="submit">발송</button>
        </span>
      </div>
      <div class="text-danger">서버에 따라서 보내는 이메일 주소는 바뀔 수 있습니다.</div>

    </li>
    <li class="list-group-item">
      <h4>대상 이메일</h4>
      <div id="div_mail_tos">
        <div style="margin-top:0.5em">
          <div>
            <textarea type="text" name="tos" id="tos" rows="5" class="form-control" placeholder="받는사람목록&#13;&#10;,로 다중발송&#13;&#10;줄바꿈으로 발송횟수를설정" required >mins01@naver.com</textarea>
          </div>
          <div>
            <div class="input-group">
              <span class="input-group-addon" >이메일 파일</span>
              <input type="file" class="form-control" accept=".text,.txt,.csv" value="" placeholder="파일 읽기(csv)" id="file_for_tos">
              <span class="input-group-btn">
                <button class="btn btn-info" type="button" onclick="load_file_for_tos();return false;">LOAD</button>
              </span>
            </div>
          </div>
        </div>
        <div class="text-danger">한번 발송시 ,로 여러명에게 발송 가능. 줄바꿈으로 여러번 발송 가능</div>

      </div>
      <script>
      function load_file_for_tos(){
        var file_for_tos = document.querySelector('#file_for_tos');
        if(file_for_tos.files.length==0){
          alert('파일을 선택해주세요.');
          return false;
        }
        var file = file_for_tos.files[0]
        var tos = document.querySelector('#tos');
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
          tos.value = event.target.result;
        };
        fileReader.readAsText(file);
      }

      </script>

    </li>
    <li class="list-group-item">
      <h4>메일 내용</h4>
      <div>
        <div class="input-group input-group_mail_tos" id="input-group_mail_tos">
          <span class="input-group-addon" >메일 제목</span>
          <input type="text" name="subject" class="form-control" placeholder="[***] *** 안내 메일입니다." >
        </div>
      </div>
      <div style="margin-top:0.5em">
        <div>
          mailtype: <label><input type="radio" name="mailtype" value="html" checked>html</label>, <label><input type="radio" name="mailtype" value="text">text</label>
        </div>
        <div>
          <textarea type="text" name="message" id="message" rows="10" class="form-control" placeholder="메일내용" ></textarea>
        </div>
        <div class="row" style="margin-top:0.5em;">
          <div class="col-lg-6">
            <div class="input-group">
              <span class="input-group-addon" >내용 파일</span>
              <input type="file" class="form-control" accept=".html,.htm" value="" placeholder="파일 읽기(csv)" id="file_for_message">
              <span class="input-group-btn">
                <button class="btn btn-info" type="button" onclick="load_file_for_message();return false;">LOAD</button>
              </span>
            </div>
          </div>
          <div class="col-lg-6 text-right">
            <button class="btn btn-warning" type="button" onclick="show_modal_preview();return false;">PREVIEW</button>
          </div>

        </div>
      </div>
      <script>
      function load_file_for_message(){
        var file_for_tos = document.querySelector('#file_for_message');
        if(file_for_tos.files.length==0){
          alert('파일을 선택해주세요.');
          return false;
        }
        var file = file_for_tos.files[0]
        var tos = document.querySelector('#message');
        var fileReader = new FileReader();
        fileReader.onload = function (event) {
          tos.value = event.target.result;
        };
        fileReader.readAsText(file);
      }
      </script>
    </li>
  </ul>
</form>


<!-- Modal -->
<div class="modal fade" id="modal_preview" data-backdrop="static" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">메일 내용 미리보기</h4>
      </div>
      <div class="modal-body" id="modal_preview_body" >
        <iframe id="iframe_preview" src="<?=SITE_URI_ASSET_PREFIX?>blank.html?t=<?=REFLESH_TIME?>"></iframe>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>
<script>
function show_modal_preview(){
  var iframe_preview = document.querySelector('#iframe_preview');
  var message = document.querySelector('#message');
  iframe_preview.contentDocument.body.innerHTML = message.value;
  $('#modal_preview').modal("show")
}
</script>
