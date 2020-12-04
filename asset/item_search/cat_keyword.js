function cat_keyword_init(){
	var v = new Vue({
		el: '#catKeywordApp',
		data: {
			"cat_tree":[],
			"cat_rows":{},
			"cat1":"",
			"cat2":"",
			"cat3":"",
			"cat4":"",
			"cid":"",
			"cname":"",
			"ajax_cat_keyword_url":"",
			"rs_keywords":[],
			"filter_keyword":"",
			"selected_keyword":"",
			"selected_keyword_length":0
		},
		methods:{
			'change_cat_tree':function(event){
				let option = event.target.querySelector('option:checked');;
				let value = option.value;
				let data_value = option.dataset.value;
				// console.log(event);
			},
			"set_cid":function(cid){
				let cats = []
				let r = null;
				if(r = cat_rows[cid]){
					// console.log(r);
					cats.unshift(r);
				}
				// console.log(cat_rows[r.nsc_pid]);
				if(cat_rows[r.nsc_pid]){
					r = cat_rows[r.nsc_pid];
					cats.unshift(r);
				}
				if(cat_rows[r.nsc_pid]){
					r = cat_rows[r.nsc_pid];
					cats.unshift(r);
				}
				if(cat_rows[r.nsc_pid]){
					r = cat_rows[r.nsc_pid];
					cats.unshift(r);
				}
				// console.log(cats);
				// this.cat1 = document.querySelector('option[data-value="'+cats[0].nsc_id+'"]').value;
				if(cats[0]){ this.cat1 = cats[0].nsc_id; }
				if(cats[1]){ this.cat2 = cats[1].nsc_id; }
				if(cats[2]){ this.cat3 = cats[2].nsc_id; }
				if(cats[3]){ this.cat4 = cats[3].nsc_id; }
				this.delay_sync_cid();
			},
			"delay_sync_cid":function(){
				setTimeout(function(thisC){
					return function(){
						thisC.sync_cid();
					}
				}(this),100)
			},
			'sync_cid':function(){
				let cr;
				if(this.cat4!==''){
					cr = this.cat_rows[this.cat4];
					this.cid = cr.nsc_id;
					// this.cname = cr.nsc_name;
				}else if(this.cat3!==''){
					cr = this.cat_rows[this.cat3];
					this.cid = cr.nsc_id;
					// this.cname = cr.nsc_name;
				}else if(this.cat2!==''){
					cr = this.cat_rows[this.cat2];
					this.cid = cr.nsc_id;
					// this.cname = cr.nsc_name;
				}else if(this.cat1!==''){
					cr = this.cat_rows[this.cat1];
					this.cid = cr.nsc_id;
					// this.cname = cr.nsc_name;
				}else {
					this.cid = '';
					// this.cname = '';
				}
				this.sync_cname();
			},
			"sync_cname":function(){
				this.cname = '';

				let ts = [];
				document.querySelectorAll('.select_cat:not(:disabled) option:checked').forEach((item, i) => {
					if(item.value==''){return;}
					ts.push(item.text)
				});
				this.cname = ts.join(" > ");
			},
			'add_selected_keyword':function(keyword){
				var onlyUnique = function(value, index, self) {
					return self.indexOf(value) === index;
				}
				let t = this.selected_keyword.replace(/(\s|\t|\n|\r)/g,'')
				let ts = t==''?[]:t.split(',');
				ts.push(keyword);
				ts = ts.filter(onlyUnique);
				this.selected_keyword = ts.join(",");
			},
			'ajax_cat_keyword':function(cid){
				if(cid==''){alert('카테고리를 설정해주세요.');return false;}
				let qstrs = {cid:cid};
				let url = ajax_cat_keyword_url+'?'+$.param(qstrs);
				let thisC = this;
				$.ajax({
					url: url,
					type: 'GET', //GET
					dataType: 'json', //xml, json, script, jsonp, or html
					// data: post_data,
				})
				.done(function(rData) { //통신 성공 시 호출
					console.log("success");
					thisC.rs_keywords = rData;
					thisC.sort_rs_keywords('kr_competitive_strength',1);

					let state = {"cid":cid};
					history.replaceState(state, this.cname,'?cid='+cid);
				})
				.fail(function() { //통신 실패 시 호출
					console.log("error");
				})
				.always(function() { //성공/실패 후 호출.
					console.log("complete");
				});
			},
			"btn_sort":function(event){
				let target = event.target;
				let sort_key = target.dataset.sort_key;
				let order_type = parseInt(target.getAttribute('data-order_type'),10);
				order_type = (order_type+1)%3;
				if(order_type!==0){
					this.sort_rs_keywords(sort_key,order_type)
				}else{
					this.sort_rs_keywords('kr_competitive_strength',1);
				}
			},
			"sort_rs_keywords":function(sort_key,order_type){
				document.querySelectorAll('.btn_sort').forEach((item, i) => {
					item.setAttribute('data-order_type',0);
				});
				let sort_fn = null;
				if(order_type===1){
					sort_fn = function(sort_key){
						return function(a,b){
							return (a[sort_key]===null?0:a[sort_key])-(b[sort_key]===null?0:b[sort_key])
						}
					}(sort_key)
				}else if(order_type===2){
					sort_fn = function(sort_key){
					 return function(a,b){
						 return (b[sort_key]===null?0:b[sort_key])-(a[sort_key]===null?0:a[sort_key])
					 }
				 }(sort_key)
				}
				this.rs_keywords.sort(sort_fn);
				document.querySelector('#btn_'+sort_key).setAttribute('data-order_type',order_type);
			},
			select_cat1:function(event){
				this.cat2='';
				this.select_cat2(event);
			},
			select_cat2:function(event){
				this.cat3='';
				this.select_cat3(event);
			},
			select_cat3:function(event){
				this.cat4='';
				this.select_cat4(event);
			},
			select_cat4:function(event){
				this.delay_sync_cid();
			},
		},
		watch:{
			"selected_keyword":function(nv,ov){
				if(this.selected_keyword==0){
					this.selected_keyword_length = 0;
				}
				this.selected_keyword_length = this.selected_keyword.split(',').length
			}
		}

	});
	return v;
}
