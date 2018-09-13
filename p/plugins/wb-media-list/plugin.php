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
		$max = $this->getMaxImages( $args );

		if ( $this->checkArgs( $args ) )
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

				$args['file'] = $file;
				/** Remove the root of the file path to use it an image source. */
				$args['src'] = str_replace( SITE_PATH, '', $file );
				$args['dim'] = $this->getImageDimensions( $args );
				$str .= $this->getImageHtml( $args );
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
	private function getMaxImages( $args )
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
			$type = $this -> getImageType( $args );
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
	private function getImageType( $args )
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
	private function getImageHtml( $args )
	{
		$str = '<div class="media">' . PHP_EOL;
		$str .= sprintf( '<a href="%s">', SITE_URL . $args['src'] );
		$str .= '<img ';
		$str .= sprintf( ' class="%s"', $this->getImageClass( $args ) );
		$str .= sprintf( ' src="%s"', $args['src'] );
		$str .= sprintf( ' alt="%s"', $this->getImageAlt( $args ) );
		$str .= sprintf( ' width="%s"', $this->getImageWidth( $args ) );
		$str .= sprintf( ' height="%s"', $this->getImageHeight( $args ) );
		$str .= ' />';
		$str .= '</a>' . PHP_EOL;
		$str .= '<div>';
		$str .= sprintf( '<span class="name">%s</span>', $this->getImageName( $args ) );
		$str .= sprintf( ' <span class="size">%s</span>', $this->getImageSize( $args ) );
		$str .= '</div>';
		$str .= '</div>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the image dimensions.
	 *
	 * Gets the image dimensions as the last part of the file name and only if
	 * it follows the format: ###x###, where # is an integer.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageDimensions( $args )
	{
		if ( "" !== $args['file'] )
		{
			$ex = explode( '-', $args['file'] );
			$part = $ex[ count( $ex ) - 1 ];
			$ex2 = explode( '.', $part );
			$dim1 = $ex2[ 0 ];
			$arr = explode( 'x', $dim1 );
			$dim['width'] = $arr[0];
			$dim['height'] = $arr[1];
		}
		else
		{
			$dim['width'] = 600;
			$dim['height'] = 400;
		}
		return $dim;
	}

	/**
	 * Get the image width.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageWidth( $args )
	{
		if ( defined( 'SITE_IMAGE_WIDTH' ) )
		{
			$width = SITE_IMAGE_WIDTH;
		}
		else
		{
			$width = $args['dim']['width'];
		}
		return $width;
	}

	/**
	 * Get the image height.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageHeight( $args )
	{
		if ( defined( 'SITE_IMAGE_HEIGHT' ) )
		{
			$height = SITE_IMAGE_HEIGHT;
		}
		else
		{
			$height = $args['dim']['height'];;
		}
		return $height;
	}

	/**
	 * Get the image size
	 *
	 * @param array $args
	 * @return int
	 */
	private function getImageSize( $args ){

		if ( class_exists('Imagick') )
		{
			$image = new Imagick( $args['file'] );
			$size = $image->getImageLength();
		}
		else
		{
			$size = filesize( $args['file'] );
		}
		$size = number_format( $size / 1000, 1, ".", "," );
		return $size . ' kB';
	}

	/**
	 * Get the image alt tag.
	 *
	 * May be the same as the name (or not).
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageAlt( $args )
	{
		if ( "" !== $args['src'] )
		{
			$ex = explode( '/', $args['src'] );
			$alt = $ex[ count( $ex ) - 1 ];
		}
		else
		{
			$alt = "Not available";
		}
		return $alt;
	}

	/**
	 * Get the image name.
	 *
	 * Gets the image name as the first part of the file name, before the
	 * double dashes. Converts hyphens into spaces and puts all characters
	 * into uppercase, for simplicity.
	 *
	 * May be the same as the alt tag.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageName( $args )
	{
		if ( "" !== $args['file'] )
		{
			$ex = explode( '/', $args['file'] );
			$file = $ex[ count( $ex ) - 1 ];
			$ex2 = explode( '--', $file );
			$name = $ex2[ 0 ];
			$arr = explode( '-', $name );
			$str = '';
			foreach ( $arr as $part )
			{
				$str .= strtoupper( $part ) . ' ';
			}
		}
		else
		{
			$str = "Not available";
		}
		return $str;
	}

	/**
	 * Get the image class.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageClass( $args )
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
	private function checkArgs( $args )
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

if ( ! function_exists( 'pre_dump' ) )
{
	function pre_dump( $arr )
	{
			echo "<pre>";
			var_dump( $arr );
			echo "</pre>";
	}
}
