<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count

?>

<div class="panel panel-default bbs-mode-list">

	<!-- Default panel contents -->
	<div class="panel-heading">
		<nav class="text-right">
			게시물 : <?=$count?> (<?=$max_page?> page)
		</nav>
	</div>
	<!-- Default panel contents -->
	<div class="panel-body">
		<div class="row">
		<? 
		foreach($b_rows as $r):
		//print_r($r);
		?>
			<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
				<div class="panel panel-default center-block" style="max-width:300px">
					<div class="panel-heading text-center  text-overflow-ellipsis"
					 ><a href="<?=html_escape($r['read_url'])?>" title="<?=html_escape($r['b_title'])?>"><?=html_escape($r['b_title'])?></a></div>
					<div class="panel-body text-center">
						Panel content
					</div>
				</div>
			</div>
		<?
		endforeach;
		?>
		<? if(count($b_rows)==0): ?>
		<div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 center-block clearB">
				<div class="panel panel-default center-block" style="max-width:300px">
					<div class="panel-heading text-center  text-overflow-ellipsis"
					 >No-Data</div>
					<div class="panel-body text-center">
						No-Data
					</div>
				</div>
			</div>
		<? endif; ?>
		</div>
	</div>
	<div class="panel-body">
		<div class="row">
			<div class="col-lg-2 col-sm-2 hidden-xs">
			</div>
			<div class="col-lg-8 col-sm-8 ">
				<form action="" class="form-inline text-center">
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<? if($bm_row['bm_use_category']=='1'): ?>
								<?=form_dropdown('ct', $bm_row['categorys'], isset($get['ct'])?$get['ct']:'', 'class="selectpicker show-tick" style="width:8em" data-width="80px" aria-label="카테고리 설정" title="카테고리"  data-header="카테고리"')?>
								<? endif; ?>
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" >
								<option value="title" <?=$get['tq']=='title'?'selected':''?>>제목</option>
								<option value="text" <?=$get['tq']=='text'?'selected':''?>>내용</option>
								</select>
							</div>
							<input name="q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="<?=html_escape(isset($get['q'])?$get['q']:'')?>">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-default">검색</button>
							</span>
						</div><!-- /input-group -->
					</div>
				</form>
			</div>
			<div class="col-lg-2 col-sm-2 text-right">
				<? if($permission['write']): ?>
				<a href="<?=html_escape($bbs_conf['write_url'])?>" class="btn btn-success glyphicon glyphicon-pencil"> 작성</a>
				<? endif; ?>
			</div>
		</div>
	</div>
	<div class="panel-footer">
		<nav class="text-center">
			<?=$pagination?>
		</nav>
	</div>
</div>


