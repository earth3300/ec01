jQuery(document).ready(function($) {

  // mainBreaker, scrollBreaker, overflowBreaker, alignBreaker, debugBreaker, original, currMain, currFrame, ratio, calculated, work

  //create our "breaker" switches
  var mainBreaker = {
    'engage'	: true,
    'corral'	: true,
    'overlay'	: true,			//overlay header and footer if allowed
    'resize'	: true,			//turn resize off if not needed
    'hresize'	: true,			//resize image width (height set to 100%)
    'vresize'	: true,			//resize image height
  };

  var frameBreaker = {
      'resize'	: true,			//resize frame
  };

  var scrollBreaker = {
      'scroll'	: true,			//turn scroll off if not needed
      'default'	: true,			//scroll behaviour is default
      'hscroll'	: true,			//add horizontal scroll to image
      'vscroll'	: true,			//add vertical scroll to image
      'output'	: true,
  };

  var overflowBreaker = {
    'overflow'		: true,
    'horizontal'	: true,		//hide horizontal overflow
    'vertical'		: true,		//hide vertical overflow
    'output'		: true,
  };

  var alignBreaker = {
    'align'		: true,			//use alignment
    'center'	: true,			//center div or image
    'left'		: true,			//image or div to left
    'output'	: true,
  };

  var debugBreaker = {
    'debug'		: true, //debug
    'output'	: true,
  };

  //values

  var currViewport = {
    'viewport'	: true,
    'width'		: $( window ).width(),
    'height'	: $( window ).height(),
    'output'	: true,
  };

  var currCorral = {
    'corral' 	: true,
    'width'		: $( "#corral" ).width(),
    'height'	: $( "#corral" ).height(),
    'output'	: true,
  };
  //.replace(/[A-Za-z$-]/g, "")
  var currHeader = {
    'header': true,
    'height': parseInt( $( 'header' ).css( 'height' ) ),
    'line-height': parseInt( $( 'header' ).css( 'line-height' ) ),
    'font-size': parseInt( $( 'header' ).css( 'font-size' ) ),
    'output': true,
  };

  var currFooter = {
    'footer' 		: true,
    'height'		: parseInt( $( 'footer' ).css( 'height' ) ),
    'line-height'	: parseInt( $( 'footer' ).css( 'line-height' ) ),
    'font-size'		: parseInt( $( 'footer' ).css( 'font-size' ) ),
    'output'		: true,
  };

  var currFrame = {
    'frame' 	: true,
    'width'		: $( "#frame" ).width(),
    'height'	: $( "#frame" ).height(),
    'top'		: parseInt( $( "#frame" ).css( 'top' ) ),
    'bottom'	: parseInt( $( "#frame" ).css( 'bottom' ) ),
    'output'	: true,
    };

  var origImage = {
    'origImage'	: true,
    'width'		: parseInt( $( "#frame img:visible" ).attr( "width" ) ),
    'height'	: parseInt( $( "#frame img:visible" ).attr( "height" ) ),
    'output'	: true,
  };

  var currImage = {
    'currImage'	: true,
    'width'		: $( "#frame .resize" ).width(),
    'height'	: $( "#frame .resize" ).height(),
    'output'	: true,
  };

  var ratio = {
    'ratio'		: true,
    'aspect'	: 1.7778,
    'twidth'	: 1600,
    'theight'	: 900,
    'output'	: true,
};

  var calculated = {
    'aspect'	: null,
    'width'		: null,
    'height'	: null,
    'scroll'	: null,
    'margin'	: null,
    'output'	: true,
  };

  var work = {
    'work'		: true,
    'frame'		: false,
    'resized'	: false,
    'scroll' 	: false,
    'output'	: true,
  };

  //get to work
  if ( mainBreaker['engage'] ) {
    if ( frameBreaker['resize'] ) {
      resizeFrame();
    }
  }

  function onWindowResize(){
    $( window ).resize( resizeCorral() ) ;
  }

  function resizeFrame() {
    if ( currFrame['width'] < ratio['twidth'] * .55 || currFrame['height'] < ratio['theight'] * .95 )  {
      $( "#frame" ).css( { 'top': 0, 'bottom': 0 } );
      if ( mainBreaker['corral'] ) {
        resizeCorral();
      }
      work['frame'] = true;
    }
    if ( mainBreaker['resize'] ) {
      resizeImage();
    }
  }

  function resizeCorral(){

    if ( $( "#corral" ).width()  == 1600 ) {
      var nwidth = Math.round( $( "#frame" ).height() * ratio['aspect'] / 2 ) * 2;
      $("#corral").width( nwidth );
      calculated['corral'] = nwidth;
      work['corral'] = true;

    }
  }

  function resizeImage(){

    if ( currFrame['height'] < 900 ) {

      calculated['width'] = calcImageWidth();

      $("#frame img.resize").css( { 'max-width' : 'none' } );

      $("#frame img.resize").width( calculated['width'] );

      work['resized'] = true;

    }

    if ( scrollBreaker['scroll'] ) { //turn the whole thing off if we don't need it.

      scrollImage();

    }

    if ( alignBreaker['align'] ) { //turn the whole thing off if we don't need it.

      alignImage();

    }

    setTimeout( logOutput(), 5000 );

  }

  function calcImageWidth(){

    if ( origImage['width'] !== undefined ) {

      calculated['height'] = $( "#frame .resize" ).height();

      calculated['aspect'] = origImage['width'] / origImage['height'];

      currImage['width'] = Math.round( currImage['height'] * calculated['aspect'] );

      return currImage['width'];

    }
    else {

      return false;

    }
  }

  function alignImage() {

    if ( currImage['width'] > currViewport['width'] ) {

      if ( $("#frame img").hasClass( 'center' ) ) {

        var mleft = Math.round( ( currFrame['width'] - calculated['width'] ) / 2  );

          $('#frame img.hidden').css( { 'margin-left' : mleft + 'px' } );

          calculated['margin'] = mleft;

      }
    }
  }

  function scrollImage() {

    if ( currImage['width'] > currViewport['width'] ) {

      if ( scrollBreaker['default'] ) {

        $("#frame").css( { 'overflow-x': 'scroll' } );

        var sleft = ( currFrame['width'] - calculated['width'] ) / 2;

          $('#frame').scrollLeft( sleft );

          calculated['scroll'] = sleft;

      }
      else if ( $("#frame img").hasClass( 'scroll' ) ) {

        $("#frame").css( { 'overflow-x': 'scroll' } );


        if ( $("#frame img").hasClass( 'left' ) ) {

          var sleft = ( currFrame['width'] - calculated['width'] ) / 2;

            $('#frame').scrollLeft( sleft );

            calculated['scroll'] = sleft;

        }
        else if ( $("#frame img").hasClass( 'right' ) ) {

          var scroll = Math.abs( Math.ceil( currFrame['width'] - calculated['width'] / 2 ) );

            $('#frame').scrollLeft( scroll );

            calculated['scroll'] = scroll;

        }
        else {}

      }
      else {}
    }
  }

  function logOutput(){
    if ( debugBreaker['output'] ){
      if ( $( 'body' ).hasClass( 'logged-in' ) ) {
      //mainBreaker, scrollBreaker, overflowBreaker, alignBreaker,
      //debugBreaker, original, currMain, currFrame,
      //ratio, calculated, work

        console.log( mainBreaker );
      if ( scrollBreaker['output'] )
        console.log( scrollBreaker );
      if ( overflowBreaker['output'] )
        console.log( overflowBreaker );
      if ( alignBreaker['output'] )
        console.log( alignBreaker );
      if ( debugBreaker['output'] )
        console.log( debugBreaker );
      if ( ratio['output'] )
        console.log( ratio );
      if ( currViewport['output'] )
        console.log( currViewport );
      if ( currCorral['output'] )
        console.log( currCorral );

      if ( currHeader['output'] )
        console.log( currHeader );
      if ( currFooter['output'] )
        console.log( currFooter );

      if ( currFrame['output'] )
        console.log( currFrame );
      if ( origImage['output'] )
        console.log( origImage );
      if ( currImage['output'] )
        console.log( currImage );
      if ( calculated['output'] )
        console.log( calculated );
      if ( work['output'] )
        console.log( work );

      }
    }
  }

  function dlog( arr ) {
    if ( debugBreaker['debug'] ){
      console.log( arr );
    }
  }
});
