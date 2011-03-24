
//hide and show advanced thumbnail options
function nrelate_showhide_thumbnail(idname){
	var nr_thumbval = document.getElementById(idname).value;
	switch (nr_thumbval){
	case 'Thumbnails':
		jQuery('#imagesizepreview').show('slow');
		jQuery('#imagesizepreview_header').show('slow');
		jQuery('#imagepreview').show('slow');
		jQuery('#imagepreview_header').show('slow');
		jQuery('#imagecustomfield').show('slow');
		jQuery('#imagecustomfield_header').show('slow');
		break;
	default:
		jQuery('#imagesizepreview').hide('slow');
		jQuery('#imagesizepreview_header').hide('slow');
		jQuery('#imagepreview').hide('slow');
		jQuery('#imagepreview_header').hide('slow');
		jQuery('#imagecustomfield').hide('slow');
		jQuery('#imagecustomfield_header').hide('slow');
	}
}

//Ajax call to a_transport.php to check ad validation
function checkad(NRELATE_ADMIN_URL,nr_domain,nr_adminversion,nr_idname,nr_adcode){
	if (nr_domain==""){
		document.getElementById(nr_idname).innerHTML="";
		return;
	}
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		var xmlHttp =new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		var xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	try{
		xmlHttp.onreadystatechange=function(){
			if (xmlHttp.readyState==4 && xmlHttp.status==200){
				document.getElementById(nr_idname).innerHTML=xmlHttp.responseText;
			}
		}
		xmlHttp.open("POST",NRELATE_ADMIN_URL+"/a_transport.php",true);
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttp.send("domain="+nr_domain+"&adcode="+nr_adcode+"&adminversion="+nr_adminversion);
	}catch(e){
		alert("Can't connect to server:\n" + e.toString()); 
	}
}

//Ajax call to index_transport.php to check the site's indexing status
function checkindex(nr_settingsurl,nr_domain,nr_admin_version){
	if (nr_domain==""){
		document.getElementById("indexcheck").innerHTML="";
		return;
	}
	if (window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
		var xmlHttp =new XMLHttpRequest();
	}
	else{// code for IE6, IE5
		var xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	try{
		xmlHttp.onreadystatechange=function(){
			if (xmlHttp.readyState==4 && xmlHttp.status==200){
				document.getElementById("indexcheck").innerHTML=xmlHttp.responseText;
			}
		}
		xmlHttp.open("POST",nr_settingsurl+"/index_transport.php",true);
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttp.send("domain="+nr_domain+"&admin_version="+nr_admin_version);
	}catch(e){
		alert("Can't connect to server:\n" + e.toString()); 
	}
}
