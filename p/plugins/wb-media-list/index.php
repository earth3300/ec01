<?php

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
	/**
	 * Outside of WordPress. Instantiate directly, assuming current directory.
	 *
	 * @param $args['self'] = true  List the files in the current directory.
	 *
	 * @param array $args['doctype'] = true  Set the doctype to html.
	 */
	$media_list = new MediaList();
	echo $media_list -> get( array( 'self' => true, 'doctype' => true ) );
}

/**
 * List the media of a certain type in a given directory.
 *
 * No WordPress hooks inside the class for better portability.
 */
class MediaList
{

	/**
	 * Default image dimensions
	 *
	 * array
	 */
	protected $opts = [
		'dim' => [ 'width' => 800, 'height' => 600 ],
		'max' => 12,
	];

	/**
	 * Get the list of images as HTML.
	 *
	 * @param array $args
	 * @return string
	 */
	public function get( $args )
	{
		if ( $this->checkArgs( $args ) )
		{
			$max = $this->getMaxImages( $args );
			$args['self'] = $this->isDirSelf( $args );
			$types = ['.jpg','.png'];

			$str = '<article>' . PHP_EOL;
			foreach ( $types as $type ) {
				if ( $match = $this->getMatchPattern( $type, $args ) )
				{
					$str .= $this->iterateFiles( $match, $max, $args );
				}
			}
			$str .= '</article>' . PHP_EOL;

			if ( isset( $args['doctype'] ) && $args['doctype'] )
			{
				$str = $this->getPageHtml( $str );
			}
			return $str;
		}
		else
		{
			return "Error.";
		}
	}

	/**
	 * Iterate over files
	 *
	 * @param string $match
	 * @param array $args
	 *
	 * @return string
	 */
	private function iterateFiles( $match, $max, $args )
	{
		$str = '';
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
			$args['src'] = $this->getSrcFromFile( $args['file'] );
			$args['name-dim'] = $this->getImageNameDimArr( $args['src'] );
			$args['name'] = $args['name-dim']['name'];
			$args['dim'] = $this->getImageDimArr( $args['name-dim']['strDim'] );
			$str .= $this->getImageHtml( $args );
		}
		return $str;
	}

	/**
	 * Get the source from the file, checking for a preceding slash.
	 *
	 * @param string $str
	 * @return string
	 */
	private function getSrcFromFile( $str )
	{
		$src = str_replace( $this->getSitePath(), '', $str );
		/** May be server inconsistency, therefore remove and add again. */
		$src = ltrim( $src, '/' );
		return '/' . $src;
	}

	/**
	 * Get the SITE_PATH
	 *
	 * Get the SITE_PATH from the constant, from ABSPATH (if loading within WordPress
	 * as a plugin), else from the $_SERVER['DOCUMENT_ROOT']
	 *
	 * Both of these have been tested online to have a preceding forward slash.
	 * Therefore do not add one later.
	 *
	 * @return bool
	 */
	private function getSitePath()
	{
		if ( defined( 'SITE_PATH' ) )
		{
			return SITE_PATH;
		}
		/** Available if loading within WordPress as a plugin. */
		elseif( defined( 'ABSPATH' ) )
		{
			return ABSPATH;
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
			$max = $this->opts['max'];
		}
		return $max;
	}

	/**
	 * Build the match string.
	 *
	 * This is iterated through for each type added to $types, above. A basic
	 * check for a reasonable string length (currently 10) is in place. Can
	 * develop this further, if needed.
	 *
	 * @param string $type  'jpg', 'png'
	 * @param array $args
	 *
	 * @return string|false
	 */
	private function getMatchPattern( $type, $args )
	{
		$path = $this->getBasePath( $args );
		$prefix = "/*";
		$match =  $path . $prefix . $type;

		/** Very basic check. Can improve, if needed. */
		if ( strlen( $match ) > 10 )
		{
			return $match;
		}
		else {
			return false;
		}
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
		$str .= sprintf( '<span class="name">%s</span>', $this->getImageName( $args['name'] ) );
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
		$str .= '<footer>' . PHP_EOL;
		$str .= '<div class="text-center"><small>';
		$str .= 'Note: This page has been automatically generated. No header, footer, menus or sidebars are available.';
		$str .= '</small></div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		$str .= '</html>' . PHP_EOL;

		return $str;
	}

	/**
	 * Get the Image Name and Dimensions from the String.
	 *
	 * Return the direct string values of each. Do no extra processing here.
	 *
	 * $regex = '\/([a-z\-]{3,60})-([0-9]{2,4}x[0-9]{2,4})'
	 *
	 * Although this looks complicated, it has potential to be helpful.
	 * Not only does it divide the string given it into two parts, but it
	 * also does a basic quality check on the image name structure. If the image
	 * name does not meet the criteria given, it won't be captured.
	 *
	 * @param string $str
	 *
	 * @return array  $arr['name'] $arr['dim']
	 *
	 * @example $arr['name'] = 'image-name'
	 * @example $arr['dim'] = '800x600'
	 */
	private function getImageNameDimArr( $str )
	{
		/**
		 * Since we won't have a valid image name with fewer than 13 characaters
		 * we won't bother processing anything with less than that length.
		 */
		if ( strlen( $str ) > 12 )
		{
			/** If this isn't matched, check for a name only */
			$regex = '/\/([a-z\-]{3,150})-([0-9]{2,4}x[0-9]{2,4})/';
			preg_match( $regex, $str, $match );

			if ( empty( $match ) )
			{
				$regex = '/\/([a-z,0-9\-]{5,150})\./';
				preg_match( $regex, $str, $match );
			}

			if ( ! empty( $match[1] ) )
			{
				$arr['name'] = $match[1];
			}
			else
			{
				$arr['name'] = null;

			}

			if ( ! empty( $match[2] ) )
			{
				$arr['strDim'] = $match[2];
			}
			else
			{
				$arr['strDim'] = null;

			}


			return $arr;
		}
		else {
			return false;
		}
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
	 * Get the image dimensions.
	 *
	 * Gets the image dimensions as the last part of the file name and only if
	 * it follows the format: ###x###, where # is an integer.
	 *
	 * @param string $str
	 * @return string
	 */
	private function getImageDimArr( $str )
	{

		if ( strlen( $str ) > 4 )
		{
			$arr = explode( 'x', $str );
			$dim['width'] = $arr[0];
			$dim['height'] = $arr[1];
			return $dim;
		}
		else
		{
			$dim['width'] = $this->opts['dim']['width'];
			$dim['height'] = $this->opts['dim']['height'];
			return $dim;
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
	 * Get the Image Name
	 *
	 * @param array $args
	 * @return string
	 */
	private function getImageName( $str )
	{
		if ( strlen( $str ) > 2 )
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
