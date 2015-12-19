<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/'.$conf['menu']['mn_arg2'];
?>
<h4>메뉴설정</h4>
<div ng-app="menuApp" class="row" ng-controller="treeCtrl as treeCtrl" ng-init="treeCtrl.init('<?=$json_url?>')">
	<script type="text/ng-template" id="field_renderer.html">
		<span class="menu-label">
			<span  ng-click="form_update(mn)" ng-bind="mn.mn_text"></span>
				<button ng-click="form_update(mn)" title="edit" class="btn btn-link btn-xs glyphicon glyphicon-edit"></button><button ng-click="form_appendChild(mn)" title="add child" class="btn btn-link btn-xs glyphicon glyphicon-plus-sign"></button>
		</span>
			<ul>
					<li ng-repeat="mn in mn.child" ng-class="{active: selected_obj.mode=='update' && mn.mn_id==selected_obj.mn_id || selected_obj.mode=='insert' &&mn.mn_id==selected_obj.mn_parent_id}"  ng-include="'field_renderer.html'"></li>
			</ul>
	</script>
	<div class="col-md-4">
		<div class="menu-tree">
		<ul>
			<li ng-repeat="mn in mn_tree" ng-class="{active: mn.mn_id==selected_obj.mn_id}" ng-include="'field_renderer.html'">
			</li>
		</ul>
		</div>
	</div>
	<div class="col-md-8">
		<div  ng-show="selected_obj == null" >
			<h2>메뉴를 선택해주시기 바랍니다.</h2>
		</div>
		<form name="formInfo" class="form-horizontal" ng-submit="form_submit()" ng-show="selected_obj != null" >
			<div class="form-group">
					<label class="col-sm-2 control-label">메뉴아이디</label>
					<div class="col-sm-4">
						<input type="text" class="form-control" name="mn_id" placeholder="{{selected_obj.mn_parent_id}}-?" ng-model="selected_obj.mn_id" required  ng-minlength="1" ng-maxlength="20" maxlength="20" ng-readonly="selected_obj.mode!='insert'"
						>
						<div class="error text-danger" ng-show="!formInfo.mn_id.$valid ">{{formInfo.mn_id.$error}}</div>
					</div>
					<label class="col-sm-2 control-label">부모메뉴</label>
					<div class="col-sm-4">
						<select class="form-control" name="mn_parent_id"  placeholder="mn_parent_id" ng-model="selected_obj.mn_parent_id" required ng-minlength="1" ng-maxlength="10">
							<!-- <option  value="" >#NONE#</option> -->
							<option  ng-repeat="(k,v) in mn_parent_id_lists" value="{{k}}" >[{{k}}] {{v}}</option>
						</select>
						
							
						<div class="error text-danger" ng-show="!formInfo.mn_parent_id.$valid">{{formInfo.mn_parent_id.$error}}</div>
					</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">패턴</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_uri" placeholder="mn_uri" ng-model="selected_obj.mn_uri" ng-minlength="0" ng-maxlength="100">
				*메뉴 매칭용(보통 URL과 같음)
				<div class="error text-danger" ng-show="!formInfo.mn_uri.$valid ">{{formInfo.mn_uri.$error}}</div>
				</div>
				<label class="col-sm-2 control-label">URL</label>
				<div class="col-sm-4">
				<input type="text" class="form-control" name="mn_url" placeholder="mn_url" ng-model="selected_obj.mn_url" ng-minlength="0" ng-maxlength="100">
				*이동할 경로
				<div class="error text-danger" ng-show="!formInfo.mn_url.$valid">{{formInfo.mn_url.$error}}</div>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메뉴라벨</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_text" ng-model="selected_obj.mn_text" >
				</div>
				<label class="col-sm-2 control-label">정렬순서</label>
				<div class="col-sm-4">
					<select class="form-control" placeholder="mn_sort" ng-model="selected_obj.mn_sort"  required >
						<option value="" >#NONE#</option>
						<option ng-repeat="v in [-1,0,1,2,3,4,5,6,7,8,9]" value="{{v}}" >{{v}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">메뉴사용</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_obj.mn_use" value="1" ng-checked="selected_obj.mn_use=='1'" ng-disabled="selected_obj.mn_lock=='1'">사용</label>
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_obj.mn_use" value="0" ng-checked="selected_obj.mn_use=='0'" ng-disabled="selected_obj.mn_lock=='1'">금지</label>
					</div>
				</div>
				<label class="col-sm-2 control-label">숨김메뉴</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_hide" ng-model="selected_obj.mn_hide" value="0" ng-checked="selected_obj.mn_hide=='0'" ng-disabled="selected_obj.mn_lock=='1'">일반메뉴</label>
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_obj.mn_hide" value="1" ng-checked="selected_obj.mn_hide=='1'" ng-disabled="selected_obj.mn_lock=='1'">숨김메뉴</label>
						
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="col-sm-2 control-label">모듈</label>
				<div class="col-sm-4">
					<select class="form-control" name="mn_module"  placeholder="mn_module" ng-model="selected_obj.mn_module"  ng-disabled="selected_obj.mn_lock=='1'">
						<option  value="" >#NONE#</option>
						<option  ng-repeat="val in module_lists" value="{{val}}" >{{val}}</option>
					</select>
				</div>

				<label class="col-sm-2 control-label">모듈인자1</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_arg1" ng-model="selected_obj.mn_arg1" 
					ng-hide="['bbs','page'].indexOf(selected_obj.mn_module)>-1 " 
					ng-disabled="selected_obj.mn_lock=='1'"
					>
					<select class="form-control" placeholder="mn_arg1" 
					ng-model="selected_obj.mn_arg1" 
					ng-disabled="selected_obj.mn_module!='bbs' || selected_obj.mn_lock=='1'" 
					ng-hide="selected_obj.mn_module!='bbs'"  >
						<option value="" >#게시판 아이디#</option>
						<option ng-repeat="(k, v) in bbs_lists" value="{{k}}" >[{{k}}] {{v}}</option>
					</select>
					<select class="form-control" placeholder="mn_arg1" 
					ng-model="selected_obj.mn_arg1" 
					ng-disabled="selected_obj.mn_module!='page' || selected_obj.mn_lock=='1'" 
					ng-hide="selected_obj.mn_module!='page'"  >
						<option value="" >#페이지 파일#</option>
						<option ng-repeat="(k, v) in page_lists" value="{{k}}" >{{v}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모듈인자2</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_arg2" ng-model="selected_obj.mn_arg2" ng-disabled="selected_obj.mn_lock=='1'">
				</div>
				<label class="col-sm-2 control-label">모듈인자3</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_arg3" ng-model="selected_obj.mn_arg3" ng-disabled="selected_obj.mn_lock=='1'">
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="col-sm-12 ">&lt;head&gt; 속 추가내용</label>
				<div class="col-sm-12">
					<textarea type="text" maxlength="50000" class="form-control" placeholder="mn_head_contents" ng-model="selected_obj.mn_head_contents" ng-disabled="selected_obj.mn_lock=='1'"></textarea>
				</div>
				<label class="col-sm-12 ">상단 HTML</label>
				<div class="col-sm-12">
					<textarea type="text" maxlength="50000" class="form-control" placeholder="mn_top_html" ng-model="selected_obj.mn_top_html" ng-disabled="selected_obj.mn_lock=='1'"></textarea>
				</div>
			</div>
			<hr>
			<div class="form-group text-right">
				<div class="col-sm-12">
					<button  ng-show="selected_obj.mode=='insert'" class="btn btn-default">자식메뉴 등록</button>
					<button  ng-show="selected_obj.mode=='update'" class="btn btn-default">수정</button>
					<button type="button" ng-show="selected_obj.mode=='update' && selected_obj.child.length==0 && selected_obj.mn_lock=='0'" ng-click="delete();" class="btn btn-default btn-danger pull-left">삭제</button>
					<button type="button" ng-show="selected_obj.mode=='update' && selected_obj.child.length>0 && selected_obj.mn_lock=='0'" disabled class="btn btn-default btn-danger pull-left">자식메뉴가 있어서 삭제 불가!</button>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">자식메뉴</label>
				<div class="col-sm-10">
				<span ng-repeat="mn in selected_obj.child" ng-click="form_update(mn)" > <span class="label label-primary" >[{{$index}}] {{mn.mn_id}}:{{mn.mn_text}}</span> </span>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="col-sm-2 control-label">메뉴잠금</label>
				<div class="col-sm-10">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_lock" ng-model="selected_obj.mn_lock" value="1" ng-checked="selected_obj.mn_lock=='1'" >잠금</label>
						<label ><input type="radio" placeholder="mn_use" ng-model="selected_obj.mn_lock" value="0" ng-checked="selected_obj.mn_lock=='0'" >해제</label>
						
						<span class="text-danger">(잠긴 메뉴는 대부분 중요한 메뉴입니다.)</span>
					</div>
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
	$scope.selected_obj = null;
	$scope.mn_tree = [
		{'mn_uri':'','mn_text':'Root','child':
		[
			{'mn_uri':'bbs','mn_text':'bbs','child':[]},
		]
	}];
	$scope.form = {};
	$scope.mn_parent_id_lists = {};
	$scope.form_update=function(menu){
		$scope.selected_obj = {}
		if(menu.mn_id){
			$scope.selected_obj  = angular.copy(menu);
			$scope.selected_obj.mode='update';
		}else{
			$scope.selected_obj.mode='';
		}
		//console.log($scope.selected_obj.mode);
	}
	$scope.form_appendChild=function(menu){
		$scope.selected_obj = {}
		$scope.selected_obj.mn_parent_id  = menu.mn_id;
		$scope.selected_obj.mode='insert';
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
		$scope.mn_parent_id_lists = {};
		$scope.mn_parent_id_lists_push($scope.mn_tree);
		//console.log($scope.mn_parent_id_lists,$scope.mn_rows);
	}
	$scope.mn_parent_id_lists_push = function(menus){
		for(var i=0,m=menus.length;i<m;i++){
			var mn = menus[i];
			$scope.mn_parent_id_lists[mn.mn_id] = mn.mn_text;
			if(mn.child && mn.child.length>0){
				$scope.mn_parent_id_lists_push(mn.child);
			}
		}
	}
	
	//통신 결과처리:성공
	$scope.callback_success = function(data, status, headers, config){
		//console.log(data);
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
		if(data.page_lists){
			$scope.page_lists = data.page_lists;
		}
		if(data.mn_id){
			if($scope.mn_rows[data.mn_id]){
				$scope.form_update($scope.mn_rows[data.mn_id]);
			}else{
				$scope.form_update({});
			}
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
		switch($scope.selected_obj.mode){
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
			data: $httpParamSerializer($scope.selected_obj),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	//데이터수정
	$scope.call_update = function(){
		//console.log($scope.formInfo.$error);
		if(!$scope.formInfo.$valid){
			return false;
		}
		$http({
			url: $scope.json_url+'/update',
			method: 'POST',
			data: $httpParamSerializer($scope.selected_obj),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.delete = function(){
		if(confirm('삭제하시겠습니까?\n복구가 불가능합니다.\n\n메뉴사용 금지로 설정하시길 추천합니다.')){
			return $scope.call_delete();
		}
		return false;
	}
	//데이터삭제
	$scope.call_delete = function(){
		//console.log($scope.formInfo.$error);
		if(!$scope.formInfo.mn_id.$valid && $scope.selected_obj.mn_id){
			return false;
		}
		$http({
			url: $scope.json_url+'/delete',
			method: 'POST',
			data: $httpParamSerializer({mn_id:$scope.selected_obj.mn_id}),
			headers: {
				'Content-Type': 'application/x-www-form-urlencoded'
			}
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	
}]);

</script>