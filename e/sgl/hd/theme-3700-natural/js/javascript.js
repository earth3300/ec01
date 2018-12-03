jQuery(document).ready(function($) {
	
	var opts = {
    	'aspect'	: 1.7778,
		'vwidth'	: $( window ).width(),
    	'vheight'	: $( window ).height(),
    	'cwidth'	: $( "#corral" ).width(),
    	'cheight'	: $( "#corral" ).height(),
        'fwidth'	: $( "#frame" ).width(),
    	'fheight'	: $( "#frame" ).height(),
    	'dwidth'	: $( "#frame > .inner" ).width(),
    	'dheight'	: $( "#frame > .inner" ).height(),
    	'mwidth'	: $( "#frame img" ).attr( "width" ),
    	'mheight'	: $( "#frame img" ).attr( "height" ),
    	'rwidth'	: $( "#frame video" ).width(),
    	'rheight'	: $( "#frame video" ).height(),
    	'theight'	: 900,
    	'resize'	: false,  //resize both h & v.
    	'mresize'	: false,   //adjust image margin top
    	'hresize'	: false,   //resize image width (height set to 100%)
    	'vresize'	: false,   //resize image width (height set to 100%)
    	'scroll'	: true,   //add horizontal scroll to image
    	'center'	: false,  //center image
    	'left'		: true,
    	'overflow'	: false,  //hide image overflow
    	'debug'		: false,
    };
    
    if ( opts['debug'] ) {
		var html = '<section id="debug">' + opts['fwidth'] + ' x ' + opts['fheight'] + '</section>';
		$("#frame").append( html );
	}
	if ( opts['mwidth'] !== undefined && $("#frame").hasClass( 'pages' ) ) {
		
    	if ( opts['hresize'] && $( "#frame .page:visible > img").width() < 1600  ) {
    		console.log ( $( "#frame .page:visible > img").width() );
            $("#frame img").css( { 'max-width' : 'none', 'width' : $( "#frame .page:visible > img").width() } );	    			    	
        }
    	else if ( opts['mresize'] && opts['mheight'] < ['fheight'] * .97 ) {
	    	$("#frame img").height( opts['mheight'] );
	        var top = ( opts['fheight'] - opts['mheight'] ) / 2;
	        $("#frame img").css( { 'margin-top': top } );
	    }
	    else if ( opts['hresize'] && opts['mheight'] > opts['fheight'] ) {
	    	nheight = $("#frame > .inner").height();
	    	nwidth = opts['aspect'] * nheight;
	        nwidth = Math.round( nwidth * 2 ) / 2;
	        $("#frame img").width( nwidth );
	    }
	    else if ( opts['resize'] && opts['mwidth'] > opts['fwidth'] * .98 && opts['mheight'] > opts['fheight'] * .95 ) {
	    	$("#frame img").css( { 'width': '100%', 'height' : '100%' } );
	    }
	    else {}
	    if ( opts['fwidth'] < 1600 * .90 ) {
	    	
	    	$("#frame img").css( { 'max-width' : 'none', 'width' : opts['mwidth'] } );
	    	if ( opts['center'] && opts['overflow'] ) {
	    		// console.log( "overflow" );
	    		var mleft = ( opts['mwidth'] - opts['fwidth'] ) / 2 * -1;
	    		$('#frame img').css( { 'margin-left': mleft } );
	    	}
	    	else if ( opts['scroll'] &&  opts['center'] ) {
	    		// console.log( "scroll-center" );
	    		$("#frame").css( { 'overflow-x': 'scroll' } );
	    		var sleft = ( opts['mwidth'] - opts['fwidth'] ) / 2;
		    	$('#frame').scrollLeft( sleft );
	    	}
	    	else if ( opts['left'] && opts['scroll'] && $("#frame" ).hasClass( "pages" ) ) {
	    		//left adjust
	    		//console.log( "scroll-left" );
	    		$("#frame").css( { 'overflow-x': 'scroll' } );
	    		var sleft = ( opts['fwidth'] - opts['mwidth'] ) / 2;
		    	$('#frame').scrollLeft( sleft );
	    	}
	    	else if ( $("#frame" ).hasClass( "full" ) ) {
	    		//full left (do nothing)
	    		//console.log( "do nothing" );
	    		
	    	}
	    	else {
	    		//center
	    	}	    	
	    }	    
    }
    else if ( dheight !== undefined ) {    		
    	if ( dheight <= fheight ) {
    		var top = ( ( fheight - dheight ) / 2 );
	        top = Math.floor( top );
	        $("#frame > .inner").css( { 'top': top, 'margin-top': 0 } );
	    }
    	if ( opts['dheight'] > opts['fheight'] ) {
    		$("#frame > .inner").css( { 'top': 0, 'margin-top' : 0 } );
	        $("#frame").css( { 'overflow-y': 'scroll' } );
	    }    	
    }
    else {
    	
    }
	
});