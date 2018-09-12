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
		$max = $this->get_max_images( $args );

		if ( $this->check_args( $args ) )
		{
			$match = $this->match( $args );
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
				$args['src'] = str_replace( SITE_PATH, '', $file );
				$str .= $this->get_image_html( $args );
			}
			$str .= '</article>' . PHP_EOL;
			return $str;
		}
		else
		{
			return "Error.";
		}
	}

	/**
	 * Get the maximum number of images to process..
	 *
	 * @param array $args
	 * @return int
	 */
	private function get_max_images( $args )
	{
		if ( isset( $args['max'] ) )
		{
			$max = $args['max'];
		}
		else
		{
			$max = 5;
		}
		return $max;
	}

	/**
	 * Build the match string.
	 *
	 * @param array $args
	 * @return string|false
	 */
	private function match( $args )
	{
		if (  defined( 'SITE_CDN_PATH' ) ) {
			$root = SITE_CDN_PATH;
			$prefix = "/*";
			$type = $this -> get_image_type( $args );
			$pattern =  $prefix . $type;
			$match =  $root . $args['dir'] . $pattern;
			return $match;
		}
		else {
			return "Error.";
		}
	}

	/**
	 * Get the type of image to process.
	 *
	 * If no type is specified, return the default (.jpg). Include the dot (.)
	 * for simplicity.
	 *
	 * jpg or png. Default: jpg
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_type( $args )
	{
		if ( isset( $args['type'] ) )
		{
			if ( 'png' == $args['type'] )
			{
				return '.png';
			}
			else if ( 'jpg' == $args['type'] )
			{
				return '.jpg';
			}
		}
		else
		{
			return '.jpg';
		}
	}

	/**
	 * Get the image HTML
	 *
	 * If no type is specified, return the default (.jpg). Include the dot (.)
	 * for simplicity.
	 *
	 * jpg or png. Default: jpg
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_html( $args )
	{
		$str = sprintf( '<a href="%s">', SITE_URL . $args['src'] );
		$str .= '<img ';
		$str .= sprintf( ' class="%s"', $this->get_image_class( $args ) );
		$str .= sprintf( ' src="%s"', $args['src'] );
		$str .= sprintf( ' alt="%s"', $this->get_image_alt( $args ) );
		$str .= sprintf( ' width="600"', $this->get_image_width( $args ) );
		$str .= sprintf( ' height="%s"', $this->get_image_height( $args ) );
		$str .= ' />';
		$str .= '</a>' . PHP_EOL;
		return $str;
	}

	/**
	 * Get the image width.
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_width( $args )
	{
		if ( defined( 'SITE_IMAGE_WIDTH' ) )
		{
			$width = SITE_IMAGE_WIDTH;
		}
		else
		{
			$width = '600';
		}
	}

	/**
	 * Get the image height.
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_height( $args )
	{
		if ( defined( 'SITE_IMAGE_HEIGHT' ) )
		{
			$height = SITE_IMAGE_HEIGHT;
		}
		else
		{
			$height = '400';
		}
		return $height;
	}

/**
	 * Get the image alt tag.
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_alt( $args )
	{
		if ( "" !== $args['src'] )
		{
			$alt = $args['src'];
		}
		else
		{
			$alt = "Not available";
		}
		return $alt;
	}

	/**
	 * Get the image class.
	 *
	 * @param array $args
	 * @return string
	 */
	private function get_image_class( $args )
	{
		if ( defined( 'SITE_IMAGE_CLASS' ) )
		{
			$class = SITE_IMAGE_CLASS;
		}
		else
		{
			$class = 'generic';
		}
		return $class;
	}

	/**
	 * Check the arguments from the shortcode
	 *
	 * @param array $args
	 * @return bool
	 */
	private function check_args( $args )
	{
		if ( isset( $args['dir'] ) )
		{
			return true;
		}
		else
		{
			return false;
		}
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
	if ( is_array( $args ) )
	{
		$media_list = new MediaList();
		return $media_list -> get( $args );
	}
	else
	{
		return '<!-- Missing the image directory to process. [media-list dir=""]-->';
	}
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
