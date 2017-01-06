<?
//$comment_url
// print_r($bm_row );
?><div ng-app="bbsComment" class="bbs_c">
<div class="panel panel-default bbs-mode-comment"  ng-controller="CommentCtrl as commentCtrl" ng-init="commentCtrl.init('<?=html_escape($comment_url )?>','<?=$bm_row['bm_use_commnet_number']?>')">
	<div class="panel-heading">
		댓글
	</div>
	<ul class="list-group">
		<li class="list-group-item" ng-class="{'active':form.bc_idx==bc_row.bc_idx}"  ng-repeat="bc_row in bc_rows">
			<div id="bc_idx_{{bc_row.bc_idx}}"  class=" bc_depth bc_depth-{{bc_row.depth | print_depth:10}}">
				<dl class="form-inline">
					<dt class="sc_title">
						<a id="cmt_{{bc_row.bc_idx}}"  name="cmt_{{bc_row.bc_idx}}"></a>
						<span class="nick bc_nick" ng-bind="bc_row.bc_name"></span>
						<span ng-hide="bc_row.bc_number &lt;= 0 || bm_use_commnet_number!='1'" >
						/ <span class="bc_number bc-star-{{bc_row.bc_number}}"></span>
						</span>
						/ <span class="date bc_insert_date" ng-bind="bc_row.bc_insert_date|print_date"></span>
						<div class="pull-right"  ng-if="m_row.m_idx>0">
							<button ng-click="set_mode('answer',$index);" ng-show="permission.admin || permission.answer" type="button" class="btn btn-xs btn-success">답변</button>
							<button ng-show="permission.admin || (m_row.m_idx == bc_row.m_idx &amp;&amp; permission.edit )" ng-click="set_mode('edit',$index);" type="button" class="btn btn-xs btn-warning">수정</button>
							<button ng-show="permission.admin || (m_row.m_idx == bc_row.m_idx &amp;&amp; permission.delete )" ng-click="mode_delete($index);" type="button" class="btn btn-xs btn-danger">삭제</button>
						</div>
						<div class="clearfix"></div>
					</dt>
					<dd class="bc_comment" ng-hide="form.mode=='edit' &amp;&amp; form.bc_idx==bc_row.bc_idx"
					ng-bind-html="bc_row.bc_comment | linky | nl2br | space2nbsp"></dd>
				</dl>
			</div>
		</li>
		<li class="list-group-item form-inline"  ng-if="bc_rows.length == 0">
			<dl>
				<dt class="sc_title">
					No Nickname
				</dt>
				<dd class="bc_comment text-center">No Comment</dd>
			</dl>
		</li>
		<li id="bc_idx_write" class="list-group-item" ng-class="{'active':!form.bc_idx}">
			<div ng-if="form.mode!='write'" class="text-center">
				<button type="button" class="btn btn-info" ng-click="set_mode('write','write')">새로운 댓글 작성</button>
			</div>
			<div ng-hide="m_row.m_idx&gt;0" class="text-center">
				로그인이 필요합니다.
			</div>
			<form ng-if="m_row.m_idx&gt;0" name="wform" ng-submit="submitComment()" ng-class="{'wform-answer':form.mode=='answer'}">
				<input type="hidden" class="form-control" ng-model="form.mode" name="mode" value="write">
				<input type="hidden" class="form-control" ng-model="form.bc_idx" name="bc_idx" value="">
				<dl>
					<dt ng-show="form.mode!='edit'" class="sc_title form-control-static">
								<span class="nick bc_nick">{{m_row.m_nick}}</span>
					</dt>
					<dd class="bc_comment">
					<div class="form-group">
						<textarea
						ng-maxlength="60000"
						ng-disabled="form.mode=='delete' || !permission[form.mode]"
						ng-model="form.bc_comment"
						ng-trim="true"
						name="bc_comment"  class="form-control" rows="3" placeholder="댓글내용" required onkeyup="sync_textarea_height(this)" onblur="this.onkeyup()" onfocus="this.onkeyup()" style="line-height: 1.5em;max-height: 20em; min-height: 6em"></textarea>
						<div role="alert">
						<div class="alert alert-danger" role="alert" ng-show="wform.bc_comment.$error.maxlength">내용이 너무 많습니다!</div>
						</div>
					</div>
					<div class="form-inline text-right">
						<span ng-show="msg.length&gt;0" ng-bind="msg">-</span>
						<div class="form-group" >
							<select ng-model="form.bc_number"
							ng-disabled="bm_use_commnet_number!='1'"
							ng-hide="bm_use_commnet_number!='1'"
							name="bc_number" class="form-control input-sm" style="width:9em">
									<option value="0" class="bc-star bc-star-0" ng-selected="!form.bc_number">no-star</option>
									<option value="1" class="bc-star bc-star-1">★☆☆☆☆</option>
									<option value="2" class="bc-star bc-star-2">★★☆☆☆</option>
									<option value="3" class="bc-star bc-star-3">★★★☆☆</option>
									<option value="4" class="bc-star bc-star-4">★★★★☆</option>
									<option value="5" class="bc-star bc-star-5">★★★★★</option>
								</select>
							<button ng-disabled="!wform.$valid" type="submit" class="btn btn-warning">확인</button>
						</div>
					</div>
					</dd>
				</dl>
			</form>
		</li>
	</ul>
</div>
</div>
<script>
	/**
	 * 텍스트박스 높이 맞추기.
	 * @param  {[type]} el [description]
	 * @return {[type]}    [description]
	 */
	function sync_textarea_height(el){
		var nl_cnt = el.value.split("\n").length;
		el.style.height = ((nl_cnt+2)*1.5)+'em';
	}
</script>
<script>
ng_bbs_comment('bbsComment','CommentCtrl');
</script>
