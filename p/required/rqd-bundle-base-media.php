<?php

defined('NDA') || exit('No direct access.');

/*
Plugin Name: Required Bundle Base Media
Plugin URI: http://wp.cbos.ca/plugins/rqd-bundle-base-media/
Description: Better media handling.
Version: 2018.09.11
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca/
License: GPLv2+
*/

/**
 * Base Media Required Logic and Functionality
 *
 * Ideally an abstract class will be used here. However, we are practicing
 * using object oriented programming and the various methods of calling an action
 * hook. Here we are keep the action hook on the *outside* of the class to improve
 * portability for this class. That means, if we do our job right, we should also
 * be able to use it outside of WordPress. Woohoo!!!
 *
 * Now that we have it working (basically) we can add a shortcode to enable a display of all
 * images in a directory up to a certain limit (say 100).
 */

/**
 * Place the action hook outside of the class (decouple). Version II.
 *
 * No action hook is *inside* the class. This should result in the greatest portability.
 */
class BaseMedia
{
	/**
	 * Loads all images in the specified directory and displays them in valid HTML.
	 */
	public function on_loaded()
	{
		//SITE_MEDIA_PATH
	}

	/**
	 * Use the PHP glob function to list files (of a certain type).
	 *
	 * @example glob( SITE_MEDIA_PATH . '/*.jpg')
	 */
	public function mediaGlob()
	{
		$max = 5;

		$root = SITE_CDN_PATH;
		$sub = '/cluster/applied/architecture/shelter/micro-cabin/image';
		$pattern = "/*.jpg";

		$match =  $root . $sub . $pattern;

		$str = '<article>' . PHP_EOL;
		$cnt = 0;
		foreach ( glob( $match  ) as $file )
		{
			$cnt++;
			if ( $cnt > $max )
			{
				break;
			}
			/** search, replace, subject */
			$src = str_replace( SITE_PATH, '', $file );
			$str .= sprintf('<a href="%s"><img src="%s" width="500" height="auto" alt="Image" class="generic" /></a>%s', SITE_URL . $src, $src, PHP_EOL );
		}
		$str .= '</article>' . PHP_EOL;
		echo $str;
	}

	/**
	 * List files recursively.
	 *
	 * @see http://php.net/manual/en/function.glob.php#82455
	 */
	private function listFiles( $path )
	{
		$files = array();

		if(is_dir($path))
		{
			if($handle = opendir($path))
			{
				while(($name = readdir($handle)) !== false)
				{
					if(!preg_match("#^\.#", $name))
					if(is_dir($path . "/" . $name))
					{
						$files[$name] = list_files($path . "/" . $name);
					}
					else
					{
						$files[] = $name;
					}
				}

				closedir($handle);
			}
		}

		return $files;
	}

	private function dirIterator( $path )
	{
		$dir_iterator = new RecursiveDirectoryIterator($path);
		$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
		// could use CHILD_FIRST if you so wish

		foreach ($iterator as $file) {
			echo $file, "\n";
		}
	}

	/**
	 * Get all files of a type (image) and output in valid HTML
	 *
	 * @see http://php.net/manual/en/function.glob.php#92710
	 */
	private function listImage()
	{
		$dir_iterator = new RecursiveDirectoryIterator($path);
		$iterator = new RecursiveIteratorIterator($dir_iterator, RecursiveIteratorIterator::SELF_FIRST);
		$size = 0;
		foreach ($iterator as $file) {
			if ($file->isFile()) {
				echo substr($file->getPathname(), 27) . ": " . $file->getSize() . " B; modified " . date("Y-m-d", $file->getMTime()) . "\n";
				$size += $file->getSize();
			}
		}
		echo "\nTotal file size: ", $size, " bytes\n";
	}
}

/**
 * If the SITE constant is defined, we assume all other related constants are defined.
 *
 * @see `/c/config/site/cfg-structure.php` for a list of these contants and their values.
 */
if ( 0 ) {
	if( defined('SITE') )
	{
		{
		$base_media = new BaseMedia();
		//add_action('wp_loaded', array($base_media, 'mediaGlob'));
		add_shortcode('glob-glob', array($base_media, 'mediaGlob'));
		}
	}
	else
	{
		exit('SITE_ configuration paramaters are required for this to work.');
	}
}
