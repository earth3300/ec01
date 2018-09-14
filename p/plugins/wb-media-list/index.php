<?php

// if `add_shortcode` exists, we are in WordPress, otherwise not.
if( function_exists( 'add_shortcode' ) )
{
	// No direct access.
	defined('NDA') || exit('No direct access.');

	//shortcode [media-list dir=""]
	add_shortcode( 'media-list', 'media_list' );
}
else
{
	// Outside of WordPress. Instantiate directly, assuming current directory.
	$args['self'] = true;
	$media_list = new MediaList();
	$list = $media_list -> get( $args );
	echo $media_list->getPageHtml( $list );
}

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
		$args['self'] = $this->isDirSelf( $args );

		if ( $this->checkArgs( $args ) )
		{
			$match = $this->getMatchPattern( $args );
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
				$args['src'] = '/' . str_replace( $this->getSitePath(), '', $file );
				$args['dim'] = $this->getImageDimArr( $args );
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
	 * Get the SITE_PATH from the constant, else from the $_SERVER['DOCUMENT_ROOT']
	 *
	 * @return bool
	 */
	private function getSitePath()
	{
		if ( defined( 'SITE_PATH' ) )
		{
			return SITE_PATH;
		}
		else
		{
			return $_SERVER['DOCUMENT_ROOT'];
		}
	}

	/**
	 * Get the "Self" directory, if set.
	 *
	 * @param array $args
	 * @return bool
	 */
	private function isDirSelf( $args )
	{
		if ( isset( $args['self'] ) && true == $args['self'] )
		{
			return true;
		}
		else
		{
			return false;
		}
	}


	/**
	 * Get the maximum number of images to process.
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
	private function getMatchPattern( $args )
	{
		$path = $this->getBasePath( $args );
		$media = $this->getMediaDir( $args );
		$prefix = "/*";
		$type = $this -> getImageType( $args );
		$pattern =  $prefix . $type;
		//$match =  $path . $media . $prefix . $type;
		$match =  $path . $prefix . $type;

		if ( ! empty ( $match ) )
		{

		}
		else {
			$match = "Error.";
		}
		return $match;
	}

	/**
	 * Get the Base Path to the Media Directory.
	 *
	 * This does not need to include the `/media` directory.
	 *
	 * @param array $args
	 * @return string
	 */
	private function getBasePath( $args )
	{
		if ( isset( $args['self'] ) )
		{
			$path = __DIR__;
		}
		elseif ( defined( 'SITE_CDN_PATH' ) )
		{
			$path = SITE_CDN_PATH;
		}
		return $path;
	}

	/**
	 * Get the Media Directory
	 *
	 * @param array $args
	 * @return string
	 *
	 * @example $args['dir'] = '/architecture/shelter/micro-cabin/'
	 */
	private function getMediaDir( $args )
	{
		if ( isset( $args['dir'] ) )
		{
			$media = $args['dir'];
		}
		else
		{
			$media = '/media';
		}
		return $media;
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
			return '.png';
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
		$str .= sprintf( '<a href="%s">%s', $args['src'], PHP_EOL );
		$str .= '<img';
		$str .= sprintf( ' class="%s"', $this->getImageClass( $args ) );
		$str .= sprintf( ' src="%s"', $args['src'] );
		$str .= sprintf( ' alt="%s"', $this->getImageAlt( $args ) );
		$str .= sprintf( ' width="%s"', $this->getImageWidth( $args ) );
		$str .= sprintf( ' height="%s"', $this->getImageHeight( $args ) );
		$str .= ' />' . PHP_EOL;
		$str .= '</a>' . PHP_EOL;
		$str .= '<div class="text-center">';
		$str .= sprintf( '<span class="name">%s</span>', $this->getImageName( $args['src'] ) );
		$str .= sprintf( ' <span class="size">%s</span>', $this->getImageSize( $args ) );
		$str .= '</div>' . PHP_EOL;
		$str .= '</div>' . PHP_EOL;

		return $str;
	}

	/**
	 * Wrap the string in page HTML `<!DOCTYPE html>`, etc.
	 *
	 * @param string $str
	 * @return string
	 */
	public function getPageHtml( $html )
	{
		$str = '<!DOCTYPE html>' . PHP_EOL;
		$str .= '<html class="fixed-width" lang="en-CA">' . PHP_EOL;
		$str .= '<head>' . PHP_EOL;
		$str .= '<meta charset="UTF-8">' . PHP_EOL;
		$str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
		$str .= '<title>Media List</title>' . PHP_EOL;
		$str .= '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
		$str .= '<link rel=stylesheet href="/0/theme/css/style.css">' . PHP_EOL;
		$str .= '</head>' . PHP_EOL;
		$str .= '<body>' . PHP_EOL;
		$str .= '<main>' . PHP_EOL;
		$str .= $html;
		$str .= '</main>' . PHP_EOL;
		$str .= '</html>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the image dimensions.
	 *
	 * Gets the image dimensions as the last part of the file name and only if
	 * it follows the format: ###x###, where # is an integer.
	 *
	 * @param string $str
	 * @return string
	 */
	private function getImageDimArr( $args )
	{
		if ( ! empty( $args['src'] ) )
		{
			if ( $str = $this->extractImageDimStr( $args['src'] ) )
			{
				$arr = explode( 'x', $str );
				$dim['width'] = $arr[0];
				$dim['height'] = $arr[1];
			}
			else
			{
				$dim['width'] = 600;
				$dim['height'] = 450;
			}
			return $dim;
		}
		else {
			return false;
		}
	}

	/**
	 * Extract Image Dimensions from String
	 *
	 * In order to be valid or useful, image dimensions should be two digits or
	 * greater and have two dimensions (the width and the height). These are to
	 * be separated by the 'x' character (always), which is to be in lower case
	 * (always). Thus, we will have a minimum of 5 characters. In some cases, we
	 * may want to refer to a structural element as a '2x4', for example. This
	 * would refer to the common stud as used in house contruction, not an image
	 * size. Further, the image size should be last in the string (preceding the
	 * dot and then the extension). Thus, if we either trim off the extension
	 * (with the dot), or create a match pattern that includes the dot as the right
	 * most character, then we can use that as a right most starting point. Furhter,
	 * we can also use the hyphen just to the left as the left most bounding character.
	 * Thus, our rule would be: Look for a set of five or more characters (but not
	 * exceeding ...) that is bounded on the right by a dot and on the left by a hyphen.
	 * Further, ensure the characters to the right match those of a valide image extension.
	 * Here we allow jpg, png (only). Although a gif is a valid image extension, there
	 * is no real need to use it in most cases, as it allows for animation, which may
	 * be better served by a video (mp4, ogg or webm) format, rather than a gif.
	 *
	 * Since an image to be displayed on a web page would not be expected to exceed
	 * 9,999px, but may reach 1024 px or 2048 px, easily, we should allow up to
	 * 4 x 2 + 1 or nine characters for our match. Using preg_match that will contain a
	 * regex, we will then have something like:
	 *
	 * $regex = '/([0-9]{2,4})x([0-9]{2,4})\.(jpg|png)/'
	 *
	 * Brief summary: Match the pattern 00x00 to 0000x0000 with a .jpg or .png extension.
	 *
	 * Note that our previous efforts to pare down the number of variations by disallowing
	 * `jpeg` as a valid extension, means that our selection process here is simplified
	 * by that degree. There is no objective reason a JPEG file needs to be expressed in
	 * two ways (other than the fact that it is allowed), thus enforcing the use of `jpg`
	 * only here, makes life a lot simpler, when it comes to determining valid image files.
	 *
	 * @param string $str
	 * @return bool|string
	 *
	 * @example image-name-800x600.jpg
	 *
	 */
	private function extractImageDimStr( $str )
	{
		/**
		 * Since we won't have a valid image name with fewer than 13 characaters
		 * we won't bother processing anything with less than that length.
		 */
		if ( strlen( $str ) > 12 )
		{
			$regex = '/([0-9]{2,4}x[0-9]{2,4})/';
			preg_match( $regex, $str, $match );
			if ( ! empty( $match[0] ) )
			{
				return $match[0];
			}
			else
			{
				return false;
			}
		}
		else {
			return false;
		}
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
	 * image size, if present. Converts hyphens into spaces and puts all
	 * characters into uppercase, for simplicity. May be the same as the alt tag.
	 *
	 * The name:
	 *
	 * Starts with `/`
	 * Ends with `-`
	 *
	 * $regex = '/\/([a-z\-]{3,36})-/';
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageNameStr( $str )
	{
		if ( strlen( $str ) > 3 )
		{
			$regex = '/\/([a-z\-]{3,36})--/';
			preg_match( $regex, $str, $match );
			if ( ! empty( $match[1] ) )
			{
				return $match[1];
			}
			else
			{
				return "Not available";
			}
		}
	}

	/**
	 * Get the Image Name
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageName( $str )
	{
		if ( $str = $this->getImageNameStr( $str ) )
		{
			$name = str_replace( '-', ' ', $str );
			$name = strtoupper( $name );
		}
		else
		{
			$name = "Not available";
		}
		return $name;
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
		if ( isset( $args['dir'] ) || isset( $args['self'] ) )
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
