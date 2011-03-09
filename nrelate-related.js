function nr_loadframe(){
  if(nr_load_link){
    nr_load_link=false;
    window.location.href=nr_clicked_link;
  }
}

jQuery(document).ready(function(){
	jQuery('.nr_rc_link, .nr_rc_panel, .nr_rc_panel_hover').live('click', function(event){
		event.preventDefault();
		var nr_src_url = window.location.href;
		var nr_iframe_src = "http://api.nrelate.com/rcw_wp/track.html";
		var nr_iframe = document.getElementById('nr_clickthrough_frame');
		if (jQuery(this).hasClass('nr_ad')) {
			nr_type = 'ad';
		} else if (jQuery(this).hasClass('nr_external')) {
			nr_type = 'external';
		} else {
			nr_type = 'internal';
		}
		nr_iframe_src += "?type=" + nr_type + "&domain=" + escape(nr_domain) + "&src_url=" + escape(nr_src_url) + "&dest_url=" + escape(jQuery(this).attr('href'));
		nr_load_link = true;
		nr_clicked_link = jQuery(this).attr('href');
		nr_iframe.src = nr_iframe_src;
	});
});

function nr_rc_fix_css(){
  var nr_height=0;
  jQuery("a.nr_rc_panel").each(function(){
    if(jQuery(this).innerHeight()>nr_height){
      nr_height=jQuery(this).innerHeight();
    }
  });
  jQuery("a.nr_rc_panel").css("height",nr_height+"px");
}

function nr_initialize () {
	nr_rc_fix_css();
	
	jQuery('#nrelate_related .nr_sponsored').hover(
		function(){
			jQuery(this).stop();
			jQuery(this).animate(
				{'left' : '0px'},
				200
			);
		},
		function(){
			jQuery(this).stop();
			jQuery(this).animate(
				{'left' : (jQuery(this).parent().width() - 18) + 'px'},
				200
			);
		}
	);
}