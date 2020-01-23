<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/bbs/script.js')?>"></script>

<link href="/web_work/mb_wysiwyg_dom/bootstrap.css?t=<?=REFLESH_TIME?>" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/mb_wysiwyg.js?t=<?=REFLESH_TIME?>"></script>
<script type="text/javascript" src="/web_work/mb_wysiwyg_dom/set.toolbar.js?t=<?=REFLESH_TIME?>"></script>

<script src="<?=html_escape(SITE_URI_ASSET_PREFIX.'js/mh_gps.js')?>"></script>

<script>
//--- 위지윅 생성
$(
function(){
	 $('.pre-wysiwyg').each(function(idx,el){
		 createWysiwygObj(el)
	 })
})
</script>

<h2>배너 관리자</h2>
<div class="banner_admin banner_admin_form">
  <form onsubmit="submitWysiwyg();return check_form_bbs(this);" method="post">
    <input class="form-control" type="text" readonly name="process" value="<?=isset($row['bn_idx'][0])?'update':'insert'?>">
    <table class="table table-bordered">
      <thead>
        <tr>
          <th class="text-center" style="width:10em">필드</th>
          <th class="text-center">값</th>
        </tr>
      </thead>
      <tbody>
        <?
        foreach($row as $k => $v):
          if(
            $k =='bn_insert_date'
            || $k =='bn_update_date'
            || $k =='bn_isdel'
          ){
            continue;
          }
          ?>

          <?
          if($k=='bn_idx'):
            ?>
            <tr>
              <th class="text-center" style="width:10em"><?=html_escape($k)?></th>
              <td><input class="form-control" type="text" readonly name="<?=html_escape($k)?>" value="<?=html_escape($v)?>"></td>
            </tr>
            <?
          elseif($k=='bn_html'):
            ?>
            <tr>
              <th class="text-center" style="width:10em"><?=html_escape($k)?></th>
              <td>
                <textarea class="pre-wysiwyg"  name="<?=html_escape($k)?>"><?=html_escape($v)?></textarea>
              </td>
            </tr>
            <?
          else:
            ?>
            <tr>
              <th class="text-center" style="width:10em"><?=html_escape($k)?></th>
              <td><input class="form-control" type="text" name="<?=html_escape($k)?>" value="<?=html_escape($v)?>"></td>
            </tr>
            <?
          endif;
          ?>



          <?
        endforeach;
        ?>
        <tr>
          <th>동작</th>
          <td class="text-right">
            <a href="<?=html_escape($base_url)?>" class="btn btn-info">목록</a>
            <button class="btn btn-success">확인</button>
          </hd>
        </tr>
      </tbody>
    </table>
  </form>
</div>
