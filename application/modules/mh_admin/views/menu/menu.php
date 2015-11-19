<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/json_menu';
?>
<h2>메뉴설정</h2>
<div ng-app="menuApp" class="row" ng-controller="treeCtrl as treeCtrl" ng-init="treeCtrl.init('<?=$json_url?>')">
	<script type="text/ng-template" id="field_renderer.html">
			<span  ng-click="form_update(mn)" ng-bind="mn.mn_text">▲</span>
				<button ng-click="form_update(mn)" title="add child" class="btn btn-default btn-xs">E</button>
				<button ng-click="form_appendChild(mn)" title="add child" class="btn btn-default btn-xs">+</button>
			<ul>
					<li ng-repeat="mn in mn.child" ng-class="{active: selected_menu.mode=='update' && mn.mn_id==selected_menu.mn_id || selected_menu.mode=='insert' &&mn.mn_id==selected_menu.mn_parent_id}"  ng-include="'field_renderer.html'"></li>
			</ul>
	</script>
	<div class="col-md-4">
		<ul class="menu-tree" >
			<li ng-repeat="mn in mn_tree" ng-class="{active: mn.mn_id==selected_menu.mn_id}" ng-include="'field_renderer.html'">
			</li>
		</ul>
	</div>
	<div class="col-md-8">
		<form name="formInfo" class="form-horizontal" ng-submit="form_submit()" >
			<div class="form-group">
					<label class="col-sm-2 control-label">mn_id</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="mn_id" placeholder="{{selected_menu.mn_parent_id}}-?" ng-model="selected_menu.mn_id" required  ng-minlength="1" ng-maxlength="10" ng-readonly="selected_menu.mode!='insert'">
						<div class="error text-danger" ng-show="!formInfo.mn_id.$valid ">{{formInfo.mn_id.$error}}</div>
					</div>
					<label class="col-sm-2 control-label">mn_parent_id</label>
					<div class="col-sm-4">
						<select class="form-control" name="mn_parent_id"  placeholder="mn_parent_id" ng-model="selected_menu.mn_parent_id" required ng-minlength="1" ng-maxlength="10">
							<option  value="" >#NONE#</option>
							<option  ng-repeat="mn in mn_rows" value="{{mn.mn_id}}" >[{{mn.mn_id}}] {{mn.mn_text}}</option>
						</select>
						
							
						<div class="error text-danger" ng-show="!formInfo.mn_parent_id.$valid">{{formInfo.mn_parent_id.$error}}</div>
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">mn_uri</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_uri" placeholder="mn_uri" ng-model="selected_menu.mn_uri"  ng-minlength="0" ng-maxlength="100">
				*메뉴 매칭 경로
				<div class="error text-danger" ng-show="!formInfo.mn_uri.$valid ">{{formInfo.mn_uri.$error}}</div>
				</div>
				<label class="col-sm-2 control-label">mn_url</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_url" placeholder="mn_url" ng-model="selected_menu.mn_url" ng-minlength="0" ng-maxlength="100">
				*이동할 경로
				<div class="error text-danger" ng-show="!formInfo.mn_url.$valid">{{formInfo.mn_url.$error}}</div>
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
				<span ng-repeat="mn in selected_menu.child" ng-click="form_update(mn)" > <span class="label label-primary" >[{{$index}}] {{mn.mn_id}}:{{mn.mn_text}}</span> </span>
				</div>
			</div>
			<div class="form-group text-right">
				<div class="col-sm-12">
					<button  ng-show="selected_menu.mode=='insert'" class="btn btn-default">하위메뉴 등록</button>
					<button  ng-show="selected_menu.mode=='update'" class="btn btn-default">수정</button>
				</div>
			</div>
		</form>
	</div>

</div>


<script>
var menuApp = angular.module('menuApp', []);
menuApp.controller('treeCtrl', ['$scope','$http','$httpParamSerializer', function ($scope,$http,$httpParamSerializer) {
	this.init = function(json_url){
		$scope.json_url = json_url;
		$scope.call_lists();
	}
	$scope.temp = '1';
	$scope.selected_menu = {};
	$scope.mn_tree = [
		{'mn_uri':'','mn_text':'Root','child':
		[
			{'mn_uri':'bbs','mn_text':'bbs','child':[]},
			{'mn_uri':'etc','mn_text':'etc','child':[]},
		]
	}];
	$scope.form = {};
	$scope.form_update=function(menu){
		$scope.selected_menu = {}
		$scope.selected_menu  = menu;
		$scope.selected_menu.mode='update';
		console.log($scope.selected_menu.mode);
	}
	$scope.form_appendChild=function(menu){
		$scope.selected_menu = {}
		$scope.selected_menu.mn_parent_id  = menu.mn_id;
		$scope.selected_menu.mode='insert';
	}
	//트리모양으로 만든다.
	$scope.set_mn_rows =function(mn_rows){
		$scope.mn_rows = mn_rows;
		$scope.mn_tree = [];
		for(var x in $scope.mn_rows){
			var mn_row = $scope.mn_rows[x];
			if(mn_row['mn_parent_id'] == mn_row['mn_id']){
				$scope.mn_tree.push(mn_row);
				mn_row['mn_parent_id'].child = [];
			}else if($scope.mn_rows[mn_row['mn_parent_id']]){
				if(!$scope.mn_rows[mn_row['mn_parent_id']].child){
					$scope.mn_rows[mn_row['mn_parent_id']].child = [];
				}
				$scope.mn_rows[mn_row['mn_parent_id']].child.push(mn_row);
			}
		}
		console.log($scope.mn_tree);
	}
	//통신 결과처리:성공
	$scope.callback_success = function(data, status, headers, config){
		console.log(data);
		if(data.mn_rows){
			$scope.set_mn_rows(data.mn_rows);
		}
		if(data.msg && data.msg.length>0){
			alert(data.msg);
		}
	}
	//통신 결과처리:실패
	$scope.callback_error = function(data, status, headers, config){
		$scope.bc_rows =[];
		$scope.m_row = [];
	}
		
	//데이터초기처리
	$scope.call_lists = function(){
		$http({
			url: $scope.json_url+'/lists',
			method: 'GET',
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.form_submit = function(){
		switch($scope.selected_menu.mode){
			case "insert":$scope.call_insert();break;
			case "update":$scope.call_update();break;
		}
	}
	//데이터삽입
	$scope.call_insert = function(){
		if(!$scope.formInfo.$valid){
			return false;
		}
		$http({
			url: $scope.json_url+'/insert',
			method: 'POST',
			data: $httpParamSerializer($scope.selected_menu),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	//데이터수정
	$scope.call_update = function(){
		console.log($scope.formInfo.$error);
		if(!$scope.formInfo.$valid){
			return false;
		}
		$http({
			url: $scope.json_url+'/update',
			method: 'POST',
			data: $httpParamSerializer($scope.selected_menu),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	
}]);

</script>