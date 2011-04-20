function nr_loadframe(){
  if(nr_load_link){
    nr_load_link=false;
    window.location.href=nr_clicked_link;
  }
}

function nr_rc_fix_css(){
	if (jQuery('a.nr_rc_panel:first').length==0) return;
	
	var currentTallest = 0,
		 currentRowStart = 0,
		 rowDivs = new Array(),
		 $el,
		 topPosition = 0,
		 num_cols = 0,
		 row_counter = 0;
	
	currentRowStart = jQuery('a.nr_rc_panel:first').position().top;
	
	jQuery('a.nr_rc_panel').each(function() {
		$el = jQuery(this);
		$el.append('<div class="nr_clear" style="height:1px;"></div>');
		topPostion = $el.position().top;
		if (currentRowStart != topPostion) {
			for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
				rowDivs[currentDiv].height(currentTallest);
			}
			rowDivs.length = 0;
			currentRowStart = topPostion;
			currentTallest = $el.innerHeight();
			rowDivs.push($el);
		} else {
			rowDivs.push($el);
			currentTallest = (currentTallest < $el.innerHeight()) ? ($el.innerHeight()) : (currentTallest);
		}
		
		for (currentDiv = 0 ; currentDiv < rowDivs.length ; currentDiv++) {
			rowDivs[currentDiv].height(currentTallest);
		}
	});
	
	topPosition = jQuery('a.nr_rc_panel:first').position().top;
	_elements = jQuery('a.nr_rc_panel');
	for (i=0; i<_elements.length; i++) { if (jQuery(_elements[i]).position().top!=topPosition) break; num_cols++; }
	num_rows = Math.ceil(_elements.length/num_cols);
	
	_row_counter = 1;
	_col_counter = 1;
	for(i=0; i<_elements.length; i++) {
		$el = jQuery(_elements[i]);
		row_even_odd = (_row_counter%2==0) ? ' nr_even_row' : ' nr_odd_row';
		col_even_odd = (_col_counter%2==0) ? ' nr_even_col' : ' nr_odd_col';
		nr_first_col = (_col_counter==1) ? ' nr_first_col' : '';
		nr_last_col = (_col_counter==num_cols && nr_first_col=='') ? ' nr_last_col' : '';
		nr_first_row = (_row_counter==1) ? ' nr_first_row' : '';
		nr_last_row = (_row_counter==num_rows && nr_first_row=='') ? ' nr_last_row' : '';
		$el.addClass('nr_row_' + _row_counter + ' nr_col_' + _col_counter + row_even_odd + col_even_odd + nr_first_col + nr_last_col + nr_first_row + nr_last_row);
		_col_counter++;
		if (_col_counter>num_cols) {
			_col_counter = 1;
			_row_counter++;
		}
	}
}

//tracking
jQuery('.nr_rc_link').live('click', function(event){
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

function nr_initialize () {
	nr_rc_fix_css();
	
	jQuery(document).ready(function(){
		//sponsored animation
		jQuery('.nrelate_related .nr_sponsored').hover(
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
	});
}