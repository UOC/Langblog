function showhidelayer(layername,layernameCloser){
	document.getElementById(layernameCloser).style.display='none';
	
	if(document.getElementById(layername).style.display=='none') document.getElementById(layername).style.display='block';
	else document.getElementById(layername).style.display='none';
}