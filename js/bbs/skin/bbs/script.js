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
	
	var bm_use_category = $(f).attr('data-data-bm_use_category');
	var ta = f.b_category;
	if(bm_use_category=='2' && ta.value.length==0){
		ta.focus();
		alert('ī�װ��� �ʼ��� �����ϼž��մϴ�.');
		return false;
	}
	
	return true;
}

