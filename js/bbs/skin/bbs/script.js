function check_form_bbs(f){
	var ta = f.b_title;
	if(ta && ta.value.length<2){
		ta.focus();
		alert('�������� 2���� �̻� �Է����ּ���.');
		return false;
	}
	var ta = f.b_text;
	if(ta && ta.value.length<2){
		ta.focus();
		alert('�۳����� 2���� �̻�  �Է����ּ���.');
		return false;
	}
	return true;
}

