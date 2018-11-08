<?php
/**
 * EC01 XML Reader
 *
 * Reads an XML file in the directory in which it is placed and displays it as
 * valid HTML. Can also be used as a WordPress plugin.
 *
 * @package Earth3300\EC01
 * @version 0.0.1
 * @author Clarence J. Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence J. Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL v3.0
 * @link https://github.com/earth3300/ec01-xml-reader
 *
 * @wordpress-plugin
 * Plugin Name: EC01 XML Reader
 * Plugin URI:  https://github.com/earth3300/ec01-xml-reader
 * Description: Reads and XML file and displays it in HTML.  Shortcode [xml-reader dir=""].
 * Version: 0.0.1
 * Author: Clarence J. Bos
 * Author URI: https://github.com/earth3300/
 * Text Domain: ec01-xml-reader
 * License:  GPL v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Standards: https://semver.org/  Versioning
 * Standards: https://www.php-fig.org/psr/  PHP Formatting
 * Standards: http://docs.phpdoc.org/references/phpdoc/tags/ Documentation.
 *
 * File: index.php
 * Created: 2018-10-07
 * Updated: 2018-11-08
 * Time: 10:13 EST
 */

namespace Earth3300\EC01;

/**
* Reads an XML file and displays it in HTML.
 *
 * The environment switch is found at the bottom of this file.
 */
class XMLReader
{

	/** @var array Default options. */
	protected $opts = [
		'max_files' => 1,
		'max_length' => 1000*10,
    'type' => 'xml',
    'ext' => '.xml',
		'dir' => '/data',
		'index' => false,
		'file' => 'data.xml', //set to empty to read directory contents.
		'title' => 'XML Reader',
    'css' => '/0/theme/css/style.css',
		'url' => 'https://github.com/earth3300/ec01-xml-reader',
		'msg' => [
			'success' => 'Success',
			'not_available' => 'Not Available',
			'error' => 'Error',
		],
	];

	/**
	 * Gets XML File and Return as HTML
	 *
	 * Allow only XML. Possibly use the MIME type.
	 *
	 * @link https://developer.mozilla.org/en-US/docs/Web/HTTP/Basics_of_HTTP/MIME_types
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function get( $args = null )
	{
		/** Figure out what is happening, set the switches accordingly. */
		$args = $this->setTheSwitches( $args );

		{

			/** Set the page class to the type. */
			$args['class'] = $this->opts['type'];

			/** Open the article element. */
			$str = '<article>' . PHP_EOL;

			/** Get the match pattern for the files. */
			if ( $match = $this->getMatchPattern( $this->opts['ext'], $args ) )
				{
					/** This will look only for the first file if max == 1. */
					$str .= $this->iterateFiles( $match, $args );
				}
			}

			/** Close the article element. */
			$str .= '</article>' . PHP_EOL;

			/** If the request is for a full page, wrap the HTML in page HTML. */
			if ( isset( $args['doctype'] ) && $args['doctype'] )
			{
				/** Note the lack of a preceding '.' before the equals sign. Important!!! */
				$str = $this->getPageHtml( $str, $args );
			}

			/** Deliver the HTML, wrapped in page HTML or not. */
			return $str;
	}

	/**
	 * Iterate over files.
	 *
	 * Capability for jpg, png, mp3 and mp4
	 *
	 * @param string $match
	 * @param array $args
	 *
	 * @return string
	 */
	private function iterateFiles( $match, $args )
	{
		/** Initialize the string variable. */
		$str = '';

		/** Set the counter to 0. */
		$cnt = 0;

		/** Use the PHP 'glob' function to iterate through the files that match the pattern. */
		foreach ( glob( $match ) as $file )
		{

			/** Increment the counter by one. */
			$cnt++;

			/** Stop processing if the count equals the maximum. */
			if ( $cnt > $this->opts['max_length'] )
			{
				break;
			}

			/** Add the file to the argument array. */
			$args['file'] = $file;

			/** Get the base path of the file, including the file name. */
			$args['src'] = $this->getSrcFromFile( $args['file'] );

			/** Get the item HTML. */
			$str .= $this->getItemHtml( $args );
		}
		return $str;
	}

	/**
	 * Get the source from the file, checking for a preceding slash.
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	private function getSrcFromFile( $str )
	{
		/** Remove the part of the path that is before the site root. */
		$src = str_replace( $this->getSitePath(), '', $str );

		/** Just in case, we remove the preceding slash and add it again. */
		$src = ltrim( $src, '/' );

		/** Return the file src with the preceding slash. */
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
	 * Build the Match String
	 *
	 * Allowing only `xml` here.
	 *
	 * @param string $type  'xml'
	 * @param array $args
	 *
	 * @return string|false
	 */
	private function getMatchPattern( $ext, $args )
	{
		if ( '.xml' == $ext ) {

			/** Get the base path (from the root of the server. */
			$path = $this->getBasePath( $args );

			/** Add a wild card to allow for any file name (not including the extension). */
			$prefix = "/*";

			/** Built the match string. */
			$match =  $path . $prefix . $ext;

			/** Ensure it is non-trivial. */
			if ( strlen( $match ) > 10 )
			{
				return $match;
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Get the Base Path to the Media Directory.
	 *
	 * This does not need to include the `/media` directory.
	 *
	 * @param array $args
	 *
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
		/** Assume the current directory if no other directives. */
		else
		{
			$path = __DIR__;
		}
		return $path;
	}

	/**
	 * Get the Working Directory
	 *
	 * @param array $args
	 *
	 * @return string
	 *
	 * @example $args['dir'] = '/my/directory/'
	 */
	private function getWorkingDir( $args )
	{
		if ( isset( $args['dir'] ) )
		{
			$dir = $args['dir'];
		}
		else
		{
			$dir = $this->opts['dir'];
		}
		return $dir;
	}

	/**
	 * Get the Item HTML
	 *
	 *  string file_get_contents (
	 *  	string $filename [,
	 *  	bool $use_include_path = FALSE [,
	 *  	resource $context [,
	 *  	int $offset = 0 [,
	 *  	int $maxlen ]]]]
	 *  	)
	 *
	 * file_get_contents( $file, true, null, 0, $maxlen );
	 *
	 * This function is similar to file(),
	 * except that file_get_contents() returns the file in a string,
	 * starting at the specified offset up to maxlen bytes.
	 * On failure, file_get_contents() will return FALSE.
	 * file_get_contents() is the preferred way to read the contents of a file
	 * into a string. It will use memory mapping techniques if supported by your
	 * OS to enhance performance.
	 *
	 * @param array $args
	 *
	 * @return string|bool
	 */
	private function getItemHTML( $args )
	{
		/** Check if the file exists. */
		if ( file_exists( $args['file'] ) )
		{
			/** Read the contents into a string. */
			$str = file_get_contents( $args['file'] );

			if ( $xml = $this->strToXML( $str ) )
			{
				/** Convert the xml to an array */
				$arr = $this->xmlToArray( $xml );

				/** Convert the array into HTML */
				if( $html = $this->arrToHTML( $arr ) )
				{
					return $html;
				}
				else
				{
					return false;
				}
			}
			else
			{
				return false;
			}
		}
		else
		{
			return false;
		}
	}

	/**
	 * Convert an Associative Array to HTML
	 *
	 * @param array $array
	 *
	 * @return string|bool
	 *
	 * ["name"]=> string(12) "Peterborough"
   * ["lat"]=> string(9) "44.306805"
   * ["lng"]=> string(11) "-78.3201927"
   * ["width"]=> string(4) "8.88"
   * ["height"]=> string(4) "9.25"
   * ["area"] => string(5) "70.82"
   * ["radius"]=> string(4) "4300"
   * ["people"]=> string(6) "116570"
	 */
	private function arrToHTML( $arr )
	{
		if ( is_array( $arr ) && ! empty( $arr ) )
		{
			if ( 0 ) {
			echo "<pre>";
			echo "</pre>"; }
			var_dump( $arr[ key( $arr ) ] );

			$data = $arr[ key( $arr ) ];

			foreach ( $data as $key => $row )
			{

			}
			/** loop through array up to n depth */

		}
		else
		{
			return false;
		}
	}

	private function twoDeeArrToHTML( $data )
	{

    $rows = [];

    foreach ($data as $row)
		{
        $cells = array();

        foreach ($row as $cell)
				{
            $cells[] = "<td>{$cell}</td>";
        }

        $rows[] = "<tr>" . implode( '', $cells ) . "</tr>" . PHP_EOL;
    }

    return "<table>" . implode( '', $rows ) . "</table>";
	}

/*
 * $data = array(
    array('1' => '1', '2' => '2'),
    array('1' => '111', '2' => '222'),
    array('1' => '111111', '2' => '222222'),
    );
		echo html_table($data);
	}
	*/

	/**
	 * Convert an XML String to an XML Object
	 *
	 * simplexml_load_string (
	 * 		string $data [,
	 * 		string $class_name = "SimpleXMLElement" [,
	 * 		int $options = 0 [,
	 * 		string $ns = "" [,
	 * 		bool $is_prefix = FALSE ]]]]
	 * 		)
	 * @link http://php.net/manual/en/function.simplexml-load-string.php
	 *
	 * @param string $str
	 *
	 * @return object|bool
	 */
	 private function strToXML( $str )
	 {
		 /** Convert a well-formed xml string into an xml object. */
		 $xml = simplexml_load_string( $str, "SimpleXMLElement", LIBXML_NOCDATA );

		 /** Check the returned value. */
		 if ( $xml !== false )
		 {
			 return $xml;
		 }
		 else
		 {
			 return false;
		 }

	 }
	/**
	 * Convert String to XML to JSON to Array
	 *
	 * json_encode (
		 * 	mixed $value [,
		 * 	int $options = 0 [,
		 * 	int $depth = 512 ]]
	 * 	)
	 * @link http://php.net/manual/en/function.json-encode.php
	 *
	 * json_decode (
		 * 	string $json [,
		 * 	bool $assoc = FALSE [,
		 * 	int $depth = 512 [,
		 * 	int $options = 0 ]]]
	 * 	)
	 * 	@link http://php.net/manual/en/function.json-decode.php
	 *
	 *  @param string $str
	 *
	 *  @return array|bool
	 */
	private function xmlToArray( $xml )
	{
			/** Encode the xml as JSON */
			$json = json_encode( $xml );

			/** Decode the $json into an array */
			$arr = json_decode( $json, true );

			/** Basic check */
			if ( is_array( $arr ) )
			{
				/** Return the array. */
				return $arr;
			}
			else
			{
				return false;
			}
	}

	/**
	 * Set the Switches
	 *
	 * If $args['self'] or $args['dir'] are not set, it assumes we are in the
	 * working directory. Therefore, $args['self'] is set to true and $args['dir']
	 * is set to null. We also have to set the
	 * $args['doctype'] to true to know whether or not to wrap the output in
	 * the correct doctype and the containing html and body elements.
	 *
	 * @param array $args
	 *
	 * @return array
	 */
	private function setTheSwitches( $args )
	{
		/** Set the working directory to what is provided, or false.  */
		$args['dir'] = isset( $args['dir'] ) ? $args['dir'] : false;

		/** Set the working directory switch to false. */
		$args['self'] = false;

		/** Set the doctype switch to false. */
		$args['doctype'] = false;

		/** if $args['dir'] == false, set $args['self'] to true. */
		if ( ! $args['dir'] )
		{
			/** Obtain files from the directory in which this file is placed. */
			$args['self'] = true;

			/** Wrap the HTML generated here in page HTML. */
			$args['doctype'] = true;
		}

			/** Return the argument array. */
			return $args;
	}

  /**
	 * Embed the provided HTML in a Valid HTML Page
	 *
	 * Uses the HTML5 DOCTYPE (`<!DOCTYPE html>`), the UTF-8 charset, sets the
	 * initial viewport for mobile devices, disallows robot indexing (by default),
	 * and references a single stylesheet.
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	public function getPageHtml( $html, $args )
	{
		$str = '<!DOCTYPE html>' . PHP_EOL;
		$str .= sprintf( '<html class="dynamic theme-dark %s" lang="en-CA">%s', $args['class'], PHP_EOL );
		$str .= '<head>' . PHP_EOL;
		$str .= '<meta charset="UTF-8">' . PHP_EOL;
		$str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
		$str .= sprintf( '<title>%s</title>%s', $this->opts['title'], PHP_EOL);
		$str .= $this->opts['index'] ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
		$str .= sprintf('<link rel=stylesheet href="%s">%s', $this->opts['css'], PHP_EOL);
		$str .= '</head>' . PHP_EOL;
		$str .= '<body>' . PHP_EOL;
		$str .= '<main>' . PHP_EOL;
		$str .= $html;
		$str .= '</main>' . PHP_EOL;
		$str .= '<footer>' . PHP_EOL;
		$str .= '<div class="text-center"><small>';
		$str .= sprintf( 'Note: This page has been <a href="%s">automatically generated</a>. No header, footer, menus or sidebars are available.', $this->opts['url'] );
		$str .= '</small></div>' . PHP_EOL;
		$str .= '</footer>' . PHP_EOL;
		$str .= '</html>' . PHP_EOL;

		return $str;
	}

} // End class

/**
 * Callback from the xml-reader Shortcode
 *
 * Performs a check, then instantiates the XMLReader class
 * and returns the xml file found (if any) as HTML.
 *
 * @param array  $args['dir']
 *
 * @return string  HTML as a list of images, wrapped in the article element.
 */
function media_index( $args )
{
	if ( is_array( $args ) )
	{
		$xml_reader = new XMLReader();
		return $xml_reader->get( $args );
	}
	else
	{
		return '<!-- Missing the image directory to process. [xml-reader dir=""]-->';
	}
}

/**
 * Environment Check.
 *
 * In WordPress if a WordPress specific function is found ('add_shortcode' is
 * the one we need, so that is the one a  check is made for).
 */
if( function_exists( 'add_shortcode' ) )
{
	/** No direct access (NDA). */
	defined('ABSPATH') || exit('NDA');

	/** Add shortcode [xml-reader dir=""] */
	add_shortcode( 'xml-reader', 'xml_reader' );
}
/**
 * Else Instantiate the Class Directly (not in WordPress)
 *
 * It could be that this file is within another framework. But as we don't know
 * what that is here, we can't make use of it.
 *
 * @return string
 */
else
{
	$xml_reader = new XMLReader();
	echo $xml_reader->get();
}
