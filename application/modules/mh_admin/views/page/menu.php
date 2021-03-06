<?
//$bm_row,$b_rows,$b_n_rows
//$start_num,$count
$json_url = dirname($conf['base_url']).'/'.$conf['menu']['mn_arg2'];
if($conf['menu']['mn_arg2']=='json_admin_menu'){
	$def_mn_m_level = 99;
}else{
	$def_mn_m_level = 0;
}
?>
<script>
var def_mn_m_level = <?=json_encode($def_mn_m_level)?>;
</script>
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_treeList/treeList.css?t=<?=REFLESH_TIME?>">
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_treeList/treeList-theme.css?t=<?=REFLESH_TIME?>">
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_treeList/treeList.js?t=<?=REFLESH_TIME?>"></script>
<link rel="stylesheet" href="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_dragAndDrop/dragAndDrop.css?t=<?=REFLESH_TIME?>">
<script src="<?=SITE_URI_ASSET_PREFIX?>etcmodule/ui_dragAndDrop/dragAndDrop.js?t=<?=REFLESH_TIME?>"></script>
<script>
$(function(){
	// treeList.debug = true;
	// treeList.debug = true;
	treeList.addEvent();
	// dragAndDrop.debug = true;
	dragAndDrop.dropEffect = "move";
	dragAndDrop.enable();
})
</script>
<script>
dragAndDrop.ondrop = function(evt,dropzone,dragNode,dropNode){

	if(dragNode===dropNode){return;}
	// alert(selected_obj);
	// console.log(evt,dropzone,dragNode,dropNode);
	//-- 앞에 위치, 뒤에 위치
	let mask = dragNode.compareDocumentPosition(dropNode)
	// console.log(mask);
	if(mask & Node.DOCUMENT_POSITION_CONTAINED_BY){
		console.log("dropNode의 위치가 dragNode의 자식 노드");
		return false;
	}
	// console.log(evt,dropzone,dragNode,dropNode);
	let p = dragNode.parentNode.closest('.treeList-branch');


	let menuAppScp = angular.element(document.querySelector('div[ng-app="menuApp"]')).scope()

	let dragNodeMn = angular.element(dragNode).scope().$parent.mn
	let dropNodeMn = angular.element(dropNode).scope().$parent.mn



	console.log(dragNodeMn,dropNodeMn);
	if(dropzone.classList.contains('dropzone-add-child')){ //자식으로 추가
		console.log("서브메뉴 이동");
		if(!confirm(dropNodeMn.mn_text+' 서브메뉴에 포함하겠습니까?')){
			return;
		}
		dragNodeMn.mn_parent_id = dropNodeMn.mn_id;
		let next_sort = dropzone.querySelector(":scope >ul>li:last-child")?angular.element(dropzone.querySelector(":scope >ul>li:last-child")).scope().$parent.mn.mn_sort:10;
		dragNodeMn.mn_sort = Math.round((parseInt(dropNodeMn.mn_sort,10)+next_sort)/2).toString();
		menuAppScp.$apply(function () {
					menuAppScp.form_update(dragNodeMn);
					setTimeout(function(){
						menuAppScp.$apply(function () {
							menuAppScp.form_submit();
						})
					},500)

			});
	}else{
		console.log("다음으로 이동");
		if(!confirm(dropNodeMn.mn_text+' 다음으로 이동 하시겠습니까?')){
			return;
		}

		let next_sort = dropNodeMn.mn_sort
		if(dropNode.nextElementSibling){
			next_sort = angular.element(dropNode.nextElementSibling).scope().$parent.mn.mn_sort;
		}
		next_sort = parseInt(next_sort,10);
		dragNodeMn.mn_parent_id = dropNodeMn.mn_parent_id;
		dragNodeMn.mn_sort = Math.round((parseInt(dropNodeMn.mn_sort,10)+next_sort)/2+1).toString();
		menuAppScp.$apply(function () {
					menuAppScp.form_update(dragNodeMn);
					setTimeout(function(){
						menuAppScp.$apply(function () {
							menuAppScp.form_submit();
						})
					},500)

			});
	}
	return true;
}
</script>
<style>
.mn_text{cursor: pointer; display: inline-block;}
.treeList{
	overflow-y:auto;
	max-height: 60vh;
	min-height: 300px;
}
.r-box{

	border:3px solid #ccc;
	border-radius: 10px;
	padding:5px;
}
.treeList .mn_text{
	padding: 0px 5px;
}
.treeList .mn_use_0 .mn_text{
	text-decoration: line-through;
}
.treeList .mn_hide_1 .treeList-leaf{
	background-color: #eee;
}
.treeList-leaf{display: flex;flex-wrap: wrap;justify-content: flex-end;}
.treeList-leaf-flex{
	padding:0 !important;
}
.treeList-leaf-flex > *{
	padding:2px !important;
	margin:0px !important;
}
.treeList-leaf-flex-text{
	padding:2px 5px;
}
.treeList-branch.active{}
.treeList-branch.active > .treeList-leaf{
	font-weight:bold;
	border-color: #e93;
	background-color: #fec;
}
.treeList-toggle-stem.glyphicon::before{
content: "\e118";
}
.treeList-hidden-stem .treeList-toggle-stem.glyphicon::before{
content: "\e117";
}

.dnd-dropzone.dnd-dropzone-dragenter:not(.dnd-draggable-dragging){
  filter: none;
	border-color: #f00;
}
</style>
<h4>메뉴설정</h4>
<datalist id="datalist-mn_a_target">
		<option value="" >기본</option>
		<option value="_blank" >새창</option>
		<option value="_self" >현재창</option>
		<option value="_parent" >부조창</option>
		<option value="_top" >최상위창</option>
</datalist>
<div ng-app="menuApp" class="row" ng-controller="treeCtrl as treeCtrl" ng-init="treeCtrl.init('<?=$json_url?>')">
	<script type="text/ng-template" id="field_renderer.html">
		<div class="treeList-leaf treeList-leaf-flex dnd-dropzone dropzone-move-next">
			<button ng-if="mn.child.length>0" title="toggle-child" class="btn btn-link btn-xs treeList-toggle-stem glyphicon dnd-dropzone dropzone-add-child"></button>
			<button ng-click="form_appendChild(mn)" title="add child" class="btn btn-link btn-xs glyphicon glyphicon-plus-sign dnd-dropzone dropzone-add-child"></button>
			<!-- <button ng-if="mn.child.length>0" title="toggle-child" class="btn btn-link btn-xs treeList-toggle-stem glyphicon glyphicon glyphicon-folder-close"></button> -->
			<!-- <button ng-if="mn.child.length>0" title="toggle-child" class="btn btn-link btn-xs treeList-hide-stem glyphicon glyphicon glyphicon-folder-open"></button> -->
			<div class="mn_text treeList-leaf-flex-text ellipsis"  ng-click="form_update(mn)" ng-bind="mn.mn_text"></div>
			<!-- <button ng-click="form_update(mn)" title="edit" class="btn btn-link btn-xs glyphicon glyphicon-edit"></button> -->

		</div>
		<ul class="treeList-stem" ng-if="mn.child.length>0">
			<li class="treeList-branch dnd-node dnd-draggable" draggable="true" ng-repeat="mn in mn.child" ng-class="{active: selected_obj.mode=='update' && mn.mn_id==selected_obj.mn_id || selected_obj.mode=='insert' &&mn.mn_id==selected_obj.mn_parent_id, 'mn_use_0':mn.mn_use=='0', 'mn_hide_1':mn.mn_hide=='1'}" ng-include="'field_renderer.html'"></li>
		</ul>
	</script>
	<div class="col-md-4">
		<div class="r-box">
			<div class="treeList treeList-hover treeList-theme-0 dnd-drop-animation">
				<ul class="treeList-stem">
					<li class="treeList-branch  dnd-node dnd-draggable" ng-repeat="mn in mn_tree" ng-class="{active: mn.mn_id==selected_obj.mn_id, 'mn_use_0':mn.mn_use=='0', 'mn_hide_1':mn.mn_hide=='1'}"  ng-include="'field_renderer.html'">

					</li>
				</ul>
			</div>
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
						<input type="text" class="form-control" name="mn_id" placeholder="#AUTO#" ng-model="selected_obj.mn_id"  ng-readonly="1">
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
				<label class="col-sm-2 control-label"><a ng-href="{{selected_obj.url}}" target="_blank" title="메뉴 URL 확인">URL</a></label>
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
				<label class="col-sm-2 control-label">이동 타겟</label>
				<div class="col-sm-4">
					<input type="text" maxlength="20" class="form-control" placeholder="mn_a_target" list="datalist-mn_a_target" ng-model="selected_obj.mn_a_target" >
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">정렬순서</label>
				<div class="col-sm-4">
					<!-- <select class="form-control" placeholder="mn_sort" ng-model="selected_obj.mn_sort"  required >
						<option value="" >#NONE#</option>
						<option ng-repeat="v in mn_sorts" value="{{v}}" >{{v}}</option>
					</select>
					-->
					<input type="number" string-to-number  min="-10" max="999999"  class="form-control" placeholder="mn_sort" ng-model="selected_obj.mn_sort"  required >
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
			<div class="form-group">
				<label class="col-sm-2 control-label"><a href="<?=dirname(dirname($conf['base_url']))?>/sitemap/google" target="_blank">Sitemap</a>에 표시</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_hide_sitemap" ng-model="selected_obj.mn_hide_sitemap" value="0" ng-checked="selected_obj.mn_hide_sitemap=='0'" ng-disabled="selected_obj.mn_lock=='1'">사용</label>
						<label ><input type="radio" placeholder="mn_hide_sitemap" ng-model="selected_obj.mn_hide_sitemap" value="1" ng-checked="selected_obj.mn_hide_sitemap=='1'" ng-disabled="selected_obj.mn_lock=='1'">금지</label>
					</div>
				</div>
				<label class="col-sm-2 control-label">배너사용</label>
				<div class="col-sm-4">
					<div class="btn-group form-control-static" >
						<label ><input type="radio" placeholder="mn_use_banners" ng-model="selected_obj.mn_use_banners" value="1" ng-checked="selected_obj.mn_use_banners=='1'" ng-disabled="selected_obj.mn_lock=='1'">사용</label>
						<label ><input type="radio" placeholder="mn_use_banners" ng-model="selected_obj.mn_use_banners" value="0" ng-checked="selected_obj.mn_use_banners=='0'" ng-disabled="selected_obj.mn_lock=='1'">금지</label>
					</div>
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="col-sm-2 control-label">모듈</label>
				<div class="col-sm-4">
					<!-- <select class="form-control" name="mn_module"  placeholder="mn_module" ng-model="selected_obj.mn_module"  ng-disabled="selected_obj.mn_lock=='1'">
						<option  value="" >#NONE#</option>
						<option  ng-repeat="val in module_lists" value="{{val}}" >{{val}}</option>
					</select> -->
					<input class="form-control" name="mn_module" list="datalist-mn_module"  placeholder="mn_module" ng-model="selected_obj.mn_module"  ng-disabled="selected_obj.mn_lock=='1'">
					<datalist id="datalist-mn_module">
						<option  ng-repeat="val in module_lists" value="{{val}}" >{{val}}</option>
					</datalist>
				</div>

				<label class="col-sm-2 control-label">모듈인자1</label>
				<div class="col-sm-4">
					<input type="text" maxlength="100" class="form-control" placeholder="mn_arg1" ng-model="selected_obj.mn_arg1"
					ng-hide="['page','bbs','mh/bbs','mh/page','mh_admin/page','mh_service/page'].includes(selected_obj.mn_module)"
					ng-disabled="selected_obj.mn_lock=='1'"
					>
					<select class="form-control" placeholder="mn_arg1"
					ng-model="selected_obj.mn_arg1"
					ng-disabled="!['bbs','mh/bbs'].includes(selected_obj.mn_module) || selected_obj.mn_lock=='1'"
					ng-hide="!['bbs','mh/bbs'].includes(selected_obj.mn_module)"  >
						<option value="" >#게시판 아이디#</option>
						<option ng-repeat="(k, v) in bbs_lists" value="{{k}}" >[{{k}}] {{v}}</option>
					</select>
					<select class="form-control" placeholder="mn_arg1"
					ng-model="selected_obj.mn_arg1"
					ng-disabled="!['page','mh/page','mh_admin/page','mh_service/page'].includes(selected_obj.mn_module) || selected_obj.mn_lock=='1'"
					ng-hide="!['page','mh/page','mh_admin/page','mh_service/page'].includes(selected_obj.mn_module)"  >
						<option value="" >#페이지 파일#</option>
						<option ng-repeat="(k, v) in page_lists" value="{{v}}" >{{v}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">모듈인자2</label>
				<div class="col-sm-4">
					<input type="text" maxlength="100" class="form-control" placeholder="mn_arg2" ng-model="selected_obj.mn_arg2" ng-disabled="selected_obj.mn_lock=='1'">
				</div>
				<label class="col-sm-2 control-label">모듈인자3</label>
				<div class="col-sm-4">
					<input type="text" maxlength="100" class="form-control" placeholder="mn_arg3" ng-model="selected_obj.mn_arg3" ng-disabled="selected_obj.mn_lock=='1'">
				</div>
			</div>
			<hr>
			<div class="form-group">
				<label class="col-sm-2 control-label">제한레벨</label>
				<div class="col-sm-4">
					<input type="text" maxlength="100" class="form-control" placeholder="mn_m_level" ng-model="selected_obj.mn_m_level" ng-disabled="selected_obj.mn_lock=='1'">
				</div>
				<label class="col-sm-2 control-label">레이아웃</label>
				<div class="col-sm-4">
					<select class="form-control" placeholder="mn_arg1"
					ng-model="selected_obj.mn_layout"
					ng-disabled="selected_obj.mn_lock=='1'"
					>
						<option value="" >#레이아웃 파일#</option>
						<option ng-repeat="(k, v) in layout_lists" value="{{v}}" >{{v}}</option>
					</select>

					<!-- <input type="text" maxlength="100" class="form-control" placeholder="mn_layout" ng-model="selected_obj.mn_layout" > -->
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-2 control-label">접근허용아이디</label>
				<div class="col-sm-10">
					<input type="text" maxlength="100" class="form-control" placeholder="mn_allowed_m_id" ng-model="selected_obj.mn_allowed_m_id" ng-disabled="selected_obj.mn_lock=='1'">
					<div>
						<small class="text-danger">( ,표로 구분 )( 빈값이면 모든 사용자 접근 가능 )</small>
					</div>
				</div>
			</div>
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
var w_scope = null;
var menuApp = angular.module('menuApp', []);
menuApp.controller('treeCtrl', ['$scope','$http','$httpParamSerializer', function ($scope,$http,$httpParamSerializer) {
	console.log($scope);
	w_scope = $scope;
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
		if($scope.selected_obj.mn_sort.length>0){
			$scope.selected_obj.mn_sort = parseInt($scope.selected_obj.mn_sort);
		}else{
			$scope.selected_obj.mn_sort = 0;
		}
		// console.log($scope.selected_obj,$scope.selected_obj.mode);
	}
	$scope.form_appendChild=function(menu){
		// console.log($scope.mn_rows["mn-"+menu.mn_id]);
		if(!$scope.mn_rows["mn-"+menu.mn_id]){
			return false;
		}
		$scope.selected_obj = {}
		$scope.selected_obj.mn_parent_id  = menu.mn_id;
		$scope.selected_obj.mn_m_level = def_mn_m_level;// 기본 레벨

		var t = $scope.mn_rows["mn-"+menu.mn_id].child
		var mx = 0;
		for(var i=0,m=t.length;i<m;i++){
			var tt = Math.floor(parseInt(t[i].mn_sort)/10)*10
			mx = Math.max(mx,tt);
		}
		mx = Math.min(mx+10,99);
		$scope.selected_obj.mn_sort = mx;
		//$scope.selected_obj.mn_sort =
		$scope.selected_obj.mode='insert';
	}
	//트리모양으로 만든다.
	$scope.set_mn_rows =function(mn_rows){
		$scope.mn_rows = {}
		//$scope.mn_rows = mn_rows;
		$scope.mn_tree = [];
		for(var i=0,m=mn_rows.length;i<m;i++){
			$scope.mn_rows['mn-'+mn_rows[i]['mn_id']] = mn_rows[i];
		}
		console.log($scope.mn_rows);

		for(var x in $scope.mn_rows){
			var mn_row = $scope.mn_rows[x];
			//console.log(x,mn_row['mn_parent_id'],mn_row['mn_text']);
			if(mn_row['mn_parent_id'] == mn_row['mn_id']){
				$scope.mn_tree.push(mn_row);
				mn_row['mn_parent_id'].child = [];
			}else if($scope.mn_rows['mn-'+mn_row['mn_parent_id']]){
				if(!$scope.mn_rows['mn-'+mn_row['mn_parent_id']].child){
					$scope.mn_rows['mn-'+mn_row['mn_parent_id']].child = [];
				}
				$scope.mn_rows['mn-'+mn_row['mn_parent_id']].child.push(mn_row);

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
		if(data.layout_lists){
			$scope.layout_lists = data.layout_lists;
		}
		if(data.mn_id){
			if($scope.mn_rows["mn-"+data.mn_id]){
				$scope.form_update($scope.mn_rows["mn-"+data.mn_id]);
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
