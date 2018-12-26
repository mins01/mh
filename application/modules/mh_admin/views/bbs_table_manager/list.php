<?
//$btm_rows

?>



<div class="panel panel-default bbs-mode-list">
	<!-- Default panel contents -->
	<div class="panel-heading">
		<nav class="text-right">
			게시판 테이블 그룹 : <?=count($btm_rows)?> 개
		</nav>
	</div>
	<div class="panel-body">
		<ul class="list-group">
			<!-- Default panel contents -->
			<li class="list-group-item active">
				<div class="row">
					<div class="col-md-4 text-center">아이디</div>
					<div class="col-md-8">
						<div class="col-sm-6">테이블:게시판</div>
						<div class="col-sm-6">테이블:댓글</div>
						<div class="col-sm-6">테이블:첨부파일</div>
						<div class="col-sm-6">테이블:조회</div>
						<div class="col-sm-6">테이블:태그</div>
						<div class="col-sm-6">첨부파일경로</div>
					</div>
				</div>
			</li>
			<? 
			foreach($btm_rows as $btm_row):
			//print_r($r);
			?>
			<li class="list-group-item">
				<div class="row ">
					<div class="col-md-4 text-center">
						<label><input class="tbl_id" name="tbl_id" type="radio" data-status="<?=$btm_row['status']?>" value="<?=html_escape($btm_row['tbl_id'])?>">
						<span class=" label <?=$btm_row['status']!='ok'?'label-danger':'label-success'?>"><?=html_escape($btm_row['status'])?></span>
						<?=html_escape($btm_row['tbl_id'])?>
						
						</label>
					</div>
					
					<div class="col-md-8">
						<? foreach(array('tbl_data','tbl_comment','tbl_file','tbl_hit','tbl_tag') as $t): ?>
							<div class="col-sm-6">
							<?
							if(!isset($btm_row[$t][0])):
							?>
							<span class="label label-danger">error</span> <?=html_escape($t)?> : <?=html_escape($btm_row[$t])?>
							<?
							else:
							?>
							<span class="label label-success">ok</span>
							<?=html_escape($t)?> : <?=html_escape($btm_row[$t])?>
							<?
							endif;
							?>
							</div>
						<? endforeach; ?>
						<div class="col-sm-6">
						<span class=" label <?=!$btm_row['file_dir_exists']?'label-danger':'label-success'?>"><?=html_escape($btm_row['file_dir_exists']?'ok':'error')?></span>
						<?=html_escape($btm_row['file_dir'])?></div>
					</div>
					
				</div>
			</li>
			<? 
				endforeach; 
			?>
			<li class="list-group-item">
				<div class="row ">
					<div class="col-md-6 text-center">
						<p class="form-control-static">
						선택된 게시판 테이블 그룹을 복사하시겠습니까?
						</p>
					</div>
					<div class="col-md-6 text-center form-inline">
						<form action="" method="post" onsubmit="return process_copy_tables(this); return false;" onerror="return false;">
							<input type="hidden" name="mode" value="process">
							<input type="hidden" name="process" value="copy_tables">
							<input type="hidden" name="tbl_id" value="">
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-addon">새 아이디</div>
									<input type="text" maxlength="10" class="form-control" name="to_tbl_id" placeholder="(알파벳,숫자)">
									<span class="input-group-btn">
										<button type="submit" class="btn btn-danger">복사하기</button>
									</span>
								</div>
							</div>
						</form>
					</div>
				</div>
			</li>
			<li class="list-group-item">
				<div class="row ">
					<div class="col-md-6 text-center">
						<p class="form-control-static">
						선택된 게시판 테이블 그룹을 삭제하시겠습니까?
						</p>
					</div>
					<div class="col-md-6 text-center form-inline">
						<form action="" method="post" onsubmit="return process_drop_tables(this); return false;" onerror="return false;">
							<input type="hidden" name="mode" value="process">
							<input type="hidden" name="process" value="drop_tables">
							<input type="hidden" name="tbl_id" value="">
							<button type="submit" class="btn btn-danger">삭제하기</button>
						</form>
					</div>
				</div>
			</li>
		</ul>
		
	</div>
</div>
<script>
function process_copy_tables(f){
	
	var $tbl_id = $('.tbl_id:checked');
	var tbl_id = $tbl_id.val();
	if(!tbl_id){
		alert("선택된 테이블 그룹 아이디가 없습니다.");
		return false;
	}
	if($tbl_id.attr('data-status')!='ok' 
		&& !confirm('불완전한 게시판 그룹입니다. 그래도 복사하시겠습니까?')){
		return false;
	}
	var to_tbl_id = f.to_tbl_id.value;
	if(/[^a-zA-Z0-9]/.test(to_tbl_id)){
		alert("알파벳,숫자만 사용이 가능합니다.");
		f.to_tbl_id.focus();
		return false;
	}
	if(!/^.{2,10}$/.test(to_tbl_id)){
		alert("2자 이상 8자 이하로 설정해주세요.");
		f.to_tbl_id.focus();
		return false;
	}
	if($('.tbl_id[value='+to_tbl_id+']').length>0){
		alert("이미 사용중인 그룹 아이디입니다.");
		f.to_tbl_id.focus();
		return false;
	}
	alert('인덱스, 추가 필드. 첨부파일 폴더 등은 따로 확인하셔야합니다.');
	if(!confirm('선택된 게시판 테이블 그룹을 복사하시겠습니까?')){
		return false;
	}
	
	f.tbl_id.value=tbl_id;
	return true;
}

function process_drop_tables(f){
	if($('.tbl_id').length<2){
		alert("마지막 게시판 테이블 그룹은 삭제할 수 없습니다.");
		return false;
	}
	var $tbl_id = $('.tbl_id:checked');
	var tbl_id = $tbl_id.val();
	if(!tbl_id){
		alert("선택된 테이블 그룹 아이디가 없습니다.");
		return false;
	}
	alert('첨부파일 경로는 삭제가 안되는 경우도 있으니, 따로 확인하셔야합니다.');
	if(!confirm('선택된 게시판 테이블 그룹을 삭제하시겠습니까?')){
		return false;
	}
	
	f.tbl_id.value=tbl_id;
	return true;
}
</script>


