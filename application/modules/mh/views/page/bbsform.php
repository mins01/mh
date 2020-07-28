<form enctype="multipart/form-data" action="/mh/bbs/test/write" method="post">
	process: <input type="text" name="process" value="write"></br>
	ret_url: <input type="text" name="ret_url" value="<?=current_url()?>"><br>
	b_idx: <input type="text" name="b_idx" value=""><br>
	b_id: <input type="text" name="b_id" value=""><br>
	b_gidx: <input type="text" name="b_gidx" value=""><br>
	b_gpos: <input type="text" name="b_gpos" value=""><br>
	b_pidx: <input type="text" name="b_pidx" value=""><br>

	<!-- m_idx: <input type="text" name="m_idx" value=""><br> -->
	b_name: <input type="text" name="b_name" value="b_name"><br>
	b_pass: <input type="text" name="b_pass" value="b_pass"><br>

	b_notice: <input type="text" name="b_notice" value=""><br>
	b_secret: <input type="text" name="b_secret" value=""><br>
	b_html: <input type="text" name="b_html" value=""><br>
	b_link: <input type="text" name="b_link" value=""><br>
	b_category: <input type="text" name="b_category" value=""><br>

	b_title: <input type="text" name="b_title" value="b_title"><br>
	b_text: <input type="text" name="b_text" value="b_text"><br>

	b_date_st: <input type="text" name="b_date_st" value=""><br>
	b_date_ed: <input type="text" name="b_date_ed" value=""><br>

	b_etc_0: <input type="text" name="b_etc_0" value=""><br>
	b_etc_1: <input type="text" name="b_etc_1" value=""><br>
	b_etc_2: <input type="text" name="b_etc_2" value=""><br>
	b_etc_3: <input type="text" name="b_etc_3" value=""><br>
	b_etc_4: <input type="text" name="b_etc_4" value=""><br>

	b_num_0: <input type="text" name="b_num_0" value=""><br>
	b_num_1: <input type="text" name="b_num_1" value=""><br>
	b_num_2: <input type="text" name="b_num_2" value=""><br>
	b_num_3: <input type="text" name="b_num_3" value=""><br>
	b_num_4: <input type="text" name="b_num_4" value=""><br>
	첨부파일1 : <input type="file" name="upf[]" multiple="">
	첨부파일2 : <input type="file" name="upf[]" multiple="">
	첨부파일3 : <input type="file" name="upf[]" multiple="">
	첨부파일4 : <input type="file" name="upf[]" multiple="">

	<button type="submit">등록</button>
</form>
