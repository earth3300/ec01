<?php
/**
 * EC01 Template (Earth3300\EC01)
 *
 * This file constructs the page. Required.
 *
 * File: template.php
 * Created: 2018-10-01
 * Update: 2018-11-06
 * Time: 07:59 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/**
 * The EC01 HTML Template.
 *
 * @return string
 */
class EC01Template extends EC01HTML{

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
			if ( $page['file']['page'] )
			{
				//We've got the whole thing. Add the header and deliver.
				header('Content-type: text/html; charset=utf-8;');
				return $page['page'];
				//Done!!!
			}
			else
			{
				pre_dump( $page['class'] );
				/** Construct the page on the "engine" page */
				header('Content-type: text/html; charset=utf-8;');

				/** The `DOCTYPE` is `html` (HTML5) */
				$str = '<!DOCTYPE html>' . PHP_EOL;

				/** Default Language is `en` or `en-CA`. The class is added as a complete string (i.e. 'class="..." ) */
				$str .= sprintf('<html %slang="%s">%s', $page['class']['html'], SITE_LANG, PHP_EOL);
				$str .= '<head>' . PHP_EOL;

				/** Charset is `UTF-8`. */
				$str .= sprintf( '<meta charset="%s">%s', SITE_CHARSET, PHP_EOL );

				/** Viewport is set for mobile devices */
				$str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;

				/** Page title. */
				$str .= sprintf( '<title>%s</title>%s', $page['page-title'], PHP_EOL );

				/** Can deliver a very basic version, if needed. */
				if ( SITE_USE_BASIC )
				{
					$str .= '<link rel=stylesheet href="/0/theme/css/01-bootstrap.css">' . PHP_EOL;
					$str .= '<link rel=stylesheet href="/0/theme/css/02-main.css">' . PHP_EOL;
				}
				else
				{
					/** Default is not to allow robots to index the site until we are ready. */
					$str  .= SITE_INDEX_ALLOW ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;

					/** Style sheet variations */
					if ( SITE_USE_CSS_MIN )
					{
						$str .= sprintf( '<link rel=stylesheet href="%s/style.min.css">%s', SITE_CSS_URL, PHP_EOL );
					}
					elseif ( SITE_USE_CSS_ALL )
					{
						$str .= sprintf( '<link rel=stylesheet href="%s/style.all.css">%s', SITE_CSS_URL, PHP_EOL );
					}
					else
					{
						$str .= SITE_USE_CSS_BOOTSTRAP ? sprintf( '<link rel=stylesheet href="%s/01-bootstrap.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
						$str .= SITE_USE_CSS_MAIN ? sprintf( '<link rel=stylesheet href="%s/02-main.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
						$str .= SITE_USE_CSS_COLOR ? sprintf( '<link rel=stylesheet href="%s/03-color.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
						$str .= SITE_USE_CSS_SPRITE ? sprintf( '<link rel=stylesheet href="%s/04-sprite.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
						$str .= SITE_USE_CSS_DEVICE ? sprintf( '<link rel=stylesheet href="%s/05-device.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
						$str .= SITE_USE_CSS_ADJUSTMENTS ? sprintf( '<link rel=stylesheet href="%s/06-adjustments.css">%s', SITE_CSS_URL, PHP_EOL ) : '';
					}
				}

				/** Close the `head` element. */
				$str .= '</head>' . PHP_EOL;

				/** If needed, the body has a class. Expecting: `class="..."`, with the appropriate spaces. */
				$str .= sprintf('<body%s>%s',$page['class']['body'], PHP_EOL);

				/** A body wrap. */
				$str .= '<div class="wrap">' . PHP_EOL;

				/** An inner wrap. */
				$str .= '<div class="inner">' . PHP_EOL;

				/** The main header element. */
				$str .= $page['header']['main'];

				/** An optional sub header element.  */
				$str .= isset( $page['header']['sub'] ) ? $page['header']['sub'] : '';

				/** The HTML5 `main` element. */
				$str .= '<main>' . PHP_EOL;

				/** The "article". This is what it is all about. */
				$str .= $page['article'];

				/** Close the main element. */
				$str .= '</main>' . PHP_EOL;

				/** Close the inner body wrap. */
				$str .= '</div>' . PHP_EOL; //inner

				/** The optional page sidebar (if anything). */
				$str .= $page['sidebar'];

				/** Close the body wrap */
				$str .= '</div>' . PHP_EOL; //wrap

				/** The page footer. */
				$str .= $page['footer'];

				/** Displays the time it took to generate the page. */
				$str .= SITE_ELAPSED_TIME ? get_site_elapsed() : '';

				/** Close the body element. */
				$str .= '</body>' . PHP_EOL;

				/** Close the `html` element. */
				$str .= '</html>';

				/** Return the string so it can be echoed. */
				return $str;
				}
			}
		else
		{
			/** Else, we've got nothing to work with. */
			return "The Page Array is not available.";
		}
	} // end function.
} // end class.

/**
* Get the elapsed time.
*
* Get the elapsed time from when the request first reached the server, to just before the end.
*/
function get_site_elapsed(){

	/** Can be used anywhere on the site to determine load time to that point. */
   global $site_elapsed;

   /** Explains the meaning of the time to the end user */
   $msg = 'Time (in milliseconds) to process the underlying code from when the request reaches the server to just before it leaves the server. Lower numbers are better.';

   /** End time to four decimal places in seconds (float) */
   $site_elapsed['end'] = microtime( true );

   /** Calculates elapsed time (accurate to 1/10000 seconds). Expressed as milliseconds */
   $time = number_format( ( $site_elapsed['end'] - $site_elapsed['start'] ) * 1000, 2, '.', ',' );

   $str = '<footer id="elapsed-time" class="subdued text-center" ';
   $str .= sprintf( 'title="%s">Elapsed: %s ms</footer>%s', $msg, $time , PHP_EOL ) ;

   return $str;
}
