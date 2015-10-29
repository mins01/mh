<?
//$comment_url 
?><div ng-app="bbsComment">
<div class="panel panel-default bbs-mode-comment"  ng-controller="CommentCtrl" >
	<div class="panel-heading">
		덧글
	</div>
	<ul class="list-group">
		<li class="list-group-item form-inline">
			<dl>
				<dt class="sc_title"><span class="nick bc_nick">bc_row.bc_name</span> / <span class="date bc_insert_date">bc_row.bc_insert_date</span></dt>
				<dd class="bc_comment">bc_row.bc_comment</dd>
			</dl>
		</li>
		<li class="list-group-item form-inline"  ng-repeat="bc_row in bc_rows">
			<dl>
				<dt class="sc_title"><span class="nick bc_nick">{{bc_row.bc_name}}</span> / <span class="date bc_insert_date">{{bc_row.bc_insert_date}}</span></dt>
				<dd class="bc_comment">{{bc_row.bc_comment}}</dd>
			</dl>
		</li>

	</ul>
</div>
</div>
<script>

var bbsComment = angular.module('bbsComment', [])
bbsComment.controller('CommentCtrl', ['$scope', '$http', function ($scope,$http) {
	var comment_url = "<?=html_escape($comment_url )?>";
  $scope.bc_rows = [
	{"bc_nick":"1111","bc_insert_date":"bc_insert_date","bc_comment":"bc_comment",},
	{"bc_nick":"2222","bc_insert_date":"bc_insert_date","bc_comment":"bc_comment",},
	{"bc_nick":"3333","bc_insert_date":"bc_insert_date","bc_comment":"bc_comment",},
	{"bc_nick":"4444","bc_insert_date":"bc_insert_date","bc_comment":"bc_comment",},
	];
	
	$http({
		method: 'GET',
		url: comment_url,
	})
	.success(function (data, status, headers, config) {
		$scope.bc_rows = data.bc_rows;
	})
	.error(function (data, status, headers, config) {
		$scope.bc_rows = array();
	});
	
}]);
</script>