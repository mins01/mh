<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/'.$conf['menu']['mn_arg2'];
?>
<h4>회원관리</h4>
<div ng-app="memmngApp">
<div ng-view></div>
	<div ng-controller="listCtrl as listCtrl" ng-init="listCtrl.init('<?=$json_url?>')">
			<div class="table-responsive" >
				<table class="table table-condensed table-bordered table-striped">
					<col style="width:80px">
					<col style="width:80px">
					<col style="">
					<thead>
						<tr>
							<th class="text-center">idx</th>
							<th class="text-center">아이디</th>
							<th class="text-center">닉네임</th>
							<th class="text-center">상태</th>
							<th class="text-center">가입일</th>
							<th class="text-center">상세</th>
						</tr>
					</thead>
					<tr ng-repeat="m_row in m_rows">
						<td  class="text-center" ng-bind="m_row.m_idx"></td>
						<td  class="text-center" ng-bind="m_row.m_id"></td>
						<td  class="text-center" ng-bind="m_row.m_nick"></td>
						<td  class="text-center" ng-bind="m_row.m_status"></td>
						<td  class="text-center" ng-bind="m_row.m_insert_date"></td>
						<th>-</th>
					</tr>
				</table>
			</div>
			<div class="text-center">
				<ul class="pagination">					
					<li ng-click="call_lists(p)" ng-class="{'active': (p==page)}" ng-repeat="p in pagination_nums track by $index"><a title="{{p}} page" href="#{{p}}" aria-label="{{p}} page" ng-bind="p"></a></li>
				</ul>
			</div>
	</div>
	
</div>


<script>
var memmngApp = angular.module('memmngApp', []);
memmngApp.controller('listCtrl', ['$scope','$http','$httpParamSerializer', function ($scope,$http,$httpParamSerializer) {
	this.init = function(json_url){
		$scope.json_url = json_url;
		$scope.call_first();
	}
	$scope.limit = 10;
	$scope.page = 1;
	$scope.max_page = 0;
	$scope.m_cnt = 0;
	$scope.m_rows = [];
	$scope.pagination_nums = [];
	
	$scope.set_page = function(page){
		$scope.page = page;
		//$scope.m_cnt = m_cnt;
		$scope.max_page = Math.ceil($scope.m_cnt/$scope.limit);
		var t0 = Math.max(1,($scope.page-3)*$scope.limit );
		var t1 = Math.min(t0+6,$scope.max_page);
		$scope.pagination_nums = [];
		while(t0<=t1){
			$scope.pagination_nums.push(t0++);
		}
		//console.log($scope.pagination_nums);
	}
	
	//통신 결과처리:성공
	$scope.callback_success = function(data, status, headers, config){
		//console.log(data);
		if(data.m_rows){
			$scope.m_rows = data.m_rows;
		}
		if(data.m_cnt){
			$scope.m_cnt = data.m_cnt;
		}
		if(data.msg && data.msg.length>0){
			alert(data.msg);
		}
		if(data.offset != undefined){
			$scope.set_page(Math.ceil(parseInt(data.offset,10)/$scope.limit)+1);
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
	$scope.call_first = function(){
		$http({
			url: $scope.json_url+'/first?limit='+$scope.limit,
			method: 'GET',
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.call_lists = function(p){
		p = Math.max(1,parseInt(p));
		var offset = (p-1)*$scope.limit;
		$http({
			url: $scope.json_url+'/lists?limit='+$scope.limit+'&offset='+offset,
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