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
 * Updated: 2018-11-20
 * Time: 11:14 EST
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

    /** Get the form HTML. */
    $file['html'] .= $this->getForm( $file );

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
   * Get the Form
   *
   */
  private function getForm()
  {
    $template = new FormTemplate();
    return $template->form();
  }

  /**
   * Get the Nonce
   *
   */
  protected function getNonce()
  {
    $referer = 'self';
    $random = rand();
    $nonce = hash( 'sha512', $random );

    // Store nonce

    $str = sprintf(
      '<input type="hidden" referer="%s" nonce="%s" />%s',
      $referer, $nonce, PHP_EOL );

    return $str;
  }

  /**
   *  Get Javascript
   *
   * @param  array $file
   * @param  array $args
   *
   * @return string
   */
  private function getJavascript( $file, $args )
  {
    $str = '<script>' . PHP_EOL;
    $str .= '(function($) {' . PHP_EOL;
    $str .= 'function enableSubmit() {' . PHP_EOL;
    $str .= '        $("#form-submit").prop(\'disabled\', false );' . PHP_EOL;
    $str .= '    }' . PHP_EOL;
    $str .= '    setTimeout(enableSubmit, 5000)' . PHP_EOL;
    $str .= '})(jQuery);' . PHP_EOL;
    $str .= '</script>' . PHP_EOL;
    return $str;
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
    $str .= $this->getJavascript( $file, $args );
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

} // End FormWriter

/**
 * Class FormTemplate
 *
 */
class FormTemplate extends FormWriter
{
  /**
   * Form
   *
   * @return string
   */
  public function form()
  {
      $form = $this->getFormData();

      $response = $this->process();

      /** Wrap the form in a div. */
      $str = '<div class="form">' . PHP_EOL;

      /** Open the form. */
      $str .= sprintf( '<form action="" method="post">%s', PHP_EOL );

      /** Add a "use once" field to help prevent misuse. */
      $str .= $this->getNonce();

      /** Cycle through the fields */
      foreach ( $form['form'] as $item )
      {
        if ( $item['load'] )
        {
          $required = $item['required'] ? 'required' : '';

          $placeholder = ! empty( $item['placeholder'] ) ? 'placeholder="%s"' : '';

          switch( $item['element'] )
          {
            case 'input' :
              $str .= $this->getInput( $item );
              break;
            case 'select' :
              $str .= $this->getSelect( $item );
              break;
            case 'textarea' :
              $str .= $this->getTextArea( $item );
              break;
            default:
          }
        }
      }

      /** A hidden field to attract those anxious bots. */
      $str .= '<input type="hidden" name="form_best_time" maxlength="40"';
      $str .= ' placeholder="Best time to call..." value="" />' . PHP_EOL;

      /** Submit button. This is disabled for a few seconds to prevent anxious bots from using it. */
      if ( $form['meta']['submit']['load'] )
      {
        $str .= '<button';
        $str .= ' id="form-submit"';
        $str .= ' type="submit"';
        $str .= ' class="button button-primary"';
        $str .= $form['meta']['submit']['disabled'] ? ' disabled="true"' : '';
        $str .= '>';
        $str .= $form['meta']['submit']['title'];
        $str .= '</button>' . PHP_EOL;
      }

      /** Form response div. */
      $str .= sprintf('<div class="form-response">%s</div>%s', $response['form_response'], PHP_EOL );

      /** Close the form. */
      $str .= '</form>' . PHP_EOL;

      /** Close the form div */
      $str .= '</div><!-- .form -->' . PHP_EOL;

      /** Return the form. */
      return $str;
  }

  /**
   * Get the Input Element
   *
   * @param array $item
   *
   * @return string|bool
   */
  private function getInput( $item )
  {
    /** Check to ensure the item is an array and it has at least one required key. */
    if ( is_array( $item ) && isset( $item['type'] ) )
    {
      /** Open the label element. */
      $str = '<label';

      /** Indicate for which form id it is.  */
      $str .= sprintf( ' for="%s">', $item['name'] );

      /** Provide the label text. */
      $str .= $item['title'];

      /** Close the label element. */
      $str .= '</label>' . PHP_EOL;

      /** Opent the input tag. */
      $str .= '<input';

      /** Set the type (i.e. text, email, etc). */
      $str .= sprintf( ' type="%s"', $item['type'] );

      /** Set the id (name). */
      $str .= sprintf( ' id="%s"', $item['name'] );

      /** Set the name (name). */
      $str .= sprintf( ' name="form_%s"', $item['name'] );

      /** Set the min length (if at all). */
      $str .= sprintf( ' minlength="%s"', $item['length']['min'] );

      /** Set the max length (if at all). */
      $str .= sprintf( ' maxlength="%s"', $item['length']['max'] );

      /** Set the size (if at all). */
      $str .= sprintf( ' size="%s"', $item['size'] );

      /** Required (or not). */
      $str .= $item['required'] ? ' required' : '';;

      /** Close the input tag. */
      $str .= ' />' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get the Text Area Element
   *
   * @param array $item
   *
   * @return string|bool
   */
  private function getTextArea( $item )
  {
    /** Check to see if the item is an array and if at least one key is set. */
    if ( is_array( $item ) && isset( $item['name'] ) )
    {
      /** Open the label element. */
      $str = '<label';

      /** Indicate for which form id it is.  */
      $str .= sprintf( ' for="%s">', $item['name'] );

      /** Provide the label text. */
      $str .= $item['title'];

      /** Close the label element. */
      $str .= '</label>' . PHP_EOL;

      /** Open the textarea element. */
      $str .= '<textarea';

      /** Add the name of the form. */
      $str .= sprintf( ' name="form_%s"', $item['name'] );

      /** Specify the minimum length acceptable. */
      $str .= sprintf( ' minlength="%s"', $item['length']['min'] );

      /** Specify the maximum length acceptable. */
      $str .= sprintf( ' maxlength="%s"', $item['length']['max'] );

      /** Whether or not the element is required. */
      $str .= $item['required'] ? ' required' : '';

      /** Close the opening tag of the textarea element. */
      $str .= '>' . PHP_EOL;

      /** Add the placeholder, *if* present. */
      $str .= $item['placeholder'] . PHP_EOL;

      /** Close the textarea element. */
      $str .= '</textarea>' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    /** If nothing there, return false. */
    else
    {
      return false;
    }
  }

  /**
   * Get the Select Element
   *
   * @return string|bool
   */
  private function getSelect()
  {
    /** Get the Select data. */
    $data = new FormData();

    /** Get the select element data. */
    $select = $data->select();

    /** Get the options data. */
    $options = $data->options();

    /** Check to see if we have arrays and that they have what we need. */
    if ( is_array( $select ) && count ( $select ) > 0
      && is_array( $options ) && count ( $options ) > 0 )
    {
      /** Open the label element. */
      $str = '<label';

      /** Indicate for which form element id it is.  */
      $str .= sprintf( ' for="%s">', $select['name'] );

      /** Provide the label text. */
      $str .= $select['name'];

      /** Close the label element. */
      $str .= '</label>' . PHP_EOL;

      /** Open the select element. */
      $str = '<select';

      /** Don't forget the leading blank space before each attribute. */

      /** Set the id (for use with label and accessibility purposes). */
      $str .= sprintf( ' id="%s"', $select['name'] );

      /** Set the name (used when posting the form). */
      $str .= sprintf( ' name="form_%s"', $select['name'] );

      /** Set the size of the drop down. */
      $str .= sprintf( ' size="%s"', $select['size'] );

      /** Allow multiple items to be selected (or not). */
      $str .= $select['multiple'] ? ' multiple' : '';

      /** Close the select tag. */
      $str .= '>' . PHP_EOL;

      /** Cycle through the options. */
      foreach ( $options as $option )
      {
        /** If the item is a default, set it to default. */
        if ( $option['default'] )
        {
          $str .= sprintf( '<option value="%s" selected>%s</option>%s', $option['value'], $option['title'], PHP_EOL );
        }
        /** Else the item is not a default. */
        else
        {
          $str .= sprintf( '<option value="%s">%s</option>%s', $option['value'], $option['title'], PHP_EOL );
        }
      }
      /** Close the select element. */
      $str .= '</select>' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    /** If nothing is there, return false. */
    else
    {
      return false;
    }
  }

  /**
   * Get Form Data
   */
  private function getFormData()
  {
    $data = new FormData();
    $items['form'] = $data->form();
    $items['meta'] = $data->meta();
    return $items;
  }

  /**
   * Process the Form
   *
   * @return bool
   */
  private function process()
  {
    return true;
  }

} // End FormTemplate

/**
 * Form Data
 *
 * @var [type]
 */
class FormData extends FormWriter
{
  /**
   *  Form
   *
   * @return array
   */
  public function form() {
    $items = [
        [
          'element' => 'input',
          'name' => 'name',
          'type' => 'text',
          'title' => 'Your Name (required)',
          'length' => [ 'min' => 4, 'max' => 40 ],
          'required' => 1,
          'load' => 1,
        ],
        [
          'element' => 'input',
          'name' => 'email',
          'type' => 'email',
          'title' => 'Your Email (required)',
          'length' => [ 'min' => 4, 'max' => 40 ],
          'required' => 1,
          'load' => 1,
        ],
        [
          'element' => 'input',
          'name' => 'subject',
          'type' => 'text',
          'title' => 'Subject',
          'length' => [ 'min' => 4, 'max' => 200 ],
          'required' => 0,
          'load' => 1,
        ],
        [
          'element' => 'textarea',
          'name' => 'message',
          'type' => 'textarea',
          'title' => 'Your Message',
          'required' => 0,
          'length' => [ 'min' => 4, 'max' => 400 ],
          'load' => 1,
        ],
        [
          'element' => 'select',
          'name' => 'select',
          'type' => 'select',
          'title' => 'Select',
          'size' => 4,
          'required' => 1,
          'load' => 1,
        ],
    ];
    return $items;
  }

  /**
   * Meta
   *
   * @return array
   */
  public function meta()
  {
    $items =
    [
      'submit' =>
        [
          'title' => 'Send',
          'disabled' => 0,
          'load' => 1,
        ],
      'success' =>
        [
          'text' => 'Your message was sent succesfully.',
          'load' => 1,
        ],
      'failure' =>
        [
          'text' => 'There was an error sending your message.',
          'load' => 1,
        ],
    ];
      return $items;
  }

  public function select()
  {
    $items = [
      'element' => 'select',
      'name' => 'select',
      'title' => 'Select',
      'size' => 1,
      'multiple' => false,
    ];
    return $items;
  }

  public function options()
  {
    $items = [
      [ 'value' => 'abc', 'title' => 'ABC', 'load' => 1, ],
      [ 'value' => 'def', 'title' => 'DEF', 'load' => 1, ],
      [ 'value' => 'ghi', 'title' => 'GHI', 'load' => 1, ],
      [ 'value' => 'jkl', 'title' => 'JKL', 'load' => 1, ],
      [ 'value' => 'mno', 'title' => 'MNO', 'load' => 1, ],
      [ 'value' => 'pqr', 'title' => 'PQR', 'load' => 1, ],
      [ 'value' => 'stu', 'title' => 'STU', 'load' => 1, ],
      [ 'value' => 'vwz', 'title' => 'VWZ', 'load' => 1, ],
    ];
    return $items;
  }
} // End FormData

/**
 * Helper Function
 *
 * Call `pre_dump( $arr );` to have array or string outputted and formatted
 * for debugging purposes. A check is done to ensure this function is not
 * called twice.
 */
if ( ! function_exists( 'pre_dump' ) )
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
