<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count

?>


<div ng-app="menuApp" class="row" ng-controller="treeCtrl">
	<script type="text/ng-template" id="field_renderer.html">
			<span  ng-click="click(menu)" ng-bind="menu.mn_text">▲</span>
			 : <button class="btn btn-default btn-xs">▲</button>
			<button class="btn btn-default btn-xs">▼</button>
			<ul>
					<li ng-repeat="menu in menu.child" ng-include="'field_renderer.html'"></li>
			</ul>
	</script>
	<div class="col-md-4">
		<ul >
			<li ng-repeat="menu in mn_rows" ng-include="'field_renderer.html'">
			</li>
		</ul>
	</div>
	<div class="col-md-8">
		<form class="form-horizontal">
			<div class="form-group">
				<label class="col-sm-2 control-label">mn_uri</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" placeholder="mn_uri" ng-model="selected_menu.mn_uri" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">mn_text</label>
				<div class="col-sm-10">
				<input type="text" class="form-control" placeholder="mn_text" ng-model="selected_menu.mn_text" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">child</label>
				<div class="col-sm-10">
				<span ng-repeat="menu in selected_menu.child" > <span class="label label-primary" >{{$index}} {{menu.mn_uri}}:{{menu.mn_text}}</span> </span>
				</div>
			</div>
			<ul>

		</form>
	</div>

</div>


<script>
var menuApp = angular.module('menuApp', []);
menuApp.controller('treeCtrl', ['$scope', function ($scope) {
  // Controller magic
	$scope.temp = '1';
	$scope.selected_menu = {};
	$scope.mn_rows = [
		{'mn_uri':'','mn_text':'Root','child':
		[
			{'mn_uri':'bbs','mn_text':'bbs','child':[]},
			{'mn_uri':'etc','mn_text':'etc','child':[]},
		]
	}];
	$scope.click=function(menu){
		$scope.selected_menu  = menu;
	}
}]);

</script>