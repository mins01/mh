var parse_keyword = {
	"keywords":[],
	"split_keyword":function(keyword){
		const r = keyword.split(/[\s,]+/);
		return Array.from(new Set(r));
	},
	// 중요 중복 단어 추출
	"pickup_key_point":function(keywords){
		let kps = {};
		let kp,k;
		for(let i=0,m=keywords.length;i<m;i++){
			k = keywords[i];
			kp = k.substr(0,1); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(0,2); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(0,3); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(0,4); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(-1,1); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(-2,2); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(-3,3); if(kps[kp]=== undefined){ kps[kp] = 0;	}
			kp = k.substr(-4,4); if(kps[kp]=== undefined){ kps[kp] = 0;	}
		}
		let karr = Object.keys(kps);
		delete kps[''];
		//갯수 세기
		for(let i=0,m=keywords.length;i<m;i++){
			for(k in kps){
				let r = keywords[i].match(new RegExp(k),'i')
				if(r !== null){
					kps[k]++;
				}
			}
		}
		//--- 배열로
		let rarr = [];
		for(k in kps){
			if(kps[k]<2){continue;}// 2번 이상 나온 것만
			rarr.push([k,kps[k]])
		}
		rarr.sort(function(a,b){
			return b[0].length - a[0].length
		})
		// console.log(rarr.toString());
		//-- 단어 그룹화
		let tkps = {}
		let outkps = {}
		for(let i=0;i<rarr.length;i++){
			for(let i2=0;i2<rarr.length;i2++){
				if(rarr[i][0].length > rarr[i2][0].length && rarr[i][0].indexOf[rarr[i2][0]] !== -1){
					if(tkps[rarr[i][0]]==undefined){ tkps[rarr[i][0]] = 0;	} tkps[rarr[i][0]]++;
					if(rarr[i2][0].length==1){ if(outkps[rarr[i2][0]]==undefined){ outkps[rarr[i2][0]] = 0;	} outkps[rarr[i2][0]]++; }
					// rarr.splice(i2, 1);
				}
			}
		}
		// console.log(outkps);
		for(let i=0;i<rarr.length;i++){
			for(let i2=0;i2<rarr.length;i2++){
				if(outkps[rarr[i2][0]]){
					rarr.splice(i2, 1);
				}
			}
		}
		rarr.sort(function(a,b){
			if(a[1]==b[1]){
				return b[0].length - a[0].length
			}else{
				return b[1] - a[1]
			}
		})

		// console.log(rarr);
		return rarr;
	},
	"recommend_names":function(keywords,kps){
		let reco_names = [];
		let keyword = keywords.join(' ');
		reco_names.push(['원본', keyword]);
		kps.forEach((k, i) => {
			let keyword2 = keyword.replace(new RegExp(k[0],'ig'),'');
			reco_names.push([k[0],keyword2+' '+k[0]]) //뒤에 붙이기
			reco_names.push([k[0],k[0]+' '+keyword2]) //앞에 붙이기
		});

		return reco_names;
	}
}

function init_parseKeywordApp(){
	var v = new Vue({
		el: '#parseKeywordApp',
		data: {
			"keyword":"",
			"kps":[],
			"reco_names":[]
		},
		methods:{
			"parseKeyword":function(){
				let keywords = parse_keyword.split_keyword(this.keyword);
				this.keyword =  keywords.join(',');
				let kps = parse_keyword.pickup_key_point(keywords);
				if(kps){
					kps = kps.slice(0,10)
				}
				this.kps = kps;
				let reco_names = parse_keyword.recommend_names(keywords, kps);
				this.reco_names = reco_names;
			},
			"formSubmit":function(evt){
				this.parseKeyword();
			}
		}
	});
	return v;
}
