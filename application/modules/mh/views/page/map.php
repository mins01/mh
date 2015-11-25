<!-- 구글맵 확장 라이브러리 사용 -->
<script type="text/javascript"
	src="http://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyBw8nAJOdLCqN3DuGZJKvY0idP_QWRR5WM&sensor=false&libraries=places">
</script>
<script type="text/javascript">
// <![CDATA[
	var geocoder;
	var map;
	var marker,makerMe,flightPath;
	var reqAddress = ''
	var reqLatLng = '';
	var initZoom = 15;
	function init(){ //초기화
		//기본 위치
		if(reqLatLng.length>2){
			var t = reqLatLng.replace(/[^\.0-9,]/g,'').split(',');
			if(isFinite(t[0])){
				var initLatLng = new google.maps.LatLng(t[0], t[1]);
			}
		}
		if(!initLatLng){
			var initLatLng = new google.maps.LatLng(37.5985, 126.978);
		}
		//지오인코더, 주소를 위도,경도로 바꿔주지.
		geocoder = new google.maps.Geocoder();
		//맵 설정
		var myOptions = { 
				center: initLatLng,
				zoom: initZoom,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
				streetViewControl: true,
				streetViewControlOptions: {
				position: google.maps.ControlPosition.LEFT_BOTTOM
				},
				mapTypeControl: true,
				mapTypeControlOptions: {
				style: google.maps.MapTypeControlStyle.INSET,
				position: google.maps.ControlPosition.RIGHT_BOTTOM
				},
				zoomControl: true,
				zoomControlOptions: {
				style: google.maps.ZoomControlStyle.LARGE,
				position: google.maps.ControlPosition.LEFT_CENTER
				},
				
			};
		map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
		//--- 추가 컨트롤
		//var myControl = new MyControl();
		var naviDiv = document.getElementById('navi');
		var infoDiv =  document.getElementById('info');
		map.controls[google.maps.ControlPosition.TOP_CENTER].push(naviDiv);
		map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(infoDiv);
		//--- 마커2 정의
		var lineSymbol = {
			path: google.maps.SymbolPath.CIRCLE,
			scale:10,
			strokeWeight:2,
			strokeColor:'blue',
			fillColor:'blue',
			fillOpacity:0.5
		};
			
		var mi = new google.maps.MarkerImage('img/mypos.gif');
		myMarker = new google.maps.Marker({
				position: map.getCenter(),
				'map': map,
				title:"I AM HERE!",
				draggable:false,
				'icon':mi,
				'visible':false
		});
		//--- 마커 정의
		marker = new google.maps.Marker({
				position: map.getCenter(),
				'map': map,
				title:"HERE!",
				draggable:true
		});
		//--- 라인설정
		
		flightPath = new google.maps.Polyline({
			path: [
						marker.getPosition(),
						myMarker.getPosition()
					],
			strokeColor: "#FF0000",
			strokeOpacity: 0.8,
			strokeWeight: 2,
			'map':map,
			visible:false
		});

		//---
		init_event();
		syncInfo();
		//--- 주소초기검색
		if(reqLatLng.length<=2 && reqAddress.length>=2){
			searchByAddress(reqAddress)
		}		
		//-- 불필요 버튼 감추기
		try{
			if(window.opener && window.opener.callbackMaps){
			}else{
				document.getElementById('btn_send').style.display = 'none';
			}
		}catch(e){

		}
		//--- 자동 완성
		var autocomplete = new google.maps.places.Autocomplete(document.form_search.address);
		autocomplete.bindTo('bounds', map);
		// 검색 타입 제한.
		/*
		var type = ['address','establishment','geocode']; //빈 배열이면 3가지 전부 다.
		autocomplete.setTypes(types);
		*/
	}
	function init_event(){ //이벤트 설정
		google.maps.event.addListener(marker, 'click', function() {
			//map.setZoom(15);
			var z = map.getZoom()
			if(z<16){
				map.setZoom(++z);
			}
			showAsCenter(marker.getPosition())
			searchByLatLng(marker.getPosition())
		});
		google.maps.event.addListener(marker, 'dragend', function() {
			showAsCenter(marker.getPosition())
			searchByLatLng(marker.getPosition());
		});
		google.maps.event.addListener(map, 'center_changed', function() {
			syncInfo();
		});

		window.onorientationchange = function(){showAsCeterByMarker();}
		window.onresize = function(){showAsCeterByMarker();}

	}
	function showAsCenter(latLng){ //지도를 가운데로
		marker.setPosition(latLng);
		map.setCenter(latLng);
	}
	function showAsCeterByMarker(){ //마커 기준 중앙으로
		map.setCenter(marker.getPosition());
	}
	function showAsMarkerByCeter(){ //현재 중앙으로 마커를 이동
		marker.setPosition(map.getCenter());
		searchByLatLng(map.getCenter())
	}	
	function getInto(){ //정보가져오기
		//var LatLng = map.getCenter();
		var LatLng = marker.getPosition();
		var obj = {
			'lat':LatLng.lat()
			,'lng':LatLng.lng()
			,'address':document.form_search.address.value
		}
		showDistance();
		return obj
	}

	//--- 모양
	function syncInfo(){//정보보이기
		var obj = getInto();
		document.getElementById('span_lat').innerHTML = obj.lat.toFixed(4);
		document.getElementById('span_lng').innerHTML = obj.lng.toFixed(4);
		document.form_search.address.value = obj.address
	}	
	//---검색
	function searchByAddress(address){ //주소검색
		if(address.length<1){return false;}
		geocoder.geocode( {'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				showAsCenter(results[0].geometry.location);
				syncInfo();
			} else {
				//alert("Geocode was not successful for the following reason: " + status);
				alert("입력하신 주소로 좌표를 가져올 수 없습니다. (" + status+")");				
			}
		});
	}
	function searchByLatLng(latLng){
		geocoder.geocode( {'latLng': latLng}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				if (results[1]) {
						//showAsCenter(results[1].geometry.location);
						syncInfo();						
						document.form_search.address.value=results[1].formatted_address;
				}
			} else {
				//alert("Geocode was not successful for the following reason: " + status);
				alert("입력하신 좌표로 주소를 가져올 수 없습니다. (" + status+")");
			}
		});
	}
	function callOpener(){
		if(window.opener && window.opener.callbackMaps){
			var obj = getInto()
			window.opener.callbackMaps(obj,window);
		}
	}

//=== 본인위치관련
		var myCoords = null
		var myStat = 0;
		var geo_successCallback = function(position){
			var coords = position.coords;
			myCoords = coords; 
			var initLatLng = new google.maps.LatLng(myCoords.latitude , myCoords.longitude );
			if(myMarker){
				myMarker.setPosition(initLatLng);
				document.getElementById('span_my_lat').innerHTML = myCoords.latitude.toFixed(4);
				document.getElementById('span_my_lng').innerHTML = myCoords.longitude.toFixed(4);
			}
			showDistance();
			if(myStat==0){
				myStat = 1;
				myMarker.setVisible(true);
				flightPath.setVisible(true);
				showAsCeterByMyPos();
			}
		}
		var geo_errorCallback = function(error){
			alert("에러로인해서 위치정보를 사용할 수 없습니다.");
			alert("ERROR : ["+error.code+"] "+error.message)
			useMyPosition(false);
		};
		var geo_options	 = {'enableHighAccuracy':true,'timeout':5000};
		function watchPosition(){
			if(!navigator.geolocation){
				alert('geolocation는 지원되지 않는 기능입니다.');
				useMyPosition(false);
				return false;
			}
			if(!watchPosition.watchId){ watchPosition.watchId = null; }
			else{clearWatch();}
			watchPosition.watchId = navigator.geolocation.watchPosition(geo_successCallback,geo_errorCallback,geo_options);
		}
		function clearWatch(){
			if(!navigator.geolocation){
				//alert('geolocation는 지원되지 않는 기능입니다.');
				return false;
			}
			navigator.geolocation.clearWatch(watchPosition.watchId);
		}
		function useMyPosition(b){
			var btn_mypos = document.getElementById('btn_mypos');
			if(b){
				btn_mypos.value = "현재위치:O"
				watchPosition();				
			}else{
				
				myMarker.setVisible(false);
				flightPath.setVisible(false);
				btn_mypos.value = "현재위치:X"
				clearWatch();
				myStat = 0;
			}
		}
		function toggleMyPosition(){
			if(myMarker.getVisible()){
				useMyPosition(false)

			}else{
				useMyPosition(true)
			}
		}
		function showAsCeterByMyPos(){
			if(myStat==1){
				map.setCenter(myMarker.getPosition());
			}
		}
		function showDistance(){
			if(myStat==1){
				if(myMarker){
					var t = google.maps.geometry.spherical.computeDistanceBetween(myMarker.getPosition(),marker.getPosition());
					if(t<1000){
						document.getElementById('span_my_distance').innerHTML = t.toFixed(2)+'m';
					}else{
						t = t/1000;
						document.getElementById('span_my_distance').innerHTML = t.toFixed(2)+'㎞';
					}
					flightPath.setPath([marker.getPosition(),myMarker.getPosition()]);
				}
			}
		}
//=== 메뉴관련
	function showMenu(n,th){
		var ths = th.parentNode.getElementsByTagName('th');
		for(var i=0,m=ths.length;i<m;i++){
			ths[i].className ='';
		}
		th.className ='selected';
		var box_marker = document.getElementById('box_marker');
		var box_mypos = document.getElementById('box_mypos');	
		if(n==1){
			box_marker.className = '';
			box_mypos.className = 'hide';
		}else if(n==2){
			box_marker.className = 'hide';
			box_mypos.className = '';
		}
	}
//=== 테스트 관련
function test(){
	//var initLatLng = new google.maps.LatLng(37.5985 , 126.9780);
	//var initLatLng2 = new google.maps.LatLng(37.5081 , 127.0035);
	//var t = google.maps.geometry.spherical.computeDistanceBetween(initLatLng,initLatLng2);
	//alert(t);
	showDistance()
}

$(function(){init()})
//]]>
	</script>
	<div id="navi">
			<div id="search" class="box_shadow">
				<form name="form_search" action="javascript:void(null);" onsubmit="searchByAddress(this.address.value); return false;">
					<table width="100%" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<td width="50">주소 : </td>
							<td class="textbox"><input class="textbox" name="address" type="text" value="" size="50" style="width:100%;"></td>
							<td width="80"><input class="button" type="submit" value="검색"><input class="button" type="button" id="btn_send" value="전달" onClick="callOpener()"></td>
						</tr>
					</table>
					
				</form>
			</div>
		</div>
		<div id="info" class="box_shadow">
                <table id="tbl_menu" width="100%" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                    <th width="50%" class="selected" onclick="showMenu(1,this)"><input type="button" value="마커" /></th>
                    <th onclick="showMenu(2,this)"><input type="button" value="본인" /></th>
                    </tr>
                </table>
            <div id="box_marker" class="">
          		<div style="text-align:center"><input id="btn_fromcenter" type="button" value="마커로이동" onclick="showAsCeterByMarker()" title="마커가 있는 위치로 이동합니다." /><input id="btn_tocenter" type="button" value="마커를이동" onclick="showAsMarkerByCeter()"  title="마커를 현재 위치로 이동 시킵니다.(주소도 같이 알아냅니다.)" /></div>
위도 : <span id="span_lat"></span><br />
경도 : <span id="span_lng"></span>
             </div>
            <div id="box_mypos" class="hide">
        		<div style="text-align:center"><input id="btn_mypos" type="button" value="현재위치:X"  onclick="toggleMyPosition()"  title="현재 위치를 표시합니다." /><input id="btn_mypos" type="button" value="위치로이동"  onclick="showAsCeterByMyPos()"  title="현재 위치로 이동합니다." /></div>
위도 : <span id="span_my_lat"></span><br />
경도 : <span id="span_my_lng"></span><br />
마커와거리 : <span id="span_my_distance"></span><br />
              	</div>
<!-- 
<input type="button" value="test" onclick="test()">
-->
			</div>
	<div style="position:absolute;top:55px;left:5px;right:5px;bottom:5px">
	<div id="map_canvas" style="width:100%; height:100%"></div>
	</div>
