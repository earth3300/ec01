jQuery(document).ready(function($) {
	var width = $( "#frame" ).width();
	var height = $( "#frame" ).height();
	var $inner = $( "#frame .inner" );
	var pageTurner = true;
    if ( pageTurner ) {
    	$("#next-page").click( function() {
    		var next = $('.page:visible').next( '.page' );
    		if ( next.length != 0  )  {
    			$("#title").hide();
				$('.page:visible:first').fadeOut( 165 );
				$('.page:visible:first').next( '.page' ).delay( 165 ).fadeIn( 165 );
			}
    	});
	    
		$("#prev-page").click( function() {
			var prev = $('.page:visible').prev( '.page' );
			if ( prev.length != 0 ) {
				$("#title").hide();
				$('.page:visible:first').fadeOut( 165 );
				$('.page:visible:first').prev( '.page' ).delay( 165 ).fadeIn( 165 );
			}
		});
    }	
	$("#hide-header").click( function(){
    	$( 'header' ).fadeOut( 165 );
    	$( 'footer' ).fadeIn( 165 );
    	$( "#frame" ).fadeOut( 165, function() {
    		$( this ).css( { 'top': 0, 'bottom' : '46px' } );
    		var nheight = $( "#frame" ).height();
    		var nwidth = Math.ceil( nheight * 16 / 9 * 2  ) / 2;
    		$( "#frame" ).width( '100%' );
    		$( "#frame img" ).width( nwidth );
    		$( "#corral" ).width( nwidth );
        	$( $inner ).width( nwidth );
    		$( this ).fadeIn( 165 );    	    
    	} );
    });
    
    $("#default-screen").click( function(){
    	$( 'header' ).fadeIn( 165 );
    	$( 'footer' ).fadeIn( 165 );
    	$( "#frame" ).fadeOut( 165, function() {
    		$( this ).css( { 'top': '48px', 'bottom' : '46px' } );
    		var nwidth = Math.ceil( height * 16 / 9 * 2 ) / 2;
    		$( "#corral" ).width( nwidth );
    		$( $inner ).width( nwidth );
    		$( "#frame img" ).width( nwidth );
    		$( this ).fadeIn( 165 );
    	});    	    	    	
    });
    
    $("#full-screen").click( function(){
    	$( 'header' ).fadeOut( 165 );
    	$( 'footer' ).fadeOut( 165 );
    	$( "#frame" ).fadeOut( 165, function() {
    		$( this ).css( { 'top': 0, 'bottom' : 0 } );
    		var nheight = $( "#frame" ).height();
    		var nwidth = Math.ceil( nheight * 16 / 9 * 2  ) / 2;
    		$( "#frame" ).width( '100%' );
        	$( "#corral" ).delay( 165 ).width( nwidth );
        	$( $inner ).width( nwidth );
        	$( "#frame img" ).width( nwidth );
        	$( this ).fadeIn( 165 );
    	});
    });
    
    $("#handset").click( function(){
    	$( "#handset .display" ).css( { 'opacity' : '1.0' }).fadeIn( 330, function(){
    		$("#frame").one( "click", function(){
    	    	$( "#frame .display" ).fadeOut( 330 );
    	    	$("#frame").animate( 330, { 'opacity' : "1.0" });
    	    });
    	});    	
    });
    
    $(".goto").click( function(){
    	var ex = this.id.split('-');
    	var id = ex[1];
    	var page = $( "#frame .page:nth-child(" + id + ")" );
    	if ( page.length != 0  )  {
	    	$('.page:visible:first').fadeOut( 165 );
	    	$( "#frame .page:nth-child(" + id + ")" ).delay( 165 ).fadeIn( 165 );
    	}
    });
});
