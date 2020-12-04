function rel_keyword_init(){
	var v = new Vue({
		el: '#relKeywordApp',
		data: {
			"rs_keywords":[],
			"filter_keyword":"",
			"selected_keyword":"",
			"selected_keyword_length":0
		},
		methods:{
			"add_selected_keyword":function(keyword){
				var onlyUnique = function(value, index, self) {
					return self.indexOf(value) === index;
				}
				let t = this.selected_keyword.replace(/(\s|\t|\n|\r)/g,'')
				let ts = t==''?[]:t.split(',');
				ts.push(keyword);
				ts = ts.filter(onlyUnique);
				this.selected_keyword = ts.join(",");
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
			}
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
