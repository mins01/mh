$('.selectpicker').selectpicker('mobile');
(function(){
	var tm = (new Date()).getTime();
	window.addEventListener('load',function(){
		var tm2 = (new Date()).getTime();
		var gap = tm2-tm;
		console.log("%cWelcome! "+document.location.hostname+"\n%cHTML LOADING TIME : "+gap+" ms","font-size:20px;color:#abc;","font-size:8px;color:#ccc;");
	});
})();
