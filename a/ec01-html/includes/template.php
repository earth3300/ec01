<?php

defined( 'SITE' ) || exit;

/**
 * The FireFly HTML Template.
 *
 * @return string
 */
class FireFlyTemplate extends FireFlyHTML{

	/**
	 * Get the HTML
	 *
	 * Construct from the page array.
	 *
	 * @return string
	 */
	function getHtml( $page )
	{
		if ( is_array( $page ) )
		{
			/** Construct the page on the "engine" page */
			header('Content-type: text/html; charset=utf-8;');
			$str = '<!DOCTYPE html>' . PHP_EOL;
			$str .= ! empty( $page['class']['html'] ) ? sprintf('<html class="%s" lang="%s">%s', $page['class']['html'], SITE_LANG, PHP_EOL) : sprintf( '<html lang="%s">%s', SITE_LANG, PHP_EOL );
			$str .= '<head>' . PHP_EOL;
			$str .= sprintf( '<meta charset="%s">%s', SITE_CHARSET, PHP_EOL );
			$str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
			$str .= sprintf( '<title>%s</title>%s', $page['page-title'], PHP_EOL );
			$str  .= SITE_INDEX_ALLOW ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
			$str .= ! SITE_USE_CSS_MIN ? sprintf( '<link rel=stylesheet href="%s/style.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
			if ( SITE_USE_CSS_MIN ) {
				$str .= SITE_USE_CSS_MIN ? sprintf( '<link rel=stylesheet href="%s/style.min.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
			} else {
				$str .= SITE_USE_CSS_FONT ? sprintf( '<link rel=stylesheet href="%s/font.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
				$str .= SITE_USE_CSS_CHILD ? sprintf( '<link rel=stylesheet href="%s/child.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
				$str .= SITE_USE_CSS_SPRITE ? sprintf( '<link rel=stylesheet href="%s/sprite.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
				$str .= SITE_USE_CSS_COLOR ? sprintf( '<link rel=stylesheet href="%s/color.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
				$str .= SITE_USE_CSS_MONITORS ? sprintf( '<link rel=stylesheet href="%s/monitors.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
				$str .= SITE_USE_CSS_PRINT ? sprintf( '<link rel=stylesheet href="%s/print.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
			}
			// make path to style dependent on whether site is is subdomain or subfolder
			// $css_url_path
			$str .= '</head>' . PHP_EOL;
			$str .= ! empty( $page['class']['body'] ) ? sprintf('<body class="%s">%s',$page['class']['body'], PHP_EOL) : '<body>' . PHP_EOL;
			$str .= '<div class="wrap">' . PHP_EOL;
			$str .= '<div class="inner">' . PHP_EOL;
			$str .= $page['header']['main'];
			$str .= $page['header']['sub'];
			$str .= '<main>' . PHP_EOL;
			$str .= $page['article'];
			$str .= '</main>' . PHP_EOL;
			$str .= '</div>' . PHP_EOL; //inner
			$str .= $page['sidebar'];
			$str .= '</div>' . PHP_EOL; //wrap
			$str .= $page['footer'];
			$str .= SITE_ELAPSED_TIME ? get_firefly_elapsed() : '';
			$str .= '</body>' . PHP_EOL;
			$str .= '</html>';

			return $str;
			}
		else
		{
			return "The Page Array is not available.";
		}
	}
} // end class.

/**
* Get the elapsed time from when the request first reached the server, to just before the end.
*/
function get_firefly_elapsed(){

   global $site_elapsed;

   /** Explains the meaning of the time to the end user */
   $msg = 'Time (in milliseconds) to process the underlying code from when the request reaches the server to just before it leaves the server. Lower numbers are better.';

   /** End time to four decimal places in seconds (float) */
   $site_elapsed['end'] = microtime( true );

   /** Calculates elapsed time (accurate to 1/10000 seconds). Expressed as milliseconds */
   $time = number_format( ( $site_elapsed['end'] - $site_elapsed['start'] ) * 1000, 2, '.', ',' );

   $str = '<div id="elapsed-time" class="subdued text-center" ';
   $str .= sprintf( 'title="%s">Elapsed: %s ms</div>%s', $msg, $time , PHP_EOL ) ;

   return $str;
}
