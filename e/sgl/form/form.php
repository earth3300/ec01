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
 * Updated: 2018-11-24
 * Time: 19:20 EST
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
    'page' => [
      'title' => 'Form Writer',
      'robots' => [ 'index' => 0, ],
      'css' => [
        'load' => 1,
        'url' => '/0/theme/css/style.css',
      ],
      'footer' => [ 'load' => 0, ],
      'url' => 'https://github.com/earth3300/ec01-form-writer',
      'javascript' => [ 'load' => 1, ],
    ],
    'file' => [
      'max' => [ 'num' => 1, 'size' => 1000*10, ],
      'write' => [ 'name' => '.data.json', 'max' => 1000*10, ],
      'json' => [ 'name' => 'options.json' ],
      'type' => 'json',
      'ext' => '.json',
    ],
    'input' => [
        'text' => [ 'length' => [ 'min' => 4, 'max' => 40, ] ],
        'textarea' => [ 'length' => [ 'min' => 4, 'max' => 100, ] ],
        'characters' => [
          'check' => 1,
          'disallowed' => '~`!@#$%^&*()-_+{[}]\|;:<>?', ],
        'grammar' => [
          'check' => 1,
          'words' => 'and,the,a,an,i,you' ,
        ],
      ],
    'button' => [ 'delay' => [ 'ms' => 3000, ], ],
    'form' => [
      'prefix' => 'form',
      'referer' => [ 'load' => 1, ],
      'nonce' => [ 'load' => 1 ],
    ],
    'required' => [ 'text' => '(required)', ],
    'security' => [
      'check' => 1,
      'field' => 'form_best_time',
    ],
    'testing' => 1,
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
    $args['class'] = $this->opts['file']['type'];

    /** Add the file to the argument array. */
    $file['path'] = $this->getFilePath( $args ) . '/' .$this->opts['file']['json']['name'];

    /** Get the name of the containing directory. */
    $file['dir'] = basename(__DIR__);

    /** Construct the file name out of the file directory and its extension. */
    $file['name'] = $file['dir'] . $this->opts['file']['ext'];

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
   * This is a "use once" number.
   *
   * @return string
   */
  protected function getNonce()
  {
    $random = rand();
    $nonce = hash( 'sha512', $random );

    // Store nonce

    $str = '<input type="hidden"';
    $str .= sprintf( ' name="%s_nonce"', $this->opts['form']['prefix'] );
    $str .= sprintf( ' value="%s"', $nonce );
    $str .= '/>' . PHP_EOL;

    return $str;
  }

  /**
   * Get the Refererr
   *
   * This is the domain and URL of the page making the call.
   *
   * @return string
   */
  protected function getReferer()
  {
    $referer = 'self';

    $str = '<input type="hidden"';
    $str .= sprintf( ' name="%s_referer"', $this->opts['form']['prefix'] );
    $str .= sprintf( ' value="%s"', $referer );
    $str .= '/>' . PHP_EOL;

    return $str;
  }

  /**
   *  Get Javascript
   *
   * @return string
   */
  private function getJavascript()
  {
    $str = '<script>' . PHP_EOL;
    $str .= '  setTimeout(function() {' . PHP_EOL;
    $str .= "    var btnSubmit = document.getElementById('form-submit');" . PHP_EOL;
    $str .= '     if (typeof btnSubmit !== "undefined") {' . PHP_EOL;
    $str .= '       btnSubmit.disabled = false;' . PHP_EOL;
    $str .= '     }' . PHP_EOL;
    $str .= sprintf( '}, %s);%s', $this->opts['button']['delay']['ms'], PHP_EOL );
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
    $str .= sprintf( '<title>%s</title>%s', $this->opts['page']['title'], PHP_EOL);
    $str .= $this->opts['page']['robots']['index'] ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
    $str .= $this->opts['page']['css']['load'] ? sprintf('<link rel=stylesheet href="%s">%s', $this->opts['page']['css']['url'], PHP_EOL) : '';
    $str .= '</head>' . PHP_EOL;
    $str .= '<body>' . PHP_EOL;
    $str .= '<main>' . PHP_EOL;
    $str .= $file['html'];
    $str .= '</main>' . PHP_EOL;
    if ( $this->opts['page']['footer']['load'] )
    {
      $str .= '<footer>' . PHP_EOL;
      $str .= '<div class="text-center"><small>';
      $str .= sprintf( 'Note: This page has been <a href="%s">', $this->opts['page']['url'] );
      $str .= 'automatically generated</a>. No header, footer, menus or sidebars are available.';
      $str .= '</small></div>' . PHP_EOL;
      $str .= '</footer>' . PHP_EOL;
    }
    $str .= $this->opts['page']['javascript'] ? $this->getJavascript( $file, $args ) : '';
    $str .= '</body>' . PHP_EOL;
    $str .= '</html>' . PHP_EOL;

    return $str;
  }

} // End FormWriter

/**
 * Class FormTemplate
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
      /** Get the form data. */
      $form = $this->getFormData();

      /** Process the form, use the respons in a message, if necessary. */
      $resp = $this->process( $form );

      /** Wrap the form in a div. */
      $str = '<div class="form">' . PHP_EOL;

      /** Open the form. */
      $str .= sprintf( '<form action="" method="post">%s', PHP_EOL );

      /** Get the referer. */
      $str .= $this->opts['form']['referer']['load'] ? $this->getReferer() : '';

      /** Add a "use once" field to help prevent misuse. */
      $str .= $this->opts['form']['nonce']['load'] ? $this->getNonce() : '';

      /** Cycle through the fields */
      foreach ( $form['form'] as $item )
      {
        /** Load the item, only if required. */
        if ( $item['load'] )
        {
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

      /** Get the hidden fields */
      $str .= $this->getHidden( $form ) ;

      /** Get the submit button. */
      $str .= $this->getSubmit( $form );

      /** Form response div. */
      $str .= sprintf('<div class="%s-response">%s</div>%s', $this->opts['form']['prefix'], $resp['response'], PHP_EOL );

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
   * @return string|false
   */
  private function getInput( $item )
  {
    /** Check to ensure the item is an array and it has at least one required key. */
    if ( is_array( $item ) && isset( $item['type'] ) )
    {
      /** Initialize the string. */
      $str = '';

      /** Get the label (if the item is not hidden). */
      if ( 'hidden' !== $item['type'] )
      {
        $str .= $this->getLabel( $item );
      }

      /** Open the input tag. */
      $str .= '<input';

      /** Set the type (i.e. text, email, etc). */
      $str .= sprintf( ' type="%s"', $item['type'] );

      /** Set the id (name). */
      $str .= sprintf( ' id="form_%s"', $item['name'] );

      /** Set the name (name). */
      $str .= sprintf( ' name="form_%s"', $item['name'] );

      /** Set the min length (if at all). */
      $str .= sprintf( ' minlength="%s"', $item['length']['min'] );

      /** Set the max length (if at all). */
      $str .= sprintf( ' maxlength="%s"', $item['length']['max'] );

      /** Set the size (if at all). */
      $str .= isset( $item['size'] ) ? sprintf( ' size="%s"', $item['size'] ) : '';

      /** Set the value (if testing). */
      $str .= $item['value']['load'] && $this->opts['testing'] ? sprintf( ' value="%s"', $item['value']['text'] ) : '';

      /** Required (or not). */
      $str .= $item['required'] ? ' required' : '';

      /** Close the input tag. */
      $str .= ' />' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      /** Nothing here. Return false. */
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
      /** Get the label element. */
      $str = $this->getLabel( $item );

      /** Open the textarea element. */
      $str .= '<textarea';

      /** Form id. */
      $str .= sprintf( ' id="%s_%s"', $this->opts['form']['prefix'], $item['name'] );

      /** Add the name of the form. */
      $str .= sprintf( ' name="%s_%s"', $this->opts['form']['prefix'], $item['name'] );

      /** Specify the minimum length acceptable. */
      $str .= sprintf( ' minlength="%s"', $item['length']['min'] );

      /** Specify the maximum length acceptable. */
      $str .= sprintf( ' maxlength="%s"', $item['length']['max'] );

      /** Whether or not the element is required. */
      $str .= $item['required'] ? ' required' : '';

      /** Add the placeholder, if required. */
      $str .= $item['placeholder']['load'] ? sprintf(' placeholder="%s"', $item['placeholder']['text'] ) : '';

      /** Close the opening tag of the textarea element. */
      $str .= '>' . PHP_EOL;

      /** Add a text value, if required. */
      $str .= $item['value']['load'] && $this->opts['testing'] ? $item['value']['text'] : '';

      /** Close the textarea element. */
      $str .= '</textarea>' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      /** Nothing here, return false. */
      return false;
    }
  }

  /**
   * Get the Label Element
   *
   */
   private function getLabel( $item )
   {

      /** Check to make sure we have an array, and that it has at least on of the items we need. */
      if ( is_array( $item ) && isset( $item['name'] ) && count( $item ) > 1 )
      {
        /** Open the label element. */
        $str = '<label';

        /** Indicate for which form id it is.  */
        $str .= sprintf( ' for="%s_%s">', $this->opts['form']['prefix'], $item['name'] );

        /** Provide the label text. */
        $str .= isset( $item['title']['text'] ) ? $item['title']['text'] : ucfirst( $item['name'] );

        /** Close the label element. */
        $str .= '</label>' . PHP_EOL;

        /** Return the string. */
        return $str;
      }
      else
      {
        /** Nothing here, return false. */
        return false;
      }
   }
  /**
   * Get the Select Element
   *
   * Builds a select element and returns it as a string.
   *
   * @return string|false
   */
  private function getSelect( $item )
  {
    /** Get the options data. */
    $options = $this->getOptions();

    /** Check to see if we have arrays and that they have what we need. */
    if ( is_array( $item ) && count ( $item ) > 0
      && is_array( $options ) && count ( $options ) > 0 )
    {
      $str = $item['title']['load'] ? $this->getLabel( $item ) : '';

      /** Open the select element. */
      $str .= '<select';

      /** Don't forget the leading blank space before each attribute. */

      /** Set the id (for use with label and accessibility purposes). */
      $str .= sprintf( ' id="%s"', $item['name'] );

      /** Set the name (used when posting the form). */
      $str .= sprintf( ' name="%s_%s"', $this->opts['form']['prefix'], $item['name'] );

      /** Set the size of the drop down. */
      $str .= sprintf( ' size="%s"', $item['size'] );

      /** Allow multiple items to be selected (or not). It may help to set "size" to > 1, if true. */
      $str .= $item['multiple'] ? ' multiple' : '';

      /** Close the select tag. */
      $str .= '>' . PHP_EOL;

      /** Cycle through the options. */
      foreach ( $options as $option )
      {
        /** Process only if the object is slated to be included (loaded). */
        if ( $option['load'] )
        {
          /** If the item is a default, set it to default. */
          if ( isset( $option['default'] ) && $option['default'] )
          {
            /** Set this option value to "selected" if it is the default. */
            $str .= sprintf( '<option value="%s" selected="true">%s</option>%s',
              $option['value'], $option['title'], PHP_EOL );
          }
          /** Else the item is not a default. */
          else
          {
            /** A normal option (not selected by default. */
            $str .= sprintf( '<option value="%s">%s</option>%s',
              $option['value'], $option['title'], PHP_EOL );
          }
        }
      }
      /** Close the select element. */
      $str .= '</select>' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      /** Nothing here, return false. */
      return false;
    }
  }

  /**
   * Get the Options
   *
   * Gets the options from a JSON file. Currently handles only one drop down.
   *
   * @return string|false
   */
  private function getOptions()
  {
    $file = __DIR__ . '/' . $this->opts['file']['json']['name'];

    /** Get the file contents. */
    $json = file_get_contents( $file );

    /** Make sure the string is not too short and not too long. */
    if ( strlen( $json ) > 10 && strlen( $json ) < 1000 )
    {
      /** Turn the JSON string into an Associative Array (true). */
      $items = json_decode( $json, true );

      /** Ensure it is an array and that it is non-trivial. */
      if ( is_array( $items ) && count( $items ) > 1 )
      {
        /** Return the array of items. */
        return $items;
      }
      else
      {
        /** It is not an array or it has too few items. */
        return false;
      }
    }
    else
    {
      /** The length of the JSON string was too short (or too long). */
      return false;
    }
  }

  /**
   * Get the Hidden Fields
   *
   * Check for automated form submissions.
   */
  private function getHidden( $item )
  {
    if ( is_array( $item ) && isset( $item['hidden'] ) && $item['load'] )
    {
      /** Set input type to hidden. */
      $str = '<input type="hidden"';

      /** Set the name of the form. */
      $str .= sprintf( 'name="%s_%s"', $this->opts['form']['prefix'], $item['name'] );

      /** Set the minimum length to 0. */
      $str .= 'minlength="0"';

      /** Set the maximum length. */
      $str .= sprintf( 'maxlength="%s"', $item['length']['max'] );

      $str .= $item['placeholder']['load'] ? sprintf( 'placeholder="%s"', $item['placeholder']['text'] ) : '';

      /** Set the value to empty. */
      $str .= 'value=""';

      /** Close the input field. */
      $str .= '/>' . PHP_EOL;
    }
    else
    {
      /** Nothing here or not required. */
      return false;
    }
  }

  /**
   * Get the Submit Button
   *
   * @param array $form
   *
   * @return string
   */
  private function getSubmit( $form )
  {
    /** Submit button. This is disabled for a few seconds to prevent anxious bots from using it. */
    if ( $form['meta']['submit']['load'] )
    {
      $str = '<button';
      $str .= sprintf( ' id="%s-submit"', $this->opts['form']['prefix'] );
      $str .= ' type="submit"';
      $str .= ' class="button button-primary"';
      $str .= $form['meta']['submit']['disabled'] ? ' disabled' : '';
      $str .= '>';
      $str .= $form['meta']['submit']['title'];
      $str .= '</button>' . PHP_EOL;
      return $str;
    }
    else
    {
      return false;
    }
    return false;
  }

  /**
   * Write the Options Data to a File for a Select Element.
   *
   * This only needs to occur once and is here allowed ONLY on a local server,
   * (not online).
   *
   * @param array $items
   *
   * @return string|false
   */
  private function writeOptions( $items )
  {
    /** If requested, if local and if authorized. */
    if (  '127.0.0.1' == $_SERVER['REMOTE_ADDR']
          && file_exists( __DIR__ . '/.security' )
          && isset( $_GET['print'] )
          && isset( $_GET['unlock'] ) )
    {
      /** Set the response to false. */
      $resp = false;

      /** Encode the array to JSON (2 space indenting). */
      if ( $json = $this->jsonEncode( $options ) )
      {
        /** Write the string to a file.  */
        $resp = file_put_contents ( __DIR__ . '/options.json', $json );
      }
    }

    /** Return the results of the operation. */
    return $resp;
  }

  /**
   * Get Form Data
   *
   * Gets the form data from whereever it may reside.
   * Here is calls it from the FormData class.
   *
   * @return array
   */
  private function getFormData()
  {
    $data = new FormData();
    $items['form'] = $data->form();
    $items['meta'] = $data->meta();
    return $items;
  }

  /**
   * Process the Form.
   *
   * @return array|false
   */
  private function process( $form )
  {
    /** Instantiate the class. */
    $processor = new FormProcessor();

    /** Process the submitted form data and receive the response. */
    if ( $resp = $processor->process( $form ) )
    {
      /** Return the response to the form template, may include a message. */
      return $resp;
    }
    else
    {
      /** The processing failed. Return false. */
      return false;
    }
  }

} // End FormTemplate

/**
 * Class Form Processor
 *
 * Processes the submitted form data.
 */
class FormProcessor extends FormWriter
{
  /**
   * Process
   *
   * @return array|false
   */
  public function process( $form )
  {
    /** Initialize the response to null. */
    $resp = null;

    /** Check to see if the post is set and if the number of fields submitted is close to what we expect. */
    if ( isset( $_POST ) && count( $_POST ) > 0 && count ( $_POST ) < 10 )
    {
      /** Remove the $_POST from it's status as a global, and use it internally. */
      $posted = $_POST;

      /** Check the form and receive the response. */
      if ( $items = $this->check( $form, $posted ) )
      {
        /** Write the items to a file. */
        $resp = $this->write( $items );
      }
    }
    else
    {
      /** Nothing there. */
      $resp = false;
    }

    /** Return the response (message|bool). */
    return $resp;
  }

  /**
   * Check the fields
   *
   * The post has been check. It is set and it contains roughly the right number
   * of fields. Further verification needs to be done here. The field elements are:
   *
   * input, textarea and select. Of input there are: text, email and hidden.
   *
   * @param array $form
   * @param array $posted
   *
   * @return array|false
   */
  private function check( $form, $posted )
  {
    /** Set the accepted array variable to null. */
    $accepted = null;

    /** Check the referer. */
    if ( $this->checkReferer( $form, $posted ) )
    {
      /** Check the hidden fields for strange data. */
      if ( $this->hiddenFieldSecurity( $form, $posted ) )
      {

        $data = new FormData();

        /** Get the authorized form fields. */
        $authorized = $data->authorized();

        /** Cycle through the post fields. */
        foreach ( $posted as $name => $field )
        {
            /** If the post fields are authorized, accept. in_array($needle, $haystack); */
            if ( 1 || in_array( $name, $authorized ) )
            {
              //$index = $this->opts['form']['prefix'] . '_' . $key;
              //pre_dump( $form[ $index ] );
              //break;

              /** If the key value is the right length, accept. */
              if ( 1 ||
                ( ! empty( $field )
                && strlen( $field ) >= $form[$key]['length']['min']
                && strlen( $field ) <= $form[$key]['length']['max'] ) )
                {
                  if ( ! empty( $field) && $resp = $this->checkCharacters( $field ) )
                  {
                    /** Don't add this value, or flag. Something isn't quite right. */

                  }
                  else
                  {
                    /** Filter the HTML for Special Characters. */
                    if ( $filtered = $this->filterEntities( $field ) )
                    {
                      if ( $grammar = $this->checkGrammar( $filtered ) )
                      {
                          /** About as good as we can get, other than reading it. */
                        $accepted[$key] = $grammar;
                      }
                    }
                  }
                }
            }
          }
        }
        /** Got what we wanted. Let's return it for further processing. */
        pre_dump( $accepted );
        return $accepted;
    }
    else
    {
      /** Nothing acceptable here. */
      return false;
    }
  }

  /**
   * Filter Entities
   *
   */
  private function filterEntities( $field )
  {

  }

  /**
   * Check Referer
   *
   * Ensure the post is coming from the correct location and has been used
   * only once.
   *
   * @param array $form
   * @param array $posted
   *
   * @return bool
   */
  private function checkReferer( $form, $posted )
  {
     $referer = 'self';

    if ( $referer == $posted[ $this->opts['form']['prefix'] . '_referer'] )
    {
        /**  The referer and the nonce check out. Return true. */
        return true;
    }
    else
    {
        /** Either the referer OR the nonce do not check out. Return false. */
        return false;
    }
  }

  /**
   * Check Nonce
   *
   * Ensure the post is coming from the correct location and has been used
   * only once.
   *
   * @param array $form
   * @param array $posted
   *
   * @return bool
   */
  private function checkNonce( $form, $posted )
  {
    /** Retrieve the nonce. */
    $nonce = 'abcd';

    if ( 1 || $nonce == $posted[ $this->opts['form']['prefix'] . '_nonce' ] )
    {
        /**  The nonce checks out. Return true. */
        return true;
    }
    else
    {
        /** Either the referer OR the nonce do not check out. Return false. */
        return false;
    }
  }

  /**
   * Check Hidden Fields Security
   *
   * Check hidden fields for extra data. If data is present where none should
   * be, discard the form submission.
   *
   * The posted data contains items in the array which are the keys of the fields.
  * For example,
  * [...
  * "form_message" => "Lorem ipsum dolor sit amet",
  * "form_select" => "def",
  * "form_best_time" => "" ,
  * ... ]
  *
  * In this case, form_best_time was a hidden field which should not have any data,
  * but we are not told this. Therefore we have to cross reference this field
  * with the field in the form. Rather than to cycle through the fields in the form to find
  * this one, we have to create a field which simply tells us which field name to check.
  *
   * @param array $form
   * @param array $posted
   *
   * @return bool
   */
  private function hiddenFieldSecurity( $form, $posted )
  {
    /** Check only if required. (May not be required in all instances. */
    if( $this->opts['security']['check'] )
    {
      /** Assign the field name we are checking to an internal value. */
      $security = $this->opts['security']['field'];

      /** Check to see if this value is set and if it is non-zero. */
      if ( isset(  $posted[ $security ] ) && strlen( $posted[ $security ] ) > 0 )
      {
          /**  Something fishy going on here. Return false. */
          return false;
      }
      else
      {
          /** No fish. Return true. */
          return true;
      }
    }
    else
    {
      /** We are omitting the security check. Return true. */
      return true;
    }
  }

  /**
   * Check for Disallowed Characters
   *
   * Reject if any disallowed characters show up.
   *
   * @example preg_match ( $regex , $field, $match );
   *
   * @param string $field
   *
   * @return bool
   */
  private function checkCharacters( $field )
  {
    if ( $this->opts['input']['characters']['check'] )
    {
      if ( strlen( $field ) >= $this->opts['input']['text']['length']['min']
        && strlen( $field ) <= $this->opts['input']['text']['length']['max'] )
      {
        /** A bunch of special characters to check for. */
        $regex = sprintf( '/%s/', $this->opts['input']['characters']['disallowed'] );

        /** Place a forward slash `\` before each character. */
        $regex = addslashes( $regex );

        /** Match these characters. */
        preg_match( $regex, $field, $match );
        pre_dump( $field );
        if( isset( $match[0] ) && str( $match[0] > 0 ) )
        {
          /** A match had been found, that is *bad*. Return false. */
          return false;
        }
        else
        {
          /** All looks good. Return true. */
          return true;
        }
      }
      else
      {
        /** Nothing there. Return false. */
        return false;
      }
    }
    else
    {
      /** Nothing happened. */
      return null;
    }
  }

  /**
   * Check Grammar (Basic).
   *
   * Do a basic grammar check to see if this is a real form submission, or not.
   * We can't be too sure here, so will return a "graded" value (int), or false.
   * Very primitive. Neanderthal. (Or maybe a monkey...).
   *
   * @param string $field
   *
   * @return int|false|null
   */
  private function checkGrammar( $field )
  {
    if ( $this->opts['grammar']['check'] )
    {
      /** Grammar grade. */
      $grade = null;

      /** Run it through the length check again (in case this is called separately. */
      if ( strlen( $field ) >= $this->opts['input']['text']['length']['min']
        && strlen( $field ) <= $this->opts['input']['text']['length']['max'] )
      {
        /** A bunch of words to check. If they are not present, it may be a monkey. */
        $regex = $this->opts['grammar']['words'];

        /** Match these characters. */
        preg_match( $regex, $field, $match );

        if( ! isset( $match[0] ) && str( $match[0] < 5 ) )
        {
          /** *No* matches are found, maybe suspicious. */
          $grade = 0;
        }
        else
        {
          /** All looks good. Return true. */
          $grade = 1;
        }
      }
      else
      {
        /** Nothing there. Return false. */
        $grade = false;
      }
    }

    /** Return the grammar grade (primitive). */
    return $grade;
  }

  /**
   * Write the Fields to a JSON File
   * .
   * @return bool
   */
  private function write( $items )
  {
    /** Initialize the response as null. */
    $resp = null;

    /** Check to see if we have an array. */
    if ( is_array( $items ) )
    {

      /** Get the file to write to. */
      $file = $this->opts['file']['write']['name'];

      /** Encode the array, using pretty print and two spaces (not four). */
      if ( $json = $this->jsonEncode( $items ) )
      {
        /** Get the file size of the file, before we write to it. */
        $size = filesize( $file );

        /** If the file size is greater than allowed, start a new one. */
        if ( $size > $this->opts['file']['write']['max'] )
        {
            /** Write a new file, receiving the response. */
          $resp = file_put_contents( $file, $json );
        }
        /** Else, append to the file. */
        else
        {
          /** Append to the file, receiving the response. */
          $resp = file_put_contents( $file, $json, FILE_APPEND );
        }
      }
    }
    else
    {
      /** Return the response (null|false|bytes). */
      return $resp;
    }
  }

  /**
   * Json Encode
   *
   * Encode the array as JSON using JSON_PRETTY_PRINT, then use only two spaces
   * for indentation instead of four, to maintain consistency with PHP, CSS, JS
   * and HTML.
   *
   * @param array $items
   *
   * @return string|false
   *
   * @link https://stackoverflow.com/a/31689850/5449906
   */
  private function jsonEncode( $items = [] )
  {
    /** Ensure $items is an array and that it is non-trivial ( > 1 item ). */
    if ( is_array( $items ) and count( $items > 1 ) )
    {
      /** Perform a regular expression search and replace using a callback. */
      $json = preg_replace_callback ('/^ +/m', function( $match )
      {
        /** Returns input [1], repeated multiplier times [2]. */
        return str_repeat ( ' ', strlen( $match[0] ) / 2 );

      /** Json encode an array, using JSON_PRETTY_PRINT (indents with four spaces. */
      }, json_encode ( $items, JSON_PRETTY_PRINT ) );

      /** Return the $json string. */
      return $json;
    }
    else
    {
      /** Not an array, or array is only one item or less. */
      return false;
    }
  }
} // End Form Processor Class.

/**
 * Form Data Class
 *
 * Extends Form Writer
 */
class FormData extends FormWriter
{
  /**
   *  Form
   *
   * @return array
   */
  public function form()
  {
    $items = [
        [
          'element' => 'input',
          'type' => 'text',
          'name' => 'name',
          'title' => [ 'text' => 'Your Name (required)', 'load' => 1 ],
          'placeholder' => [ 'text' => 'Name', 'load' => 1 ],
          'value' => [ 'text' => 'Your Name Here', 'load' => 1 ],
          'length' => [ 'min' => 4, 'max' => 40 ],
          'required' => 1,
          'load' => 1,
        ],
        [
          'element' => 'input',
          'type' => 'email',
          'name' => 'email',
          'title' => [ 'text' => 'Your Email (required)', 'load' => 1 ],
          'placeholder' => [ 'text' => 'Email', 'load' => 1 ],
          'value' => [ 'text' => 'your@email.here', 'load' => 1 ],
          'length' => [ 'min' => 4, 'max' => 40 ],
          'required' => 1,
          'load' => 1,
        ],
        [
          'element' => 'input',
          'type' => 'text',
          'name' => 'subject',
          'title' => [ 'text' => 'Subject', 'load' => 1 ],
          'placeholder' => [ 'text' => 'Subject', 'load' => 1 ],
          'value' => [ 'text' => 'Lorem ipsum dolor sit amet', 'load' => 1 ],
          'length' => [ 'min' => 4, 'max' => 200 ],
          'required' => 0,
          'load' => 1,
        ],
        [
          'element' => 'textarea',
          'type' => 'textarea',
          'name' => 'message',
          'title' => [ 'text' => 'Your Message', 'load' => 1 ],
          'placeholder' => [ 'text' => 'Message', 'load' => 1 ],
          'value' => [ 'text' => 'Lorem ipsum dolor sit amet', 'load' => 1 ],
          'length' => [ 'min' => 4, 'max' => 400 ],
          'required' => 0,
          'load' => 1,
        ],
        [
          'element' => 'select',
          'type' => 'select',
          'name' => 'select',
          'title' => [ 'text' => 'Select', 'load' => 0 ],
          'size' => 1,
          'required' => 1,
          'multiple' => 0,
          'load' => 1,
        ],
      [
        'element' => 'input',
        'type' => 'hidden',
        'name' => 'best_time',
        'title' => [ 'text' => 'Best time to call', 'load' => 1 ],
        'placeholder' => [ 'text' => 'Best time', 'load' => 1 ],
        'value' => [ 'text' => '', 'load' => 0 ],
        'length' => [ 'min' => 0, 'max' => 40 ],
        'required' => 0,
        'disallowed' => 1,
        'load' => 1,
      ],
    ];
    return $items;
  }

  /**
   * Authorized form fields
   */
  public function authorized()
  {
    $items = [
      'name',
      'email',
      'phone',
      'subject',
      'message',
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
          'disabled' => 1,
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

  public function options()
  {
    $items = [
      [ 'value' => 'abc', 'title' => 'ABC', 'load' => 1, 'default' => 0, ],
      [ 'value' => 'def', 'title' => 'DEF', 'load' => 1, 'default' => 1 ],
      [ 'value' => 'ghi', 'title' => 'GHI', 'load' => 1, ],
      [ 'value' => 'jkl', 'title' => 'JKL', 'load' => 1, ],
      [ 'value' => 'mno', 'title' => 'MNO', 'load' => 1, ],
      [ 'value' => 'pqr', 'title' => 'PQR', 'load' => 1, ],
      [ 'value' => 'stu', 'title' => 'STU', 'load' => 1, ],
      [ 'value' => 'vwz', 'title' => 'VWZ', 'load' => 0, ],
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
