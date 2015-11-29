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
	return true;
}

//--
