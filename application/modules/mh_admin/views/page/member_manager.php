<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/'.$conf['menu']['mn_arg2'];
?>

<div ng-app="memmngApp">
<h4>회원관리 <span ng-bind="m_cnt"></span></h4>
	<div ng-controller="listCtrl as listCtrl" ng-init="listCtrl.init('<?=$json_url?>')">
		<div class="list" ng-show="mode=='list'">
			<div class="text-right"><span ng-bind="m_cnt"></span> 회원</div>
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
							<th class="text-center">상태/레벨</th>
							<th class="text-center">가입일</th>
							<th class="text-center">상세</th>
						</tr>
					</thead>
					<tbody ng-repeat="m_row in m_rows">
						<tr >
							<td  class="text-center" ng-bind="m_row.m_idx"></td>
							<td  class="text-center" >
							<button type="button" class="btn btn-link btn-xs" ng-bind="m_row.m_id" ng-click="go_url({mode:'update',m_idx:m_row.m_idx})">Link</button>
							</td>
							<td  class="text-center" ng-bind="m_row.m_nick"></td>
							<td  class="text-center">
								<span ng-show="m_row.m_level==-1" class="ng-hide label label-default">사용금지</span>
								<span ng-show="m_row.m_level==0" class="ng-hide label label-default">비회원</span>
								<span ng-show="m_row.m_level==1" class="ng-hide label label-info">회원</span>
								<span ng-show="m_row.m_level==99" class="ng-hide label label-danger">관리자</span>
								,
								<span ng-show="m_row.m_isout==0" class="ng-hide label label-info">사용중</span>
								<span ng-show="m_row.m_isout==1" class="ng-hide label label-default">탈퇴됨</span>
							
							</td>
							<td  class="text-center" ng-bind="m_row.m_insert_date"></td>
							<th  class="text-center" ><button ng-click="go_url({mode:'update',m_idx:m_row.m_idx})" class="btn btn-default btn-xs">상세</button></th>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="text-center">
				<div>
					<form  name="formInfo" class="form-inline text-center" ng-submit="form_search_submit()" >
					<div class="form-group">
						<div class="input-group">
							<div class="input-group-btn">
								<select name="tq" class="selectpicker show-tick" style="width:4em" data-width="80px" aria-label="검색대상" ng-model="form_v_search.tq" >
								<option value="_all_" >#all#</option>
								<option value="m_idx" >m_idx</option>
								<option value="m_id" >m_id</option>
								<option value="m_nick" >m_nick</option>
								</select>
							</div>
							<input name="q" ng-model="form_v_search.q" aria-label="검색어" type="search" class="form-control" placeholder="검색어" value="">
							<span class="input-group-btn">
								<button type="submit" class="btn btn-info">검색</button>
							</span>
						</div><!-- /input-group -->
					</div>
				</form>
				</div>
				<ul class="pagination">					
					<li ng-class="{'active': (p==page)}" ng-repeat="p in pagination_nums track by $index"><a title="{{p}} page" href="javascript:void(0)" ng-click="go_url({mode:'list',page:p})" aria-label="{{p}} page" ng-bind="p"></a></li>
				</ul>
			</div>
		</div>
		<div class="form" ng-show="mode!='list'" class="ng-hide">
			<div >
				<form name="formInfo" class="form-horizontal" ng-submit="form_submit()" >
					<div class="form-group">
						<label class="col-sm-2 control-label">m_idx</label>
						<div class="col-sm-4">
							<input type="text" maxlength="20" class="form-control" name="m_idx" placeholder="#AUTO#" ng-model="selected_m_row.m_idx" readonly>
						</div>
						<label class="col-sm-2 control-label">m_id</label>
						<div class="col-sm-4">
							<input type="text" maxlength="100" class="form-control" name="m_id" placeholder="m_id" ng-model="selected_m_row.m_id">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">m_nick</label>
						<div class="col-sm-4">
							<input type="text" maxlength="50" class="form-control" name="m_nick" placeholder="m_nick" ng-model="selected_m_row.m_nick">
						</div>
						<label class="col-sm-2 control-label">m_isout</label>
						<div class="col-sm-4  form-control-static">
							<label><input type="radio" value="0" ng-model="selected_m_row.m_isout">회원</label>
							,
							<label>
							<input type="radio" value="1"  ng-model="selected_m_row.m_isout">탈퇴회원
							</label>
						</div>
						
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">m_pass <input type="checkbox" ng-checked="able_change_m_pass" ng-click="able_change_m_pass=!able_change_m_pass"></label>
						<div class="col-sm-4">
							<input type="text" maxlength="20" ng-disabled="!able_change_m_pass" class="form-control" name="m_pass" placeholder="m_pass" ng-model="selected_m_row.m_pass">
						</div>
						<label class="col-sm-2 control-label">m_level</label>
						<div class="col-sm-4 form-control-static">
							<label><input type="radio" value="-1" ng-model="selected_m_row.m_level">사용금지</label>
							, <label><input type="radio" value="0" ng-model="selected_m_row.m_level">비회원</label>
							, <label><input type="radio" value="1" ng-model="selected_m_row.m_level">일반회원</label>
							, <label><input type="radio" value="99" ng-model="selected_m_row.m_level">관리자</label>
						</div>
						
					</div>
					<div class="form-group text-right">
						<div class="col-sm-12">
							<a type="button"  class="btn btn-default" onclick="history.back()">목록</a>
							<button type="submit"  class="btn btn-default" >수정</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
	
</div>


<script>
var memmngApp = angular.module('memmngApp', []);
memmngApp.controller('listCtrl', ['$scope','$http','$httpParamSerializer','$location',
	function ($scope,$http,$httpParamSerializer,$location ) {
	this.init = function(json_url){
		$scope.json_url = json_url;
		//$scope.call_first();
	}
	
	$scope.mode = 'list'
	$scope.limit = 5;
	$scope.page = 1;
	$scope.max_page = 0;
	$scope.m_cnt = 0;
	$scope.m_rows = [];
	$scope.pagination_nums = [];
	$scope.selected_m_row = null
	$scope.able_change_m_pass = false;
	$scope.form_v_search={
		tq:'all',
		q:'',
		page:1,
		mode:'list',
	}
	
	$scope.go_url = function(i_args){
		var args = $location.search();
		angular.extend(args, i_args);
		var current_url = $location.url();
		$location.search(args);
		//$location.search(args).replace();
		var new_url = $location.url();
		//console.log($location.search());
		//console.log(current_url,new_url);
		if(current_url==new_url){ //url변화가 없으면 동작 않하므로 수동으로 다시 부른다.
			$scope.action();
		}
	}
	$scope.action = function(event){
		//console.log("route changed in parent");
		var args = $location.search();	
		if(!args.mode){ args.mode = 'list'}
		$scope.mode = args.mode;
		if(args.tq){
			$scope.form_v_search.tq = args.tq;
		}
		if(args.q){
			$scope.form_v_search.q = args.q;
		}
		switch(args.mode){
			case 'insert':
			case 'update':
				$scope.select_m_row($scope.get_m_row_by_m_idx(args.m_idx),args.mode);
				$scope.call_select_by_m_idx(args.m_idx);
			break;
			case 'list':
			default:
				$scope.call_lists(args);
			break;
		}
	}
	$scope.$on('$locationChangeStart', function(event) { 
    $scope.action(event)
	});

	
	
	$scope.get_m_row_by_m_idx = function(m_idx){
		for(var i=0,m=$scope.m_rows.length;i<m;i++){
			if($scope.m_rows[i].m_idx == m_idx){
				return $scope.m_rows[i];
			}
		}
		return null;
	}
	$scope.select_m_row = function(m_row,mode){
		$scope.mode = mode;
		$scope.selected_m_row = angular.copy(m_row);		
	}
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
		$scope.able_change_m_pass = false;
		//console.log(data);
		if(data.m_rows){
			$scope.m_rows = data.m_rows;
		}
		if(data.m_row){
			$scope.selected_m_row = data.m_row;
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
	// $scope.call_first = function(){
		// var args = $location.search();
		// $scope.call_lists(args.page);
	// }
	$scope.call_lists = function(args){
		var p = args.page
		p = Math.max(1,parseInt(p));
		if(isNaN(p)){p = 1;}
		var offset = (p-1)*$scope.limit;
		var url = $scope.json_url+'/lists?limit='+$scope.limit+'&offset='+offset;
		if(args.tq && args.tq){
			url+='&tq='+args.tq+'&q='+encodeURI(args.q);
		}
		$http({
			url: url,
			method: 'GET',
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.call_select_by_m_idx = function(m_idx){
		$http({
			url: $scope.json_url+'/select_by_m_idx?m_idx='+m_idx,
			method: 'GET',
		})
		.success($scope.callback_success)
		.error($scope.callback_error);
	}
	$scope.form_search_submit = function(){
		$scope.go_url($scope.form_v_search);
	}
	$scope.form_submit = function(){
		switch($scope.mode){
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
		$scope.selected_m_row.mode ='update';
		$http({
			url: $scope.json_url+'/update',
			method: 'POST',
			data: $httpParamSerializer($scope.selected_m_row),
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
memmngApp.config(function($locationProvider) {
  $locationProvider.html5Mode(false).hashPrefix('!');
})
</script>