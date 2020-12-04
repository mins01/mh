
var lib_cat_rows = {
	"generate_cat_tree":function(cat_rows){ //cat_rows 를 cat_tree 형태로 만든다.
		if(!cat_rows){
			console.log("not found cat_rows");
			return false;
		}
		let cat_tree = []
		for(let k in cat_rows){
			let r = cat_rows[k];
			if(r['nsc_depth']==1){
				cat_tree.push(r);
			}
			if(r['nsc_pid']){
				if(cat_rows[r['nsc_pid']]['child']===undefined){
					cat_rows[r['nsc_pid']]['child'] = []
				}
				cat_rows[r['nsc_pid']]['child'].push(r);

			}
		}
		return cat_tree;
	},
	"generate_cat_names":function(cat_rows){
		let cat_names = {};
		let r,rr;
		for(let k in cat_rows){
			r = cat_rows[k];
			let ts = [];
			ts.unshift(r.nsc_name);
			if(r.nsc_pid){
				r = cat_rows[r.nsc_pid];
				ts.unshift(r.nsc_name);
			}
			if(r.nsc_pid){
				r = cat_rows[r.nsc_pid];
				ts.unshift(r.nsc_name);
			}
			if(r.nsc_pid){
				r = cat_rows[r.nsc_pid];
				ts.unshift(r.nsc_name);
			}
			cat_names[ts.join('-')] = cat_rows[k];
		};
		return cat_names;
	}
}
