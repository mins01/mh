<?
//$comment_url 
?><div ng-app="bbsComment" class="bbs_c">
<div class="panel panel-default bbs-mode-comment"  ng-controller="CommentCtrl as commentCtrl" ng-init="commentCtrl.init('<?=html_escape($comment_url )?>')">
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
						/ <span class="date bc_insert_date" ng-bind="bc_row.bc_insert_date|print_date"></span>
						<div class="pull-right"  ng-if="m_row.m_idx>0">
							<button ng-click="set_mode('answer',$index);" type="button" class="btn btn-xs btn-success">답변</button>
							<button ng-show="m_row.m_idx == bc_row.m_idx" ng-click="set_mode('edit',$index);" type="button" class="btn btn-xs btn-warning">수정</button>
							<button ng-show="m_row.m_idx == bc_row.m_idx" ng-click="mode_delete($index);" type="button" class="btn btn-xs btn-danger">삭제</button>
						</div>
						<div class="clearfix"></div>
					</dt>
					<dd class="bc_comment" ng-hide="form.mode=='edit' && form.bc_idx==bc_row.bc_idx"
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
			<div ng-hide="m_row.m_idx>0" class="text-center">
				로그인이 필요합니다.
			</div>
			<form ng-if="m_row.m_idx>0" name="wform" ng-submit="submitComment()" ng-class="{'wform-answer':form.mode=='answer'}">
				<input type="hidden" class="form-control" ng-model="form.mode" name="mode" value="write">
				<input type="hidden" class="form-control" ng-model="form.bc_idx" name="bc_idx" value="">
				<dl>
					<dt ng-show="form.mode!='edit'" class="sc_title"><span class="nick bc_nick">{{m_row.m_nick}}</span></dt>
					<dd class="bc_comment">
					<div class="form-group">
						<textarea 
						ng-maxlength="60000"
						ng-disabled="form.mode=='delete'" 
						ng-model="form.bc_comment"
						ng-trim="true"
						name="bc_comment"  class="form-control" rows="3" placeholder="댓글내용" required></textarea>
						<div role="alert">
						<div class="alert alert-danger" role="alert" ng-show="wform.bc_comment.$error.maxlength">내용이 너무 많습니다!</div>
						</div>
					</div>
					<div class="form-group text-right">
						<button ng-disabled="!wform.$valid" type="submit" class="btn btn-warning">확인</button>
					</div>
					</dd>
				</dl>
			</form>
		</li>
	</ul>
</div>
</div>
<script>
var bbsComment = angular.module('bbsComment', ['ngSanitize']);
bbsComment.filter('nl2br', ['$sanitize', function($sanitize) {
	var tag = (/xhtml/i).test(document.doctype) ? '<br />' : '<br>';
	return function(msg) {
		msg = (msg + '').replace(/(\r\n|\n\r|\r|\n|&#10;&#13;|&#13;&#10;|&#10;|&#13;)/g, tag + '$1');
		return $sanitize(msg);
	};
}]);
bbsComment.filter('space2nbsp', ['$sanitize', function($sanitize) {
	return function(msg) {
		msg = (msg + '').replace(/  /g, ' &nbsp');
		return $sanitize(msg);
	};
}]);
bbsComment.filter('print_date', ['$filter', function($filter) {
	var tag = (/xhtml/i).test(document.doctype) ? '<br />' : '<br>';
	return function(v) {
			var d = new Date(v);
			return $filter('date')(d,'yy.M.d HH:mm');
	};
}]);
bbsComment.filter('print_depth', ['$filter', function($filter) {
	return function(v,min) {
		if(min==undefined){
			min = 10
		}
			return Math.min(v,min);
	};
}]);

bbsComment.controller('CommentCtrl', ['$scope', '$http','$httpParamSerializer','$filter', function ($scope,$http,$httpParamSerializer,$filter) {
	$scope.form = {"bc_comment":"","mode":"write","bc_idx":""};
	
	this.init = function( comment_url) {
		$scope.comment_url = comment_url;
		$scope.initData();
	}

	$scope.comment_url = '';//통신URL
  $scope.bc_rows = [{"bc_idx":"-","b_idx":"-","m_idx":"-","bc_name":"","bc_comment":"-","bc_insert_date":"",}]; //Model, 커멘트 배열
	$scope.x = function(){
		return 'xxx';
	}
	
	
	//통신 결과처리:성공
	$scope.callback_success = function(data, status, headers, config){
		$scope.bc_rows = data.bc_rows;
		$scope.m_row = data.m_row;
		if(data.bc_idx){
			//setTimeout(function(){ document.location.assign("#cmt_"+data.bc_idx);} , 500);
			//setTimeout(function(){ angular.element("#cmt_"+data.bc_idx).focus();} , 500);
		}
	}
	//통신 결과처리:실패
	$scope.callback_error = function(data, status, headers, config){
		$scope.bc_rows =[];
		$scope.m_row = [];
	}
	//데이터초기처리
	$scope.initData = function(){
		$http({
			method: 'GET',
			url: $scope.comment_url,
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.call_ajax = function(d){
		$http({
			url: this.comment_url,
			method: 'POST',
			data: $httpParamSerializer(d),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success(function(data, status, headers, config){
			$scope.form.bc_comment = '';
			$scope.callback_success(data, status, headers, config);
		})
		.error($scope.callback_error);
	}
	$scope.mode_write= function(){
		$scope.call_ajax(this.form);
	}
	$scope.mode_edit= function($index){
		$scope.call_ajax(this.form);
	}
	$scope.mode_answer= function($index){
		$scope.call_ajax(this.form);
	}
	$scope.mode_delete= function($index){
		var d = angular.copy(this.form)
		d.mode='delete';
		if(!this.bc_rows[$index]){
			return false;
		}
		d.bc_idx = this.bc_rows[$index].bc_idx;
		if(!confirm('삭제하시겠습니까?')){
			return false;
		}
		
		$scope.call_ajax(d);
	}
	$scope.set_mode=function(mode,$index,nofocus){
		this.form.mode = mode;
		if(this.bc_rows[$index]){
			var bc_idx = this.bc_rows[$index].bc_idx;
			var id = '#bc_idx_'+bc_idx;
			if(mode =='answer'){
				this.form.bc_comment = '';
			}else{
				this.form.bc_comment = this.bc_rows[$index].bc_comment;
			}
		}else{
			var bc_idx = '';
			var id = '#bc_idx_write';
			this.form.bc_comment = '';
		}
		this.form.bc_idx = bc_idx;
		
		angular.element(id).append(document.wform);
		if(!nofocus){
			setTimeout(function(){ document.wform.bc_comment.focus(); },100);
		}
	}
	$scope.submitComment = function($index){
		if(!this['mode_'+this.form.mode]){
			console.log('ERROR');
			return false;
		}
		this['mode_'+this.form.mode]($index);
		this.set_mode('write','write',true);
		return false;
	}
}]);

</script>