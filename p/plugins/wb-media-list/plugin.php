<?php

defined('NDA') || exit('No direct access.');

/**
 * WP Bundle Media List.
 *
 * Lists images of a specific type in the given directory and outputs valid HTML.
 * Requires the configuration available at: {@link https://github.com/earth3300/ec01/}.
 *
 * @package WP Bundle Media List
 * @since 2018.9.0
 * @author Clarence Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 * @link http://wp.cbos.ca/plugins/wb-media-list
 * @see https://carlalexander.ca/designing-class-generate-wordpress-html-content/
 *
 * @wordpress-plugin
 * Plugin Name: WP Bundle Media List
 * Plugin URI:  http://wp.cbos.ca/plugins/wb-media-list/
 * Description: Lists all media of a given type in a given directory. Shortcode [media-list].
 * Version:     2018.9.0
 * Author:      Clarence Bos
 * Author URI:  https://www.tnoep.ca/
 * Text Domain: wb-media-list
 * License:     GPL-3.0+
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 */

/**
 * List the media of a certain type in a given directory.
 *
 * No WordPress hooks inside the class for better portability.
 */
class MediaList
{
	/**
	 * Use the PHP glob function to list files (of a certain type).
	 */
	public function get( $args )
	{
		$max = isset( $args['max'] ) ? $args['max'] : 5;
		$root = SITE_CDN_PATH;
		$pattern = "/*.jpg";

		$match =  $root . $args['dir'] . $pattern;

		$str = '<article>' . PHP_EOL;
		$cnt = 0;
		foreach ( glob( $match ) as $file )
		{
			$cnt++;
			if ( $cnt > $max )
			{
				break;
			}
			/** Remove the root of the file path to use it an image source. */
			$src = str_replace( SITE_PATH, '', $file );
			$str .= sprintf('<a href="%s"><img src="%s" width="500" height="auto" alt="Image" class="generic" /></a>%s', SITE_URL . $src, $src, PHP_EOL );
		}
		$str .= '</article>' . PHP_EOL;
		return $str;
	}
}

/**
 * Callback from the media-list shortcode.
 *
 * Performs a check, then instantiates the MediaList class
 * and returns the media list as HTML.
 *
 * @param array  $args['dir']
 * @return string  HTML as a list of images, wrapped in the article element.
 */
function media_list( $args )
{
	if ( ! is_array( $args ) || ! isset( $args['dir'] ) ) {
		return '<!-- Missing directory. We need this. -->';
	}

	$media_list = new MediaList();

    return $media_list -> get( $args );
}

/**
 * SITE_ parameters required.  { @see `/c/config/site/cfg-structure.php` }
 */
if( defined('SITE') )
{
	/** shortcode [media-list dir=""] */
	add_shortcode( 'media-list', 'media_list' );
}
else
{
	exit('SITE_ configuration paramaters are required for this class to work.');
}
