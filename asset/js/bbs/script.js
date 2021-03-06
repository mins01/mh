﻿function check_form_bbs(f){
	var ta = f.b_title;
	if(ta && ta.value.length<2){
		ta.focus();
		alert('글제목을 2글자 이상 입력해주세요.');
		return false;
	}
	var ta = f.b_text;
	if(ta && ta.value.length<2){
		ta.focus();
		alert('글내용을 2글자 이상  입력해주세요.');
		return false;
	}

	var bm_use_category = $(f).attr('data-bm_use_category');
	var ta = f.b_category;
	if(bm_use_category=='2' && ta.value.length==0){
		ta.focus();
		alert('카테고리를 필수로 선택하셔야합니다.');
		return false;
	}

	return true;
}


function set_represent(f,bf_idx){
	if(!confirm('대표이미지로 설정하시겠습니까?')){return false;}
	if(!f.bf_idx){
		alert('사용할 수 없는 상태입니다.');
		return false;
	}
	f.process.value= "set_represent";
	f.bf_idx.disabled = false;
	f.bf_idx.value = bf_idx;
	f.submit();
}
//--

/**
* 위지윅 관련
*/

//--- 위지웍 개체는 미리 생성
var wysiwygs = [];
var wysiwyg = null;
var mb_wysiwyg_url = "/web_work/mb_wysiwyg_dom";
function createWysiwygObj(target){
	try{
		// mb_wysiwyg_head_css = '<!-- 합쳐지고 최소화된 최신 CSS --> \
		// 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css"> \
		// 	<!-- 부가적인 테마 --> \
		// 	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap-theme.min.css"> \
		// 	<link rel="stylesheet" href="/mh/css/bootstrap/bootstrap-select.min.css"> \
		// 	<link rel="stylesheet" href="/mh/css/mh.css"> \
		// 	<!-- 게시판 추가 head_contents --> \
		// 	<link href="/mh/css/bbs/skin/bbs_skin_default.css" \ rel="stylesheet"> \
		// 	<!-- //게시판 추가 head_contents --> \
		// 	<style>body{padding:0 !important}</style> \
		// ';
		if($('.mb_wysiwyg_head_css').length>0){
			mb_wysiwyg_head_css = $('.mb_wysiwyg_head_css').clone().wrapAll("<div/>").parent().html()
		}
		var wysiwyg = new mb_wysiwyg(target,'100%',300,'','mins가 만든 위지웍 에디터입니다.');
		wysiwygs.push(wysiwyg);
		wysiwyg.path = mb_wysiwyg_url;
		//wysiwyg.icon_pack="wr_24px";
		wysiwyg.icon_pack="black_16px";
		wysiwyg.c_table_type = true;
		//wysiwyg.mk_wysiwyg(); 를 동작시키면 언제든지 쓸 수 있다.
		//if(b_html!=='t'){ //텍스트 모드가 아닐 경우 위지윅을 생성한다.
			//wysiwyg.mk_wysiwyg();
			showWysiwyg(wysiwyg,true);
		//}
	}catch(e){
		alert(e);
	}
	return wysiwyg;
}
//--- 위지윅 보이기 감추기
function showWysiwyg(wysiwyg,bool){
	var r = null;
	if(wysiwyg.stat != bool){
		if(bool==1){
			r = wysiwyg.mk_wysiwyg('',[],true);
		}else{
			r = wysiwyg.remove();
		}
		if(r){
			isWysiwyg = bool;
		}
	}
}
function submitWysiwyg(){
	for(var i=0,m=wysiwygs.length;i<m;i++){
		wysiwygs[i].submit();
	}
}

function load_tag_lists(url){
	var post_data = null;
	$.ajax({
		url: url,
		type: 'GET', //GET
		dataType: 'json', //xml, json, script, jsonp, or html
		data: post_data,
	})
	.done(function(rData) { //통신 성공 시 호출
		$tag_lists = $('datalist#tag_lists');
		$tag_lists.html('');
		for(var i=0,m=rData.length;i<m;i++){
			var v = rData[i];
			$tag_lists.append('<option value="'+v+'"></option>')
		}
		// console.log(rData);
		console.log("tag_lists success");

	})
	.fail(function() { //통신 실패 시 호출
		console.log("tag_lists error");
	})
	.always(function() { //성공/실패 후 호출.
		console.log("tag_lists complete");
	});
}
