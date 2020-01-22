<h2>배너 관리자</h2>
<div class="banner_admin banner_admin_list">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th class="text-center" style="width:10em">관리번호</th>
        <th class="text-center">타이틀</th>
        <th class="text-center" style="width:10em">배너 사용여부</th>
        <th class="text-center" style="width:20em">시작일~마침일</th>
      </tr>
    </thead>
    <tbody>
      <?
      foreach ($rows as $row):
        $edit_url = $base_url.'/form/'.$row['bn_idx'];
        ?>
        <tr>
          <td class="text-center"><a href="<?=html_escape($edit_url)?>"><?=html_escape($row['bn_idx'])?></a></td>
          <td class="text-center"><a href="<?=html_escape($edit_url)?>"><?=html_escape($row['bn_title'])?></a></td>
          <td class="text-center"><?=html_escape($row['bn_isuse'])?></td>
          <td class="text-center">
            <div><?=html_escape($row['bn_date_st'])?></div>
            <div>~ <?=html_escape($row['bn_date_ed'])?></div>
          </td>
        </tr>
        <?
      endforeach;
      ?>
    </tbody>
  </table>
  <div class="text-right">
    <a href="<?=html_escape($base_url)?>/form" class="btn btn-info">등록</a>
  </div>
</div>
