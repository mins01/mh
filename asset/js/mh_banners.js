var mh_banners = {
  "data":null,
  "init":function(banners_data){
    this.data = banners_data;
  },
  "attach_banners":function(){
    for(var i=0,m=this.data.banners.length;i<m;i++){
      var bn = this.data.banners[i];
      this.attach_banner(bn,i)
    }
  },
  "attach_banner":function(bn,idx){
    var node = document.querySelector(bn['bn_base_node']);
    if(!node){
      console.log('fail_attach_banner',idx,bn['bn_base_node']);
      return null;
    }
    var div = document.createElement('div');
    div.id ='mh_banner_'+bn.bn_idx;
    div.classList.add('mh-banner');
    div.classList.add('mh-banner-'+bn.bn_postion);
    div.classList.add(bn.bn_class_name);

    div.style.left=bn.bn_left;
    div.style.top=bn.bn_top;
    div.style.width=bn.bn_width;
    div.style.height=bn.bn_height;
    div.style.zIndex=bn.bn_z_index;
    div.innerHTML = bn['bn_content'];
    div.appendChild(this.create_footer(div.id));
    node.classList.add('mh-banner-bannered');
    node.classList.add('mh-banner-bannered-'+bn.bn_postion);
    node.appendChild(div);
    console.log('success_attach_banner',idx);
    return node;
  },
  "create_footer":function(id){
    var div = document.createElement('div');
    div.classList.add('mh-banner-footer');
    div.innerHTML = '<button class="btn-close btn-sm btn btn-warning">닫기</button>';
    div.querySelector('.btn-close').addEventListener('click',function(){
      document.querySelector('#'+id).classList.add('off');
    })
    return div;

  },
  "attach_window_load":function(){
    mh_banners.init(banners_data);
    window.addEventListener('load',function(){
      mh_banners.attach_banners();
    })
  }
}

mh_banners.attach_window_load();
