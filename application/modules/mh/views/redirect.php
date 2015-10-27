<script>
<!--
var msg = "<?=$msg?>";
var ret_url = "<?=$ret_url?>";

if(msg.length>0){
	alert(msg);
}

if(ret_url == -1){
	history.back();
}else{
	window.location.replace(ret_url);
}
-->
</script>