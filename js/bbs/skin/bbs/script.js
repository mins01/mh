function check_form_bbs(f){
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
	
	var bm_use_category = $(f).attr('data-data-bm_use_category');
	var ta = f.b_category;
	if(bm_use_category=='2' && ta.value.length==0){
		ta.focus();
		alert('카테고리를 필수로 선택하셔야합니다.');
		return false;
	}
	
	return true;
}

