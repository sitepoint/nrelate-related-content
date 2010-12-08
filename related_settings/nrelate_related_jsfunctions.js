// Takes values from input fields, makes appropriate conversions
// Opens a pop up window with preview url with these parameters
function nrelate_related_popup_preview(NRELATE_RELATED_SETTINGS_URL,wp_root_nr, NRELATE_RELATED_PLUGIN_VERSION){
	if (!window.focus)return true;
	var nr_thumbsize, nr_ext_opt, nr_maxageposts, nr_age_num,age_frame, nr_href, nr_imageurl, nr_title, nr_number_ext, nr_numberrelated, nr_r_title, nr_r_max_char_perline, nr_ad, nr_logo, nr_thumb, nr_adval, nr_logoval, nr_thumbval;
	nr_title = "Nrelate_Preview";
	nr_href = NRELATE_RELATED_SETTINGS_URL+"/nrelate_popup_content.php";
	nr_numberrelated = document.getElementById("related_number_of_posts").value;
	nr_num_ext = document.getElementById("related_number_of_posts_ext").value;
	nr_r_title = document.getElementById("related_title").value;
	nr_r_max_char_perline = document.getElementById("related_max_chars_per_line").value;
	nr_adval = document.getElementById("show_ad").checked;
	nr_logoval = document.getElementById("show_logo").checked;
	nr_thumbval = document.getElementById("related_thumbnail").value;
	nr_imageurl = document.getElementById("related_default_image").value;
	nr_age_num = document.getElementById("related_max_age_num").value;
	nr_age_frame = document.getElementById("related_max_age_frame").value;
	nr_ext_opt = document.getElementById("related_blogoption").value;
	nr_r_title = escape(nr_r_title);
	// Convert max age time frame to minutes
	var nr_i, nr_check;
	for(nr_i=80;nr_i<155;nr_i=nr_i+10){
		nr_check = document.getElementById("related_imagesize_"+nr_i).checked;
		if(nr_check==true){
			nr_thumbsize = nr_i;
		}
	}
	
	switch (nr_age_frame){
		case 'Hour(s)':
			nr_maxageposts = nr_age_num * 60;
			break;
		case 'Day(s)':
			nr_maxageposts = nr_age_num * 1440;
			break;
		case 'Week(s)':
			nr_maxageposts = nr_age_num * 10080;
			break;
		case 'Month(s)':
			nr_maxageposts = nr_age_num * 44640;
			break;
		case 'Year(s)':
			nr_maxageposts = nr_age_num * 525600;
			break;
		}
		
	
	// Convert ad parameter
	switch (nr_adval){
	case true:
		nr_ad = 1;
		break;
	default:
		nr_ad = 0;
	}
	
	// Convert logo parameter
	switch (nr_logoval){
	case true:
		nr_logo = 1;
		break;
	default:
		nr_logo = 0;
	}
	
	// Convert thumbnail parameter
	switch (nr_thumbval){
	case 'Thumbnails':
		nr_thumb = 1;
		break;
	default:
		nr_thumb = 0;
	}
	
	// Convert external option parameter
	switch (nr_ext_opt){
	case 'On':
		nr_ext_opt = 1;
		break;
	default:
		nr_ext_opt = 0;
	}
	
	nr_tag = "?NUM="+nr_numberrelated+"&DOMAIN="+wp_root_nr+"&IMAGEURL="+escape(nr_imageurl)+"&NUMEXT="+nr_num_ext+"&TITLE="+escape(nr_r_title)+"&MAXCHAR="+nr_r_max_char_perline+"&AD="+nr_ad+"&LOGO="+nr_logo+"&THUMB="+nr_thumb+"&MAXAGE="+nr_maxageposts+"&EXTOPT="+nr_ext_opt+"&THUMBSIZE="+nr_thumbsize+"&RELATED_VERSION="+NRELATE_RELATED_PLUGIN_VERSION;
	nr_link = nr_href+nr_tag;
	window.open(nr_link,nr_title,'width=600,height=400,scrollbars=yes');
	return false;
}

// Ajax call to blog_transport.php to check the site status from blogroll
function checkblog(NRELATE_RELATED_SETTINGS_URL,nr_domain){
	if (nr_domain==""){
		document.getElementById("bloglinks").innerHTML="";
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
				document.getElementById("bloglinks").innerHTML=xmlHttp.responseText;
			}
		}
		var nr_params = nr_domain;
		xmlHttp.open("POST",NRELATE_RELATED_SETTINGS_URL+"/blog_transport.php",true);
		xmlHttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlHttp.send("domain="+nr_params);
	}catch(e){
		alert("Can't connect to server:\n" + e.toString()); 
	}
}