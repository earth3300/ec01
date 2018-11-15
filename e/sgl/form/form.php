<?php
/**
 * EC01 Form Writer
 *
 * Creates a form in HTML, accepts a form submission, validates it, then writes
 * the validated data to a secured JSON file. This file can then be read at
 * the convenience of the main site API into the database, as needed. This file
 * will then run alone, apart from any frameworks (Joomla!, Drupal, WordPress,
 * Concrete5), but can also be used as a WordPress plugin (not tested with any
 * other frameworks).
 *
 * @package Earth3300\EC01
 * @version 0.0.1
 * @author Clarence J. Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence J. Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL v3.0
 * @link https://github.com/earth3300/ec01-form-writer
 *
 * @wordpress-plugin
 * Plugin Name: EC01 Form Writer
 * Plugin URI:  https://github.com/earth3300/ec01-form-writer
 * Description: Displays a form, accepts a form submission, validates the data, then stores the validated data as a secured JSON file.
 * Version: 0.0.1
 * Author: Clarence J. Bos
 * Author URI: https://github.com/earth3300/
 * Text Domain: ec01-form-writer
 * License:  GPL v3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.en.html
 *
 * Standards: https://semver.org/  Versioning
 * Standards: https://www.php-fig.org/psr/  PHP Formatting
 * Standards: http://docs.phpdoc.org/references/phpdoc/tags/  Documentation.
 *
 * Standards: https://tools.ietf.org/html/rfc7159  JSON
 *
 * File: form.php
 * Created: 2018-10-15
 * Updated: 2018-11-15
 * Time: 11:42 EST
 */

namespace Earth3300\EC01;

/**
* Displays a HTML Form and Writes Validated Data to a Secured File.
 *
 * The environment switch is found at the bottom of this file.
 */
class FormWriter
{

	/** @var array Default options. */
	protected $opts = [
		'max_files' => 1,
		'max_length' => 1000*10,
    'type' => 'json',
    'ext' => '.json',
		'dir' => '/data',
		'index' => false,
		'file' => 'form.json',
		'title' => 'Form Writer',
    'css' => '/0/theme/css/style.css',
		'url' => 'https://github.com/earth3300/ec01-form-writer',
		'msg' => [
			'success' => 'Success',
			'not_available' => 'Not Available',
			'error' => 'Error',
		],
    'footer' => true,
	];

	/**
	 * Displays an HTML form.
	 *
	 * @param array $args
	 *
	 * @return string
	 */
	public function get( $args = null )
	{
		/** Figure out what is happening, set the switches accordingly. */
		$args = $this->setTheSwitches( $args );

		/** Set the page class to the type. */
		$args['class'] = $this->opts['type'];

    /** Add the file to the argument array. */
    $file['path'] = $this->getFilePath( $args ) . '/' .$this->opts['file'];

    /** Get the name of the containing directory. */
    $file['dir'] = basename(__DIR__);

    /** Construct the file name out of the file directory and its extension. */
    $file['name'] = $file['dir'] . $this->opts['ext'];

    /** Get the base path of the file, including the file name. */
    $file['src'] = $this->getSrcFromFile( $file );

		/** Open the article element. */
		$file['html'] = '<article>' . PHP_EOL;

    /** Get the item JSON. */
    $file['json'] .= $this->getItemJSON( $file );

    /** Get the item array from JSON */
    $file['arr'] .= $this->getItemArr( $file );

    /** Get the item HTML. */
    $file['html'] .= $this->getItemHtml( $file );

		/** Close the article element. */
		$file['html'] .= '</article>' . PHP_EOL;

		/** If the request is for a full page, wrap the HTML in page HTML. */
		if ( isset( $args['doctype'] ) && $args['doctype'] )
		{
			/** Note the lack of a preceding '.' before the equals sign. Important!!! */
			$str = $this->getPageHtml( $file, $args );
		}

		/** Deliver the HTML, wrapped in page HTML or not. */
		return $str;
	}

	/**
	 * Get the source from the file, checking for a preceding slash.
	 *
	 * @param string $str
	 *
	 * @return string
	 */
	private function getSrcFromFile( $file )
	{

		/** Remove the part of the path that is before the site root. */
		$src = str_replace( $this->getSitePath(), '', $file['path'] );

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
	 * @return string
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
   * Get the File Path
   *
   * @param array $file
   * @param array $args
   *
   * @return string
   */
   private function getFilePath( $args )
   {
     if ( $args['self'] )
     {
       /** The path is the path to this directory. */
       $path = __DIR__;
     }
     else
     {
       /** The 'dir' is from the base of the site. */
       $path = $this->getSitePath() . '/' . $args['dir'];
     }

     return $path;
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
   * Get Item JSON

   * file_get_contents() is the preferred way to read the contents of a file
	 * into a string. It will use memory mapping techniques if supported by your
	 * OS to enhance performance.
	 *
	 * @param array $args
	 *
	 * @return string|bool
	 */
	private function getItemJSON( $file )
	{
    /** Initialize the $json object to null. */
    $json = null;

  	/** Check if the file exists. */
		if ( file_exists( $file['path'] ) )
		{
			/** Read the contents into a string. */
			$str = file_get_contents( $file['path'] );

      /** OR. */
      $json = simplejson_load_file( $file['path'] );

      /** If the string is non-trivial */
      if ( strlen( $str ) > 4 )
      {
          /**  Convert the JSON sting to an JSON Object */
			    $json = $this->strToJSON( $str );
      }
    }
    return $json;
  }

	/**
	 * Get the Item HTML (From an Array)
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
	private function getItemHTML( $file )
	{
    $html = '';

    $rows = [];

    foreach ( $file['data'] as $row )
    {
        $cells = array();

        foreach ($row as $cell)
        {
            $cells[] = "<td>{$cell}</td>";
        }

        $rows[] = "<tr>" . implode( '', $cells ) . "</tr>" . PHP_EOL;
    }

    $html = "<table>" . implode( '', $rows ) . "</table>";

    return $html;
  }

	/**
	 * Convert an JSON String to an JSON Object
	 *
	 * simplejson_load_string (
	 * 		string $data [,
	 * 		string $class_name = "SimpleJSONElement" [,
	 * 		int $options = 0 [,
	 * 		string $ns = "" [,
	 * 		bool $is_prefix = FALSE ]]]]
	 * 		)
	 * @link http://php.net/manual/en/function.simplejson-load-string.php
	 *
	 * @param string $str
	 *
	 * @return object|bool
	 */
	 private function strToJSON( $str )
	 {
		 /** Convert a well-formed json string into an json object. */
		 $json = simplejson_load_string( $str, "SimpleJSONElement", LIBJSON_NOCDATA );

		 /** Check the returned value. */
		 if ( $json !== false )
		 {
			 return $json;
		 }
		 else
		 {
			 return false;
		 }

	 }
	/**
	 * Convert String to JSON to JSON to Array
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
	private function getItemArr( $file )
	{
			/** Encode the json as JSON */
			$json = json_encode( $file['json'] );

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
   * [jsonObjToArr description]
   *
   * @link http://php.net/manual/en/book.simplejson.php
   *
   * @param  object $obj JSON Object.
   * @param int $depth Maximum depth to process.
   *
   * @return array  array
   */
  private function jsonObjToArr( $obj, $depth = 4)
  {
    /** Initialize the count of the depth to process. */
    $cnt = 0;

    $namespace = $obj->getDocNamespaces( true );

    $namespace[null] = null;

    $children = [];

    $attr = [];

    $name = strtolower( (string)$obj->getName() );

    $text = trim( (string)$obj );

    if( strlen( $text ) <= 0 )
    {
        $text = null;
    }

    // get info for all namespaces
    if( is_object( $obj ) )
    {
      /** Increment the depth counter. */
      $cnt++;
      if ( $cnt > $depth )
      {
        foreach( $namespace as $ns => $nsUrl )
        {
          /** Attributes. */
          $objAttr = $obj->attributes( $ns, true );

          foreach( $objAttr as $attrName => $attrValue )
          {
              $attribName = strtolower( trim( (string)$attrName ) );

              $attribVal = trim( (string)$attrValue );

              if ( ! empty( $ns ) )
              {
                  $attribName = $ns . ':' . $attribName;
              }
              $attr[$attribName] = $attribVal;
          }

          /** Children. */
          $objChildren = $obj->children( $ns, true );

          foreach( $objChildren as $childName => $child )
          {
              $childName = strtolower( (string)$childName );

              if( ! empty( $ns ) )
              {
                  $childName = $ns.':'.$childName;
              }
              /** Call this function recursively. */
              $children[ $childName ][] = jsonObjToArr( $child );
          }
        }
      }
    }

    return array(
        'name'=>$name,
        'text'=>$text,
        'attr'=>$attr,
        'children'=>$children
    );
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
	public function getPageHtml( $file, $args )
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
		$str .= $file['html'];
		$str .= '</main>' . PHP_EOL;
    if ( $this->opts['footer'] )
    {
  		$str .= '<footer>' . PHP_EOL;
  		$str .= '<div class="text-center"><small>';
  		$str .= sprintf( 'Note: This page has been <a href="%s">automatically generated</a>. No header, footer, menus or sidebars are available.', $this->opts['url'] );
  		$str .= '</small></div>' . PHP_EOL;
  		$str .= '</footer>' . PHP_EOL;
    }
    $str .= '</html>' . PHP_EOL;

		return $str;
	}

} // End class

/**
 * Data Class
 *
 * @var [type]
 */
class FormData extends FormWriter
{
  protected function data()
  {
    $items = [

      ];
      return $items;
  }
}

/**
 * Helper Function
 *
 * Call `pre_dump( $arr );` to have array or string outputted and formatted
 * for debugging purposes. A check is done to ensure this function is not
 * called twice.
 */
if ( ! function_exists( 'pre_dump' )
{
  function pre_dump( $arr )
  {
    echo "<pre>" . PHP_EOL;
    var_dump( $arr );
    echo "</pre>" . PHP_EOL;
  }
}

/**
 * Callback from the form-writer Shortcode
 *
 * Performs a check, then instantiates the FormWriter class
 * and returns the json file found (if any) as HTML.
 *
 * @param array  $args['dir']
 *
 * @return string  HTML as a list of images, wrapped in the article element.
 */
function form_writer( $args )
{
	if ( is_array( $args ) )
	{
		$form_writer = new FormWriter();
		return $form_writer->get( $args );
	}
	else
	{
		return '<!-- Missing the directory to process. [form-writer dir=""]-->';
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

	/** Add shortcode [form-writer dir=""] */
	add_shortcode( 'form-writer', 'form_writer' );
}
/**
 * Else Instantiate the Class Directly (not in WordPress)
 *
 * It could be that this file is within another framework. But as we don't know
 * what that is here, we can't make use of it.
 *
 * @return string  Validated HTML.
 */
else
{
	$form_writer = new FormWriter();
	echo $form_writer->get();
}
