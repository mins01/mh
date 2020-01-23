var mh_banners = {
  "data":null,
  "init":function(banners_data){
    this.data = banners_data;
    this.arrangeHide24();
  },
  "attach_banners":function(){
    for(var i=0,m=this.data.banners.length;i<m;i++){
      var bn = this.data.banners[i];
      this.attach_banner(bn,i)
    }
  },
  "attach_banner":function(bn,idx){
    if(this.isHide24(bn.bn_idx)){
      console.log('fail_attach_banner',idx,'hided');
      return null;
    }
    var node = document.querySelector(bn['bn_base_node']);
    if(!node){
      console.log('fail_attach_banner',idx,bn['bn_base_node']);
      return null;
    }
    var div = document.createElement('div');
    div.id ='mh_banner_'+bn.bn_idx;
    if(bn.bn_class_name.trim().length>0) div.className = bn.bn_class_name;
    div.classList.add('mh-banner');
    div.classList.add('mh-banner-'+bn.bn_postion);
    div.classList.add('mh-banner-content-type-'+bn.bn_content_type);


    div.style.left=bn.bn_left;
    div.style.top=bn.bn_top;
    div.style.width=bn.bn_width;
    div.style.height=bn.bn_height;
    div.style.zIndex=bn.bn_z_index;
    if(bn.bn_use_header=='1'){
      div.appendChild(this.create_header(bn,div.id));
    }
    var content = document.createElement('div');
    content.classList.add('mh-banner-content');
    content.innerHTML = bn['bn_content'];
    div.appendChild(content)
    if(bn.bn_use_footer=='1'){
      div.appendChild(this.create_footer(bn,div.id));
    }
    node.classList.add('mh-banner-bannered');
    node.classList.add('mh-banner-bannered-'+bn.bn_postion);
    node.appendChild(div);
    console.log('success_attach_banner',idx);
    return node;
  },
  "create_footer":function(bn,id){
    var div = document.createElement('div');
    div.classList.add('mh-banner-footer');
    div.innerHTML = '<button class="mh-banner-close-24">하룻동안 닫기</button> <button class="mh-banner-close">닫기</button>';
    div.querySelector('.mh-banner-close').addEventListener('click',function(bn_idx){return function(){
      mh_banners.close(bn_idx);
    }}(bn.bn_idx))
    div.querySelector('.mh-banner-close-24').addEventListener('click',function(bn_idx){return function(){
      mh_banners.hide24(bn_idx);
      mh_banners.close(bn_idx);
    }}(bn.bn_idx))
    return div;
  },
  "create_header":function(bn,id){
    var div = document.createElement('div');
    div.classList.add('mh-banner-header');
    div.innerText = bn.bn_title
    return div;
  },
  "attach_window_load":function(){
    mh_banners.init(banners_data);
    window.addEventListener('load',function(){
      mh_banners.attach_banners();
    })
  },
  "close":function(bn_idx){
    var id ='mh_banner_'+bn_idx ;
    document.querySelector('#'+id).classList.add('off');
  },
  "hide24":function(bn_idx){
    if(!window.localStorage){return false;}
    if(!window.localStorage){return;}
    var k = 'bn_hide24_'+bn_idx;
    var v = (new Date()).getTime() + 60*60*24*1000
    window.localStorage.setItem(k,v);
  },
  "isHide24":function(bn_idx){
    if(!window.localStorage){return false;}
    var k = 'bn_hide24_'+bn_idx;
    var v = window.localStorage.getItem(k);
    if(v==null){
      return false;
    }
    v = parseInt(v);
    var t = (new Date()).getTime();
    if(v >= t){
      return true;
    }
    window.localStorage.removeItem(k);
    console.log("localStorage.removeItem",k,v);
    return false;
  },
  "arrangeHide24":function(){
    var t = (new Date()).getTime();
    if(!window.localStorage){return false;}
    var ks = []
    for(var i=0,m=window.localStorage.length;i<m;i++){
      ks.push(window.localStorage.key(i));
    }
    for(var i=0,m=ks.length;i<m;i++){
      var k = ks[i]
      if(k.match(/bn_hide24_\d+/) == null ){ continue; }
      var v = parseInt(window.localStorage.getItem(k));
      if(v >= t){ continue; }
      window.localStorage.removeItem(k);
      console.log("localStorage.removeItem",k,v);
    }
  }
}
