function ng_bbs_comment(ngApp,ngController){
	
	var bbsComment = angular.module(ngApp, ['ngSanitize']);
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

	bbsComment.controller(ngController, ['$scope', '$http','$httpParamSerializer','$filter', function ($scope,$http,$httpParamSerializer,$filter) {
		$scope.form = {"bc_comment":"","mode":"write","bc_idx":"","bc_score":"0"};
		
		this.init = function( comment_url) {
			$scope.comment_url = comment_url;
			$scope.initData();
		}

		$scope.comment_url = '';//통신URL
		$scope.bc_rows = [{"bc_idx":"-","b_idx":"-","m_idx":"-","bc_name":"","bc_comment":"-","bc_insert_date":"",}]; //Model, 커멘트 배열
		
		$scope.msg = '';
		$scope.permission = {list: true, write: false, edit: true, answer: true, "delete": true, };
		$scope.x = function(){
			return 'xxx';
		}
		
		
		//통신 결과처리:성공
		$scope.callback_success = function(data, status, headers, config){
			$scope.bc_rows = data.bc_rows;
			$scope.m_row = data.m_row;
			if(data.msg != undefined){
				$scope.msg = data.msg;
			}
			if(data.permission != undefined){
				$scope.permission = data.permission;
			}
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
			console.log($httpParamSerializer(d))
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
			$scope.set_mode('write','write'); //글 쓰기 폼을 옮겨둬야한다.
			
			$scope.call_ajax(d);
		}
		$scope.set_mode=function(mode,$index,nofocus){
			this.form.mode = mode;
			if(mode!='write' && this.bc_rows[$index]){
				var bc_idx = this.bc_rows[$index].bc_idx;
				var id = '#bc_idx_'+bc_idx;
				if(mode =='answer'){
					this.form.bc_comment = '';
				}else{
					this.form.bc_comment = this.bc_rows[$index].bc_comment;
					this.form.bc_score = this.bc_rows[$index].bc_score;
				}
			}else{
				var bc_idx = '';
				var id = '#bc_idx_write';
				this.form.bc_comment = '';
				this.form.bc_score = "0";
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
	return bbsComment;
}