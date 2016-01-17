/**
* 구글 맵 기능 사용
*/
/*
<script type="text/javascript"
	  src="http://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBw8nAJOdLCqN3DuGZJKvY0idP_QWRR5WM&libraries=places"></script>
*/
var mh_google_map={
	api_key:"AIzaSyBw8nAJOdLCqN3DuGZJKvY0idP_QWRR5WM",
	geocoder:null,
	map:null,
	panorama:null,
	// 위도와 경도로 주소 가져오기
	get_address_by_lat_lng:function(lat,lng,cb){
		var geocoder = new google.maps.Geocoder();
		var latLng =  new google.maps.LatLng(lat,lng);
		geocoder.geocode( {'latLng': latLng}, 
			function(cb){
				return function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						cb(results);
					} else {
						alert("입력하신 좌표로 주소를 가져올 수 없습니다. (" + status+")");
					}
				}
			}(cb)
		);
	},
	// 위도와 경도로 주소 가져오기 콜백 샘플
	sample_cb_get_address_by_lat_lng:function(results){
		//https://developers.google.com/maps/documentation/javascript/geocoding 참고
		//results[0] : 지번주소
		//results[1] : 도로명주소
		//results[n] : 등등 뒤로 갈 수록 넓은 범위 주소
		//results[n].formatted_address : 주소
		//results[n].location.lat() : 위도
		//results[n].location.lng() : 경도
	},
	init_street_view:function(div,lat,lng,heading,pitch){
		if(!heading) heading=0;
		if(!pitch) pitch=0;
		return this.panorama = new google.maps.StreetViewPanorama(
			div,
			{
				position: {lat: lat, lng: lng},
				pov: {heading: heading, pitch: pitch},
				zoom: 1
			});
	},
	
}