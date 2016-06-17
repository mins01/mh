var m_autodatalist = {
		maxSave:10,
		maxLength:10,
		initEventForm:function(form){
			var $inputs = $(form).find(".m_autodatalist[data-m_autodatalist]");
			$inputs.each(
					function(idx,el){
						m_autodatalist.initInput(el)
					});
			$(form).on("submit",function(){
				$inputs.each(
					function(idx,el){
						m_autodatalist.saveValue(el)
					})
				
			});
			
		},
		initInput:function(input){
			var id = $(input).attr("data-m_autodatalist");
			$(input).attr("list",id+'-list');
			this.syncValue(id);
		},
		getUnique:function(arr){
			 var u = {}, a = [];
			 for(var i = 0, l = arr.length; i < l; ++i){
					if(u.hasOwnProperty(arr[i])) {
						 continue;
					}
					a.push(arr[i]);
					u[arr[i]] = 1;
			 }
			 return a;
		},
		saveValue:function(input){
			if(input.value.length==0){return;}
			var id = $(input).attr("data-m_autodatalist");
			var val = localStorage.getItem(id);
			if(!val){
				var v_arr = [];
			}else{
				var v_arr = JSON.parse(val);
			}
			var input_val = input.value.substr(0,this.maxLength);
			if(v_arr.length > 0 && v_arr[0] == input_val){
				//console.log("중복값");
				return ;
			}
			v_arr.unshift(input_val);
			v_arr = this.getUnique(v_arr)
			v_arr = v_arr.splice(0,this.maxSave);
			// console.log(v_arr);
			localStorage.setItem(id,JSON.stringify(v_arr));
			this.syncValue(id);

		},
		clearValue:function(id){
			localStorage.removeItem(id);
			this.syncValue(id);
		},
		syncValue:function(id){
			var val = localStorage.getItem(id);
			if(!val){
				var v_arr = [];
			}else{
				var v_arr = JSON.parse(val);
			}
			var datalist_id =id+'-list';

			if($("#"+datalist_id).length==0){
				var datalist_node = document.createElement('datalist');
				datalist_node.id=datalist_id;
				$(document.body).append(datalist_node);
			}else{
				var datalist_node = $("#"+datalist_id)[0];
			}
			$(datalist_node).html("");
			for(var i=0,m=v_arr.length;i<m;i++){

				var option = document.createElement('option');
				option.value=v_arr[i];
				$(datalist_node).append(option);
			}

			


			

		},

	}