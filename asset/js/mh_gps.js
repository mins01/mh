var mh_gps ={
	get_location:function(cb_success,cb_error){
		if(!navigator.geolocation){
			alert('geolocation는 지원되지 않는 기능입니다.');
			return false;
		}
		var geo_options	 = {'enableHighAccuracy':true,'timeout':10000};
		var geoloc = null;
		geoloc = navigator.geolocation.getCurrentPosition(
			function (cb_success){
				return function (position){
					cb_success(position)
				}
			}(cb_success?cb_success:this.cb_success)
			,
			function (cb_error){
				return function (error){
					cb_error(error)
				}
			}(cb_error?cb_error:this.sample_cb_error)
			,geo_options)

	},
	sample_cb_success:function(position){
		console.log(position);
		//var coords = position.coords;
		//var timestamp  = position.timestamp;
		//...
			
	}
	,
	sample_cb_error:function(error){
		console.log(error);
			
	}
}