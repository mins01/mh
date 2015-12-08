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
