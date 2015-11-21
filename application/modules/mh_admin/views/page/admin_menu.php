<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/json_menu';
?>
<h2>메뉴설정</h2>
<div ng-app="menuApp" class="row" ng-controller="treeCtrl as treeCtrl" ng-init="treeCtrl.init('<?=$json_url?>')">
	<script type="text/ng-template" id="field_renderer.html">
		<span class="menu-label">
			<span  ng-click="form_update(mn)" ng-bind="mn.mn_text"></span>
				<button ng-click="form_update(mn)" title="edit" class="btn btn-link btn-xs glyphicon glyphicon-edit"></button><button ng-click="form_appendChild(mn)" title="add child" class="btn btn-link btn-xs glyphicon glyphicon-plus-sign"></button>
		</span>
			<ul>
					<li ng-repeat="mn in mn.child" ng-class="{active: selected_menu.mode=='update' && mn.mn_id==selected_menu.mn_id || selected_menu.mode=='insert' &&mn.mn_id==selected_menu.mn_parent_id}"  ng-include="'field_renderer.html'"></li>
			</ul>
	</script>
	<div class="col-md-4">
		<div class="menu-tree">
		<ul >
			<li ng-repeat="mn in mn_tree" ng-class="{active: mn.mn_id==selected_menu.mn_id}" ng-include="'field_renderer.html'">
			</li>
		</ul>
		</div>
	</div>
	<div class="col-md-8">
		<form name="formInfo" class="form-horizontal" ng-submit="form_submit()" >
			<div class="form-group">
					<label class="col-sm-2 control-label">메뉴아이디</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="mn_id" placeholder="{{selected_menu.mn_parent_id}}-?" ng-model="selected_menu.mn_id" required  ng-minlength="1" ng-maxlength="10" ng-readonly="selected_menu.mode!='insert'">
						<div class="error text-danger" ng-show="!formInfo.mn_id.$valid ">{{formInfo.mn_id.$error}}</div>
					</div>
					<label class="col-sm-2 control-label">부모메뉴</label>
					<div class="col-sm-4">
						<select class="form-control" name="mn_parent_id"  placeholder="mn_parent_id" ng-model="selected_menu.mn_parent_id" required ng-minlength="1" ng-maxlength="10">
							<option  value="" >#NONE#</option>
							<option  ng-repeat="mn in mn_rows" value="{{mn.mn_id}}" >[{{mn.mn_id}}] {{mn.mn_text}}</option>
						</select>
						
							
						<div class="error text-danger" ng-show="!formInfo.mn_parent_id.$valid">{{formInfo.mn_parent_id.$error}}</div>
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">패턴</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_uri" placeholder="mn_uri" ng-model="selected_menu.mn_uri"  ng-minlength="0" ng-maxlength="100">
				*메뉴 매칭용(보통 URL과 같음)
				<div class="error text-danger" ng-show="!formInfo.mn_uri.$valid ">{{formInfo.mn_uri.$error}}</div>
				</div>
				<label class="col-sm-2 control-label">URL</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_url" placeholder="mn_url" ng-model="selected_menu.mn_url" ng-minlength="0" ng-maxlength="100">
				*이동할 경로
				<div class="error text-danger" ng-show="!formInfo.mn_url.$valid">{{formInfo.mn_url.$error}}</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메뉴라벨</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_text" ng-model="selected_menu.mn_text" >
				</div>
				<label class="col-sm-2 control-label">정렬순서</label>
				<div class="col-sm-4">
					<select class="form-control" placeholder="mn_sort" ng-model="selected_menu.mn_sort"  required >
						<option value="" >#NONE#</option>
						<option ng-repeat="v in [-1,0,1,2,3,4,5,6,7,8,9]" value="{{v}}" >{{v}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모듈</label>
				<div class="col-sm-4">
					<select class="form-control" name="mn_module"  placeholder="mn_module" ng-model="selected_menu.mn_module" >
						<option  value="" >#NONE#</option>
						<option  ng-repeat="val in module_lists" value="{{val}}" >{{val}}</option>
					</select>
				</div>

				<label class="col-sm-2 control-label">모듈인자1</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_arg1" ng-model="selected_menu.mn_arg1" 
					ng-hide="selected_menu.mn_module=='bbs'"
					>
					<select class="form-control" placeholder="mn_arg1" ng-model="selected_menu.mn_arg1" ng-disabled="selected_menu.mn_module!='bbs'" ng-hide="selected_menu.mn_module!='bbs'"  >
						<option value="" >#NONE#</option>
						<option ng-repeat="(k, v) in bbs_lists" value="{{k}}" >[{{k}}] {{v}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메뉴사용</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_menu.mn_use" value="1" ng-checked="selected_menu.mn_use=='1'">사용</label>
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_menu.mn_use" value="0" ng-checked="selected_menu.mn_use=='0'">금지</label>
					</div>
				</div>
				<label class="col-sm-2 control-label">숨김메뉴</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_hide" ng-model="selected_menu.mn_hide" value="0" ng-checked="selected_menu.mn_hide=='0'">일반메뉴</label>
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_menu.mn_hide" value="1" ng-checked="selected_menu.mn_hide=='1'">숨김메뉴</label>
						
					</div>
				</div>
			</div>
			<div class="form-group text-right">
				<div class="col-sm-12">
					<button  ng-show="selected_menu.mode=='insert'" class="btn btn-default">하위메뉴 등록</button>
					<button  ng-show="selected_menu.mode=='update'" class="btn btn-default">수정</button>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">하위메뉴</label>
				<div class="col-sm-10">
				<span ng-repeat="mn in selected_menu.child" ng-click="form_update(mn)" > <span class="label label-primary" >[{{$index}}] {{mn.mn_id}}:{{mn.mn_text}}</span> </span>
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
	//$scope.temp = '1';
	$scope.module_lists = [];
	$scope.selected_menu = {};
	$scope.mn_tree = [
		{'mn_uri':'','mn_text':'Root','child':
		[
			{'mn_uri':'bbs','mn_text':'bbs','child':[]},
		]
	}];
	$scope.form = {};
	$scope.form_update=function(menu){
		$scope.selected_menu = {}
		$scope.selected_menu  = angular.copy(menu);
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
		if(data.module_lists){
			$scope.module_lists = data.module_lists;
		}
		if(data.bbs_lists){
			$scope.bbs_lists = data.bbs_lists;
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
			url: $scope.json_url+'/first',
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