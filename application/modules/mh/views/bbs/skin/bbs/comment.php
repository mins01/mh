<?
//$comment_url 
?><div ng-app="bbsComment">
<div class="panel panel-default bbs-mode-comment"  ng-controller="CommentCtrl as commentCtrl" ng-init="commentCtrl.init('<?=html_escape($comment_url )?>')">
	<div class="panel-heading">
		덧글
	</div>
	<ul class="list-group">
		<li class="list-group-item form-inline"  ng-repeat="bc_row in bc_rows">
			<dl>
				<dt class="sc_title">
				<a id="cmt_{{bc_row.bc_idx}}"  name="cmt_{{bc_row.bc_idx}}"></a>
						<span class="nick bc_nick" ng-bind="bc_row.bc_name"></span> 
						/ <span class="date bc_insert_date" ng-bind="bc_row.bc_insert_date|print_date"></span>
						<div class="pull-right">
							<button ng-click="submitComment($index);" type="button" class="btn btn-xs btn-default">답변</button>
							<button ng-click="submitComment($index);" type="button" class="btn btn-xs btn-default">수정</button>
							<button ng-click="submitComment($index);" type="button" class="btn btn-xs btn-default">삭제</button>
						</div>
						<div class="clearfix"></div>
				</dt>
				<dd class="bc_comment" ng-bind-html="bc_row.bc_comment | linky | nl2br | space2nbsp"></dd>
			</dl>
		</li>
		<li class="list-group-item form-inline"  ng-if="bc_rows.length == 0">
			<dl>
				<dt class="sc_title">
					No Comment
				</dt>
				<dd class="bc_comment text-center">No Comment</dd>
			</dl>
		</li>
		<li class="list-group-item">
			<form name="form_comment" action="javascript:void(0);" ng-submit="submitComment()" >
				<dl>
					<dt class="sc_title"><p class="form-control-static"><span class="nick bc_nick">{{m_row.m_nick}}</span></p></dt>
					<dd class="bc_comment">
					<div class="form-group">
						<textarea name="bc_comment" ng-model="form.bc_comment" class="form-control" rows="3"></textarea>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-default">확인</button>
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


bbsComment.controller('CommentCtrl', ['$scope', '$http','$httpParamSerializer','$filter', function ($scope,$http,$httpParamSerializer,$filter) {
	$scope.form = {"bc_comment":"","mode":"write"};
	
	this.init = function( comment_url) {
		$scope.comment_url = comment_url;
		$scope.initData();
	}

	$scope.comment_url = '';//통신URL
  $scope.bc_rows = [{"bc_idx":"-","b_idx":"-","m_idx":"-","bc_name":"","bc_comment":"-","bc_insert_date":"",}]; //Model, 커멘트 배열
	$scope.x = function(){
		return 'xxx';
	}
	$scope.timeFormat = function(v){
		var d = new Date(v);
		return $filter('date')(d,'yy.M.d HH:mm');
		
	}
	//통신 결과처리:성공
	$scope.callback_success = function(data, status, headers, config){
		$scope.bc_rows = data.bc_rows;
		$scope.m_row = data.m_row;
		if(data.bc_idx){
			setTimeout(function(){ document.location.assign("#cmt_"+data.bc_idx);} , 500);
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
	$scope.mode_write= function(){
		//$http.post(this.comment_url,$httpParamSerializer(this.form))
		$http({
			url: this.comment_url,
			method: 'POST',
			//data: $httpParamSerializerJQLike(this.form),
			data: $httpParamSerializer(this.form),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success(function(data, status, headers, config){
			$scope.form.bc_comment = '';
			$scope.callback_success(data, status, headers, config);
			//setTimeout(function(){ angular.element('[ng-model="form.bc_comment"]').focus();} , 500);
		})
		.error($scope.callback_error);
	}
	
	$scope.submitComment = function($index){
		if(!this['mode_'+this.form.mode]){
			console.log('ERROR');
			return false;
		}
		this['mode_'+this.form.mode]($index);
		return false;
	}
}]);

</script>