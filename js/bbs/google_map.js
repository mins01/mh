var google_map ={
	map:null,
	geocoder:null,
	input_lat:null,
	input_lng:null,
	input_zoom:null,
	input_address:null,
	
	map_canvas:null,
	pos_maker:null,
	init:function(){
		this.geocoder = new google.maps.Geocoder();
		var f = document.form_bbs
		this.input_lat = document.getElementById('google_map_lat');
		this.input_lng = document.getElementById('google_map_lng');
		this.input_zoom = document.getElementById('google_map_zoom');
		this.input_address = document.getElementById('google_map_address');;
		this.map_canvas = document.getElementById('google_map_canvas');;
		this.init_map();
		this.create_autocomplete();
		this.init_event();
	},
	init_event:function(){
		var thisC = this;
		$(this.input_address).keydown(function(e) {
			if (e.keyCode != 13) return;
			thisC.search_by_address(this.value)
			return false;
	});
	},
	create_autocomplete:function(){
		var autocomplete = new google.maps.places.Autocomplete(this.input_address);
		autocomplete.bindTo('bounds', this.map);
		// 검색 타입 제한.
		/*
		var type = ['address','establishment','geocode']; //빈 배열이면 3가지 전부 다.
		autocomplete.setTypes(types);
		*/
	},
	init_map:function(){
		this.create_map(this.input_lat.value,this.input_lng.value,this.input_zoom.value)
	},
	init_readonly_map:function(google_map_canvas,lat,lng,zoom){
		this.map_canvas = google_map_canvas;
		this.create_map(lat,lng,zoom,true)
	},
	create_map:function(lat,lng,zoom,fixed){
			lat = parseFloat(lat);
			lng = parseFloat(lng);
			zoom = parseFloat(zoom);
			if(!lat) lat=37.5679872;
			if(!lng) lng=126.97716349999996;
			if(!zoom) zoom=13;
			
			
			map = new google.maps.Map(this.map_canvas, {
				center: {lat: lat, lng: lng},
				scrollwheel: true,
				zoom: zoom
			});
			this.map = map;
			var sync_map_info = function(thisC){
				return function(){
					thisC.sync_map_info();
				}
			}(this)
			map.addListener('bounds_changed', sync_map_info);
			map.addListener('center_changed', sync_map_info);
			//map.addListener('dragend', sync_map_info);
			map.addListener('zoom_changed', sync_map_info);
			
					
			this.pos_maker = new google.maps.Marker({
				position: map.getCenter(),
				'map': map,
				title:"THERE!",
				draggable: !fixed,
				'visible':true
			});
			google.maps.event.addListener(this.pos_maker, 'dragend', function(thisC) {
				return function(){
					thisC.search_by_lat_lng(this.getPosition().lat(),this.getPosition().lng())
				}
			}(this));
			
			this.sync_map_info();
			
			return this.map;
	},
	sync_map_info:function(){

		if(!this.input_lat) return false;
		this.input_lat.value = this.pos_maker.getPosition().lat();
		this.input_lng.value = this.pos_maker.getPosition().lng();
		
		this.input_zoom.value = this.map.getZoom();
	},
	sync_address:function(results){
		//https://developers.google.com/maps/documentation/javascript/geocoding 참고
		//results[0] : 지번주소
		//results[1] : 도로명주소
		//results[n] : 등등 뒤로 갈 수록 넓은 범위 주소
		//results[n].formatted_address : 주소
		//results[n].location.lat() : 위도
		//results[n].location.lng() : 경도
		var address=  results[1].formatted_address;
		this.input_address.value = address;
	},
	search_by_address:function(address){ //주소검색
		if(address.length<1){return false;}
		this.geocoder.geocode( {'address': address}, 
			function(thisC){
				return function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						console.log(results);
						thisC.search_by_lat_lng(results[0].geometry.location.lat(),results[0].geometry.location.lng());
					} else {
						alert("입력하신 주소로 좌표를 가져올 수 없습니다. (" + status+")");
					}
				}
			}(this)
		);
	},
	//GPS로 좌표잡기
	search_by_gps:function(){
		mh_gps.get_location(function(thisC){ 
			return function(position){
				console.log(position);
				var coords = position.coords;
				var f = document.form_bbs
				//f.b_num_0.value = coords.latitude;
				//f.b_num_1.value = coords.longitude;
				thisC.search_by_lat_lng(coords.latitude,coords.longitude);
			}
		}(this));
		
	},
	//위도 경도로 검색
	search_by_lat_lng:function(lat,lng){
		var geocoder = new google.maps.Geocoder();
		var latLng =  new google.maps.LatLng(lat,lng);
		this.map.setCenter(latLng)
		this.pos_maker.setPosition(latLng);
		geocoder.geocode( {'latLng': latLng}, 
			function(thisC){
				return function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						thisC.sync_address(results);
					} else {
						alert("입력하신 좌표로 주소를 가져올 수 없습니다. (" + status+")");
					}
				}
			}(this)
		);
	}
	
}







