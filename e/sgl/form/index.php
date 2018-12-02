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
 * In this file, the form has first been saved as an array. This is easy to work
 * with and internal to the file. However, once past a certain point, it is helpful
 * to have this form saved to a JSON file. In this way it is separate from *this*
 * file and so can be changed independent of it. In addition, it is then possible
 * to take a form created elsewhere (or with a plugin) and save it as a JSON file,
 * with the same format. This is the same thinking as is used when saving the data
 * from the form submission. The form is submitted, and the values of the form
 * are saved in a JSON file which can then be read into a database as needed.
 *
 * The question is, how often should the form values be updated and checked? If
 * they are the same as the last time, they should not need to be updated. Or if
 * the PHP array in *this* file has been changed, then they will need to be updated.
 * Should there be allowed two versions? Or only one. One is better.
 *
 * @package Earth3300\EC01
 * @version 0.0.2
 * @author Clarence J. Bos <cbos@tnoep.ca>
 * @copyright Copyright (c) 2018, Clarence J. Bos
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL v3.0
 * @link https://github.com/earth3300/ec01-form-writer
 *
 * @wordpress-plugin
 * Plugin Name: EC01 Form Writer
 * Plugin URI:  https://github.com/earth3300/ec01-form-writer
 * Description: Displays a form, accepts a form submission, validates the data, then stores the validated data as a secured JSON file.
 * Version: 0.0.2
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
 * File: index.php
 * Created: 2018-10-15
 * Updated: 2018-11-30
 * Time: 20:50 EST
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
      'theme' => [ 'name' => 'theme-dark', ],
      'css' => [ 'load' => 1, 'url' => '/0/theme/css/style.css', ],
      'footer' => [ 'load' => 0, ],
      'javascript' => [ 'load' => 1, ],
      'url' => 'https://github.com/earth3300/ec01-form-writer',
    ],
    'file' => [
      'max' => [ 'num' => 1, 'size' => 10*10, ],
      'read' => [ 'load' => 1, 'name' => 'form.json', ],
      'write' => [ 'allowed' => 1, 'name' => '.data.json', 'max' => 10000, ],
      'type' => 'json',
      'ext' => '.json',
      'this' => 'index.php',
    ],
    'input' => [
        'text' => [ 'length' => [ 'min' => 4, 'max' => 40, ] ],
        'textarea' => [ 'length' => [ 'min' => 4, 'max' => 100, ] ],
        'characters' => [
          'check' => 1,
          'disallowed' => '~,`,!,$,%,^,&,*,(,),-,_,+,{,[,},],|,\,;,:,<,>',
        ],
        'grammar' => [
          'check' => 1,
          'words' => 'and,the,an,you,your',
        ],
      ],
    'button' => [ 'delay' => [ 'ms' => 3000, ], ],
    'form' => [
      'prefix' => 'form',
      'write' => [ 'allow' => 1 ],
      'expiry' => [ 'load' => 1, 'check' => 1, ],
      'referer' => [ 'load' => 1, 'check' => 1, ],
      'uid' => [ 'load' => 1, 'check' => 1, ],
    ],
    'required' => [ 'text' => '(required)', ],
    'security' => [
      'check' => 1,
      'field' => 'form_best_time',
    ],
    'time' => [ 'check' => 1 ],
    'mode' => [ 'manual' => 1, 'automatic' => 0, ],
    'testing' => 1,
    ];

  /**
   * Display an HTML form.
   *
   * @param array $args
   *
   * @return string
   */
  public function get( $args = null )
  {
    /** Set the boolean switches. */
    $args = $this->setTheSwitches( $args );

    /** Set up the file. */
    $file = $this->setTheFile( $args );

    /** Get the form. */
    $form = $this->getFormData( $file, $args );

    /** Get the HTML. */
    $html = $this->getTheHtml( $file, $args, $form );

    /** Deliver the HTML. */
    return $html;

    /** Done! */
  }

  /**
   * Set the File
   */
  private function setTheFile( $args )
  {
    /** Add the file to the argument array. */
    $file['path'] = $this->getFilePath( $args ) . '/' . $this->opts['file']['read']['name'];

    /** Get the name of the containing directory. */
    $file['dir'] = basename(__DIR__);

    /** Construct the file name out of the file directory and its extension. */
    $file['name'] = $file['dir'] . $this->opts['file']['ext'];

    /** Construct the file name out of the file directory and its extension. */
    $file['class'] = $file['dir'] . $this->opts['file']['type'];

    /** Get the base path of the file, including the file name. */
    $file['src'] = $this->getSrcFromFile( $file );

    /** Return the file array. */
    return $file;
  }

  /**
   * Get the HTML
   *
   */
  private function getTheHtml( $file, $args, $form )
  {
    if ( is_array( $file ) && is_array ( $args ) )
    {
      /** Open the article element. */
      $html = '<article>' . PHP_EOL;

      /** Get the form HTML. */
      $html .= $this->getFormTemplate( $form );

      /** Close the article element. */
      $html .= '</article>' . PHP_EOL;

      /** If the request is for a full page, wrap the HTML in page HTML. */
      if ( isset( $args['doctype'] ) && $args['doctype'] )
      {
        /** Note the lack of a preceding '.' before the equals sign. Important!!! */
        $html = $this->getPageHtml( $html, $file, $args );
      }

      /** Return the HTML. */
      return $html;
    }
    else
    {
      /** Don't have what we need. */
      return false;
    }
  }

  /**
   * Get the source from the file, checking for a preceding slash.
   *
   * @param array $file
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
   * Get the Form Template
   *
   */
  private function getFormTemplate( $form )
  {
    $template = new FormTemplate();

    return $template->form( $form );
  }

  /**
   * Get the Form Data
   *
   */
  private function getFormData()
  {
    $data = new FormData();

    return $data->getData();
  }

  /**
   * Get the UID
   *
   * This is a unique number generated by a special sauce.
   *
   * Browser Type.
   *
   * UID: PROTOCOL . SERVER_ADDR . REMOTE_ADDR . HTTP_HOST . DATE . USER_AGENT
   *
   * @return string
   */
  protected function getUid()
  {
    /** Initialize the uid. */
    $uid = null;
    /** Add the Server Signature. */
    $uid .= $_SERVER['SERVER_SIGNATURE'];

    /** Add the Server address. */
    $uid .= '-' . $_SERVER['SERVER_PROTOCOL'];

    /** Add the Server address. */
    $uid .= isset( $_SERVER['HTTPS'] ) ?  '-' . $_SERVER['HTTPS'] : '-' . '0';

    /** Add the Server address (with a dash). */
    $uid .= '-' . $_SERVER['SERVER_ADDR'];

    /** Add the domain (host). */
    $uid .= '-' . $_SERVER['HTTP_HOST'];

    /** Add the Server address (with a dash). */
    $uid .= '-' . $_SERVER['SCRIPT_NAME'];

    /** Add the date. This means This hash won't check out over midnight. */
    $uid .= '-' . date('Y-m-d'); // H:s (i.e. 24:00)

    /** Add the User Agent (Browser Type and Device). */
    $uid .= '-' . $_SERVER['HTTP_USER_AGENT'];

    /** Scramble, using sha1. */
    $hash = sha1( $uid );

    /** Return the scrambled uid. */
    return $hash;
  }

  protected function getUidHtml()
  {
    /** Get the uid. */
    $uid = $this->getUid();

    /** Basic string check. */
    if ( is_string( $uid ) && strlen( $uid ) > 0 && strlen( $uid ) <= 512 )
    {
      $str = '<input type="hidden"';
      $str .= sprintf( ' name="%s_uid"', $this->opts['form']['prefix'] );
      $str .= sprintf( ' value="%s"', $uid );
      $str .= ' />' . PHP_EOL;

      /** Return the string. */
      return $str;
    }
    else
    {
      /** Nothing there (that is right). */
      return false;
    }
  }

  /**
   * Get the Referer
   *
   * This is the domain and URL of the page making the call.
   *
   * @return string
   */
  protected function getReferer()
  {
    $referer = $_SERVER['SERVER_NAME'];
    $referer .= str_replace( $this->opts['file']['this'], '', $_SERVER['SCRIPT_NAME'] );

    return $referer;
  }

  /**
   * Get the Referer HTML
   *
   * This is the domain and URL of the page making the call.
   *
   * @param string
   *
   * @return string
   */
  protected function getRefererHtml()
  {
    $referer = $this->getReferer();

    if ( is_string( $referer )
      && strlen( $referer ) > 5
      && strlen( $referer ) < 50 )
    {
      $str = '<input type="hidden"';
      $str .= sprintf( ' name="%s_referer"', $this->opts['form']['prefix'] );
      $str .= sprintf( ' value="%s"', $referer );
      $str .= ' />' . PHP_EOL;

      return $str;
    }
    else
    {
      return false;
    }
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
  public function getPageHtml( $html, $file, $args )
  {
    $str = '<!DOCTYPE html>' . PHP_EOL;
    $str .= '<html ';
    $str .= 'class="dynamic';
    $str .= ' ' . $this->opts['page']['theme']['name'];
    $str .= ' ' . $file['class'];
    $str .= '"';
    $str .= ' lang="en-CA"';
    $str .= '>' . PHP_EOL;
    $str .= '<head>' . PHP_EOL;
    $str .= '<meta charset="UTF-8">' . PHP_EOL;
    $str .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
    $str .= sprintf( '<title>%s</title>%s', $this->opts['page']['title'], PHP_EOL);
    $str .= $this->opts['page']['robots']['index'] ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
    $str .= $this->opts['page']['css']['load'] ? sprintf('<link rel=stylesheet href="%s">%s', $this->opts['page']['css']['url'], PHP_EOL) : '';
    $str .= '</head>' . PHP_EOL;
    $str .= '<body>' . PHP_EOL;
    $str .= '<main>' . PHP_EOL;
    $str .= $html;
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

    /** Return the string. */
    return $str;
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
  protected function jsonEncode( $items = [] )
  {
    /** Ensure $items is an array and that it is non-trivial ( > 1 item ). */
    if ( is_array( $items ) && count( $items > 1 ) && count( $items) < 1000 )
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

  /**
   * Write Form
   *
   * Write the form to a JSON file, if requested and allowed.
   *
   * @return int|false
   */
  protected function writeForm( $form )
  {
    /** If authorized, continue. */
    if ( $this->opts['form']['write']['allow'] )
    {
      /** If requested, continue. */
      if( 1 || isset( $_GET['write'] ) && isset( $_GET['form'] ) )
      {
        /** If security protocol is in place, continue. */
        if ( file_exists( __DIR__ . '/' . '.security' ) )
        {
          /** If on a local machine, continue. */
          /*
          Note: This means this will only work on a local machine, which
          may be a problem for some. It will mean that they will need to set up
          a desktop or laptop computer with a local server, that they can then use
          to perform this action. This may be too much for some. *However*, it is
          *much* more secure and is like placing a lock on the front door versus
          not placing a lock on the front door. It is asking for trouble by
          leaving the door wide open. Actually, with this restriction, the security
          increases by an order of magnitude. Therefore it should be encouraged
          to work this way.

          The following check may not work on all servers. However, the goal is
          to encourage consistency of platforms, so that there are fewer internal
          variations to have to worry about.
          */
          if ( '127.0.0.1' == $_SERVER['SERVER_ADDR'] )
          {
            /** Define the file. */
            $file = __DIR__ . '/' . $this->opts['file']['read']['name'];

            /** Call the writer. This will transform the data as needed, and write it to a file. */
            $resp = $this->writeFile( $file, $form );

            /** Print out the response for viewing, if in testing mode. */
            if ( $this->opts['testing'] )
            {
              echo "<pre>";
              var_dump( $resp );
              echo "</pre>";
            }
          }
        }
        else
        {
          /** Not requested. */
          return null;
        }
      }
    }
  }

  /**
   * Write
   *
   * Takes an array, checks it, encodes the array as JSON, with pretty
   * print and two spaces then prints it to a file.
   *
   * @param array $items
   *
   * @return bool
   */
  protected function writeFile( $file, $items )
  {
    /** If $items is an array and something is there, continue. */
    if ( is_array( $items )
    && count( $items ) > 1 && count( $items ) < 1000 )
    {
      /** Encode the array. Assume this can be done. */
      $json = $this->jsonEncode( $items );

      /** Write the JSON string to a file. */
      if ( $resp = file_put_contents( $file, $json ) )
      {
         /** Return the response for inspection or viewing. */
         return $resp;
      }
      else
      {
        /** It didn't work. */
        return false;
       }
     }
     else
     {
       /** We didn't get what we needed to do the job. Go back one space. */
      return false;
     }
  }

  /**
   * Write the Fields to a JSON File
   * .
   * @return bool
   */
  protected function writeData( $items )
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
        /** Get the file size of the file, before we write to it (~ <10000 bytes) */
        $size = filesize( $file );

        /** If the file size is greater than allowed, start a new one. */
        if ( $size > $this->opts['file']['write']['max'] )
        {
          $file = str_replace( '.json', '-' . date('Y-m-d-H-i-s') . '.json', $file );
            /** Write a new file, receiving the response. */
          $resp = file_put_contents( $file, $json );
        }
        /** Else, append to the file. */
        else
        {
          /** Append to the file, receiving the response. */
          $resp = file_put_contents( $file, $json, FILE_APPEND | LOCK_EX );
        }
      }
    }
    else
    {
      /** Return the response (null|false|bytes). */
      return $resp;
    }
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
  public function form( $form )
  {
    /** Check it. */
    if ( is_array( $form ) && isset( $form[1]['submit'] ) )
    {
      /** Process the form, use the respons in a message, if necessary. */
      $resp = $this->process( $form );

      /** Wrap the form in a div. */
      $str = '<div class="form">' . PHP_EOL;

      /** Open the form. */
      $str .= sprintf( '<form action="" method="post">%s', PHP_EOL );

      /** Get the referer. */
      $str .= $this->opts['form']['referer']['load'] ? $this->getRefererHtml() : '';

      /** Add a "use once" field to help prevent misuse. */
      $str .= $this->opts['form']['uid']['load'] ? $this->getUidHtml() : '';

      if ( is_array( $form[0] ) )
      {
        /** Cycle through the fields */
        foreach ( $form[0] as $item )
        {
          /** Load the item, only if required. */
          if ( $item['load'] )
          {
            switch( $item['element'] )
            {
              case 'input' :
                $str .= $this->getInput( $item );
                break;

              case 'radio' :
                $str .= $this->getRadio( $item );
                break;

              case 'checkbox' :
                $str .= $this->getCheckbox( $item );
                break;

              case 'select' :
                $str .= $this->getSelect( $item );
                break;

              case 'textarea' :
                $str .= $this->getTextArea( $item );
                break;

              default:
            } // end switch
          } // end if load
        } // end foreach
      }

      /** Get the hidden fields */
      $str .= $this->getHidden( $form ) ;

      /** Get the submit button. */
      $str .= $this->getSubmit( $form );

      /** Form response div. */
      $str .= sprintf('<div class="%s-response">%s</div>%s',
        $this->opts['form']['prefix'], $resp['response'], PHP_EOL );

      /** Close the form. */
      $str .= '</form>' . PHP_EOL;

      /** Close the form div */
      $str .= '</div><!-- .form -->' . PHP_EOL;

      /** Return the form. */
      return $str;
    }
    else
    {
      return 'Not available.';
    }
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
   * Get Radio Element
   *
   * A radio element is an input element with `type` set to "radio". The difference
   * is that there is more than one radio element to make a group. These are not
   * grouped by wrapping them in an element, they are grouped by having the same
   * name (`name`="name"). The difference is in assigning a different value to
   * each. The parameter needs to have a sub array to  make it work. Don't forget
   * the label.
   *
   * @link https://developer.mozilla.org/en-US/docs/Web/HTML/Element/input/radio
   *
   * @param array $radio
   *
   * @return string
   */
  private function getRadio( $radio )
  {
    /** Make sure the paramater is an array, and that it has "type" set, etc. */
    if ( is_array( $radio ) && isset( $radio['type'] ) && 'radio' == $radio['type'] )
    {
      if ( $radio['load'] )
        {
        /** Check to make sure we have a sub array and that it has more than one item. */
        if ( isset( $radio['items'] ) && count( $radio['items'] ) > 1 )
        {
          /** Open the radio div, with `class="radio"`. */
          $str = '<div class="radio">' . PHP_EOL;

          if ( $radio['title']['load'] )
          {
            /** Load the title for the radio group, if required. */
            $str .= sprintf('<div class="title">%s</div>%s', $radio['title']['text'], PHP_EOL );
          }

          /** Cycle through the items. */
          foreach ( $radio['items'] as $item )
          {
            /** Basic check. */
            if ( isset( $item['name'] ) && count( $item > 3 ) )
            {
              /** Create the item, only if required. */
              if ( $item['load'] )
              {
                /** Set the id. This is used in the id and the label. These two must be the same. */
                $id = sprintf( '%s_%s_%s' , $this->opts['form']['prefix'], $item['name'], $item['value'] );

                /** Open the input element. */
                $str .= '<input type="radio"';

                /** Set the id (must be unique). Start with a blank space and end with a new line. */
                $str .= sprintf( ' id="%s"', $id );

                /** Set the name (must be the same for all radio elements). Note $radio[], not $item[] here. */
                $str .= sprintf( ' name="%s_%s"', $this->opts['form']['prefix'], $radio['name'] );

                /** Set the value (required). Must be unique. Used when processing the form.*/
                $str .= sprintf( ' value="%s"', $item['value'] );

                /** Set the default, if "default" is set and if it set to "true" or "1". */
                $str .= isset( $item['default'] ) && $item['default'] ? ' checked' : '';

                /** Close the element, and add a new line. */
                $str .= ' />';

                /** Set the label. Put this in the right spot and style accordingly. */
                $str .= '<label ';

                /** Set the "for" to equal the id of the radio element. */
                $str .= sprintf( 'for="%s">', $id );

                /** Set the text of the label. */
                $str .= $item['text'];

                /** Close the label. */
                $str .= '</label>';

                /** Add a new line (so label can be moved around). */
                $str .= PHP_EOL;
              }
            }
            else
            {
              //not enough there.
              pre_dump( $item );
              break;
            }
          }

          /** Close the radio div. */
          $str .= '</div><!-- .radio -->' . PHP_EOL;

          /* Should look like the following when done.
            <div class="radio">
            <input type="radio" id="contactChoice2"
             name="contact" value="phone">
            <label for="contactChoice2">Phone</label>
            </div><!-- .radio -->
          */

          /** Return the string. */
          return $str;
        }
        else
        {
          /** Something is wrong. No items to work with. */
          return false;
        }
      }
    }
    else
    {
      /** Nothing to work with. */
      return false;
    }
  }

  /**
   * Get Checkbox
   *
   * Surprisingly, the HTML5 specification, sets this attribute to:
   * checked="". In any element where the "checked" attribute appears (regardless
   * of its value), the checked state will be triggered. If the checkbox needs
   * to be left unchecked, then the "checked" attribute must be omitted entirely.
   *
   * @link https://stackoverflow.com/a/4613192/5449906
   * @link https://stackoverflow.com/a/4228827/5449906
   * @link https://www.w3.org/TR/html5/infrastructure.html#sec-boolean-attributes
   *
   */
   private function getCheckbox( $item )
   {

    /** Make sure the paramater is an array, and that it has "type" set, etc. */
    if ( is_array( $item ) && isset( $item['type'] ) && 'checkbox' == $item['type'] )
     {
      if ( $item['load'] )
       {
        /** Open the div, with `class="checkbox"`. */
        $str = '<div class="checkbox">' . PHP_EOL;

        /** Set the id. This is used in the id and the label. These two must be the same. */
        $id = sprintf( '%s_%s' , $this->opts['form']['prefix'], $item['name'] );

        /** Open the input element. */
        $str .= '<input type="checkbox"';

        /** Set the id (must be unique). Start with a blank space. */
        $str .= sprintf( ' id="%s"', $id );

        /** Set the name (used when posting). */
        $str .= sprintf( ' name="%s_%s"', $this->opts['form']['prefix'], $item['name'] );

        /** Set the value (required). Must be unique. Used when processing the form.*/
        $str .= sprintf( ' value="%s"', $item['value'] );

        /** Set the default to checked (or not). HTML5: checked="". */
        $str .= isset( $item['checked'] ) && $item['checked'] ? ' checked=""' : '';

        /** Close the input element. Don't add a new line before label. */
        $str .= ' />';

        /** Set the label. Put this in the right spot and style accordingly. */
        $str .= '<label ';

        /** Set the "for" to equal the id of the radio element. */
        $str .= sprintf( 'for="%s">', $id );

        /** Set the text of the label. */
        $str .= $item['text'];

        /** Close the label. */
        $str .= '</label>';

        /** Add a new line (so label can be moved around). */
        $str .= PHP_EOL;

        /** Close the checkbox div. */
        $str .= '</div><!-- .checkbox -->' . PHP_EOL;

        /*
          Should look something like this when done:
          <div class="checkbox">
          <input type="checkbox" id="subscribeNews" name="subscribe" value="newsletter">
          <label for="subscribeNews">Subscribe to newsletter?</label>
          </div>
        */

        /** Done. Return the string. */
        return $str;
      }
      else
      {
        /** Nothing to load. */
        return false;
      }
    }
    else
    {
      /** Don't have what we need. Something wrong. */
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

      /** Close the textarea element, beginning and ending with a new line. */
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
  private function getSelect( $select )
  {
    /** Check to see if we have arrays and that they have what we need. */
    if ( is_array( $select )
      && count ( $select ) > 0
      && isset( $select['items'] )
      && count ( $select['items'] ) > 0 )
    {
      if ( $select['load'] )
      {
        /** Get the select label (title). */
        $str = $select['label']['load'] ? $this->getLabel( $select ) : '';

        /** Open the select element. */
        $str .= '<select';

        /** Don't forget the leading blank space before each attribute. */

        /** Set the id (for use with label and accessibility purposes). */
        $str .= sprintf( ' id="%s"', $select['name'] );

        /** Set the name (used when posting the form). */
        $str .= sprintf( ' name="%s_%s"', $this->opts['form']['prefix'], $select['name'] );

        /** Set the size of the drop down. */
        $str .= sprintf( ' size="%s"', $select['size'] );

        /** Allow multiple items to be selected (or not). It may help to set "size" to > 1, if true. */
        $str .= $select['multiple'] ? ' multiple' : '';

        /** Close the select tag. */
        $str .= '>' . PHP_EOL;

        /** Cycle through the options. */
        foreach ( $select['items'] as $item )
        {
          /** Process only if the object is slated to be included (loaded). */
          if ( $item['load'] )
          {
            /** Open the option element. */
            $str .= '<option';

            /** Set the value of the option element. */
            $str .= sprintf( ' value="%s"', $item['value'] );

            /** If the item is a default, set it to default (or not). */
            $str .= isset( $item['default'] ) && $item['default'] ? ' selected=""' : '';

            /** Close the opening option tag. */
            $str .= '>';

            /** Set the text of the option element. */
            $str .= $item['text'];

            /** Close the option element and add a new line. */
            $str .= '</option>' . PHP_EOL;
          } // end item load
        } // end foreach

        /** Close the select element. */
        $str .= '</select>' . PHP_EOL;

        /** Return the string. */
        return $str;
      }
      else
      {
        /** No select element to load, return false. */
        return false;
      }
    }
    else
    {
      /** Something wrong happened. We don't have what we need. */
      return false;
    }
  }

  /**
   * Get the Form JSON File
   *
   * Gets the form parameters saved as a JSON file.
   *
   * @return string|false
   */
  private function getFormJson()
  {
    $file = __DIR__ . '/' . $this->opts['form']['file']['name'];
    echo $file;
    /** Get the file contents. */
    $json = file_get_contents( $file );

    /** Make sure the string is not too short and not too long. */
    if ( strlen( $json ) > 10 && strlen( $json ) < 3000 )
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

      /** Set the value, if required */
      $str .= $item['value']['load'] ? sprintf( 'value="%s"', $item['value']['text'] ) : '';

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
    if( is_array( $form ) && isset( $form[1]['submit']['load'] ) )
    {
      /** Submit button. This is disabled for a few seconds to prevent anxious bots from using it. */
      if ( $form[1]['submit']['load'] )
      {
        $str = '<button';
        $str .= sprintf( ' id="%s-submit"', $this->opts['form']['prefix'] );
        $str .= ' type="submit"';
        $str .= ' class="button button-primary"';
        $str .= $form[1]['submit']['disabled'] ? ' disabled' : '';
        $str .= '>';
        $str .= $form[1]['submit']['title'];
        $str .= '</button>' . PHP_EOL;

        /** Return the string. */
        return $str;
      }
      else
      {
        /** Not requested. */
        return false;
      }
    }
    else
    {
      /** Problem. */
      return false;
    }
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
    if ( count( $_POST ) > 0 && count ( $_POST ) < 20 )
    {
      /** Remove the $_POST from it's status as a global, and use it internally. */
      $posted = $_POST;

      /** Check the form and receive the response. */
      if ( $items = $this->check( $form, $posted ) )
      {
        /** Write the items to the file specified in the function. */
        $resp = $this->writeData( $items );
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
    if ( $accepted['referer'] = $this->checkReferer( $posted ) )
    {
      /** Check the hidden fields for strange data. */
      if ( $accepted['hidden'] = $this->checkHiddenFields( $form, $posted ) )
      {
        /** Check the uid. */
        if ( $accepted['uid'] = $this->checkUid( $form, $posted ) )
        {
          $data = new FormData();

          /** Get the authorized form fields. */
          $authorized = $data->authorizedFields();

          /** Add the remote (IP) address. */
          $accepted['remote'] = $_SERVER['REMOTE_ADDR'];

          /** Check how long it took to fill out the form. */
          $accepted['time'] = $this->checkTime( $posted['form_time_posted'] );

          /** Cycle through the post fields. */
          foreach ( $posted as $name => $field )
          {
            /** Get the suffix from posted. */
            $suffix = str_replace( $this->opts['form']['prefix' ] . '_', '', $name );

            /** Get the type. */
            $type = isset( $form[0][ $suffix ]['type'] ) ? $form[0][ $suffix ]['type'] : false;

            /** If the post fields are authorized, accept. in_array($needle, $haystack); */
            if ( $type && in_array( $suffix , $authorized ) )
            {
              /** If the key value is the right length, accept. */
              if ( strlen( $field ) > 0 && isset( $form[0][ $suffix ] ) )
              {
                /** Select the fields that accept free form text. */
                if ( in_array( $type, [ 'text', 'email', 'textarea' ]  ) )
                {
                  /** Check the field length. */
                  if ( $this->checkFieldLength( $form, $field, $suffix ) )
                  {
                    /** Check the characters. */
                    $chars = $this->checkCharacters( $field );

                    /** Add it to the accepted array. */
                    $accepted[ $suffix ]['chars'] = $chars;
                  }

                  /** Filter the HTML for Special Characters. */
                  $field = $this->filterEntities( $field );

                  if ( 'textarea' == $type )
                  {
                    /** Check the grammar. (Returns int|bool|null). */
                    $grammar = $this->checkGrammar( $field );

                    /** About as good as we can get, other than reading it. */
                    $accepted[ $suffix ]['grammar'] = $grammar;
                  }
                }

                $accepted[ $suffix ]['field'] = $field;
                }
              } // end authorized.
            } // end foreach

          /** Got what we wanted. Let's return it for further processing. */
          return $accepted;
        } //uid check
        else
        {
          /** Uid did not check out. */
          return false;
        }
      }
      else
      {
        /** Hidden field security did not check out. */
        return false;
      }
    }
    else
    {
      /** Referer did not check out. */
      return false;
    }
  }

  /**
   * Check Field Length
   *
   */
  private function checkFieldLength( $form, $field, $suffix )
  {
    /** Check to see if the field length is available (and therefore applicable. */
    if ( isset( $form[0][ $suffix ]['length'] ) )
    {
       if ( strlen( $field ) >= $form[0][ $suffix ]['length']['min']
         && strlen( $field ) <= $form[0][ $suffix ]['length']['max'] )
         {
          /** Length checks out. */
          return true;
         }
         else
         {
           /** Length does not check out. */
           return false;
         }
    }
    else
    {
      /** Field length is not available. */
      return false;
    }
  }

  /**
   * Filter Entities
   *
   * Do not double encode. Encode quotes.
   *
   * @param string $field
   *
   * @return string|false
   */
  private function filterEntities( $field )
  {
    if ( strlen( $field ) > 0 && is_string( $field ) )
    {
      $field = htmlspecialchars( $field, ENT_QUOTES, ini_get("default_charset"), false );

      return $field;
    }
    else
    {
      return false;
    }
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
  private function checkReferer( $posted )
  {
    /** Get the referer. */
     $referer = $this->getReferer();

     /** Get the referred. */
     $referred = $posted[ $this->opts['form']['prefix'] . '_referer'];

    if ( $referer == $referred )
    {
      /**  They are the same. Return true. */
      return true;
    }
    else
    {
      /** They are not the same. Return false. */
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
  private function checkHiddenFields( $form, $posted )
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
   * Check Uid
   *
   * Ensure the post is coming from where it came from (does not work around
   * midnight).
   *
   * @param array $form
   * @param array $posted
   *
   * @return bool|false
   */
  private function checkUid( $form, $posted )
  {
    /** Instantiate the $checked value to "null". */
    $checked = null;

    if ( $this->opts['form']['uid']['check'] )
    {
      /** Get the uid posted. */
      $posted_uid = $posted[ $this->opts['form']['prefix'] . '_uid' ];

      /** Check to see if the recevied string is of a reasonable length. */
      if ( strlen( $posted_uid ) > 4 &&  strlen( $posted_uid ) < 45 )
      {
        /** Get the Uid from the server (a hash). */
        $server_uid = $this->getUid();

        /** Check sent against received. */
        if ( $posted_uid == $server_uid )
        {
            /**  The uid checks out. Return true. */
            return true;
        }
        else
        {
            /** The uid doe not check out. Return false. */
            return false;
        }
      }
      else
      {
        /**  Something does not check out. Return -1. */
        return false;
      }
    }
    else
    {
      /** No check done. Return null. */
      return null;
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
    /** Initialize. */
    $chars['checked'] = 0;
    $chars['length'] = null;
    $chars['grade'] = null;

    if ( $this->opts['input']['characters']['check'] )
    {
      if ( is_string( $field ) && strlen( $field ) > 0 )
      {
        /** It is being checked. */
        $chars['checked'] = 1;

        /** Capture the string length. */
        $chars['length'] = strlen( $field );

        /** Replace the commas with `|\\`. The double backslash is necessary to escape the value. */
        $disallowed = str_replace( ',', '|\\', $this->opts['input']['characters']['disallowed'] );

        /** Wrap these words with forward slashes ('/') and brackets. */
        $regex = sprintf( '/(%s)/', $disallowed  );

        /** Match these characters. */
        $match = preg_match( $regex, strtolower( $field ), $matches );

        if ( ! $match )
        {
          /** No match. Good grade. */
          $chars['grade'] = 1;
        }
        else
        {
          /** Match found. Bad grade. */
          $chars['grade'] = 0;
        }
      }
    }

    /** Return the check. */
    return $chars;
  }

  /**
   * Check Time Posted
   *
   * Check the time posted against the time submitted.
   *
   * @param string $field
   *
   * @return array
   */
  private function checkTime( $field )
  {
    if ( $this->opts['time']['check'] )
    {
      /** The field needs to be long enough, but not too long. */
      if ( strlen( $field ) > 8 && strlen( $field ) < 15 )
      {
        /** Capture the time, now. */
        $received = time();

        /** Get the current 24 hour time to the nearest second, with leading zeros. */
        $elapsed = $received - (int)$field;

        $time['checked'] = 1;
        $time['stamp'] = date('Y-m-d H:i:s');
        $time['received'] = $received;
        $time['posted'] = (int)$field;
        $time['elapsed'] = $elapsed;

        if ( $elapsed > 5 && $elapsed < 300 )
        {
          $time['score'] = 1;
        }
        else
        {
          $time['score'] = 0;
        }
      }
    }
    else
    {
      /** No check requested. */
      $time['checked'] = 0;
      $time['stamp'] = null;
      $time['received'] = null;
      $time['posted'] = null;
      $time['elapsed'] = null;
      $time['score'] = null;
    }

    /** Return the time array. */
    return $time;
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
    /** Grammar checked and grade. */
    $grammar['checked'] = null;
    $grammar['grade'] = null;

    if ( $this->opts['input']['grammar']['check'] )
    {
      /** Change the comma separated string into one compatible with regex. */
      $words = str_replace( ',', '|\b', $this->opts['input']['grammar']['words'] );

      /** Wrap these words with forward slashes ('/') and brackets. */
      $regex = sprintf( '/(%s)/', $words  );

      /** Match these characters. */
      $match = preg_match( $regex, strtolower( $field ), $matches );

      if ( $match )
      {
        /** A match found. Using a recognizable word. */
        $grammar['grade'] = 1;
      }
      else
      {
        /** No match found. Could be suspicious. */
        $grammar['grade'] = 0;
      }

      /** The grammar has been checke and a grade assigned. */
      $grammar['checked'] = 1;
    }
    else
    {
      /** No check done. */
      $grammar['checked'] = false;
    }

    /** Return the grammar check and grade. */
    return $grammar;
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
   * Get Form Data
   *
   * Gets the form data from whereever it may reside.
   * Here is calls it from the FormData class.
   *
   * @return array
   */
  public function getData()
  {
    /** Switch here to retrieve array or json file. */
    if ( $items = $this->getFile() )
    {
      return $items;
    }
    elseif ( $items = $this->getArray() )
    {
      /** We need to write this to a file, as that is our preferred method. */
      $resp = $this->writeForm( $items );

      /** Return the items. */
      return $resp;
    }
    else
    {
      return false;
    }
  }

  /**
   * Get Form Data
   *
   * Gets the form data from whereever it may reside.
   * Here is calls it from the FormData class.
   *
   * @return array
   */
  protected function getFile()
  {
    /** Switch here to retrieve array or json file. */
    if ( $this->opts['file']['read']['load'] )
    {
      /** Specify the file in the current directory. */
      $file = __DIR__ . '/' . $this->opts['file']['read']['name'];

      if ( file_exists( $file ) )
      {
        /** If the file exists, get the contents. */
        $json = file_get_contents( $file );

        /** Check the string length. */
        if ( strlen( $json ) > 4 && strlen( $json ) < 10000 )
        {
          /** Translate the (expected) JSON string into an array. */
          $items = json_decode( $json, true );

          if ( is_array( $items ) && count( $items ) > 0 && count( $items ) < 1000 )
          {
            /** If it is an array, with something in it, return it. */
            return $items;
          }
          else
          {
            /** Not an array or not the right number of items. */
            return false;
          }
        }
        else
        {
          /** String the wrong length. */
          return false;
        }
      }
      else
      {
        /** File does not exist. */
        return false;
      }
    }
    else
    {
      /** File not requested. */
      return null;
    }
  }

  /**
   *  Form
   *
   * 0: Fields
   * 1: Meta
   *
   * @return array
   */
  private function getArray()
  {
    $items = [
      0 => [
        'name' => [
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
        'email' => [
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
        'subject' => [
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
        'message' => [
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
        'select' => [
          'element' => 'select',
          'type' => 'select',
          'name' => 'select',
          'label' => [ 'text' => 'Select', 'load' => 1 ],
          'size' => 1,
          'required' => 1,
          'multiple' => 0,
          'load' => 1,
          'items' => [
            [ 'value' => 'abc', 'text' => 'ABC', 'load' => 1, 'default' => 1, ],
            [ 'value' => 'def', 'text' => 'DEF', 'load' => 1, 'default' => 0 ],
            [ 'value' => 'ghi', 'text' => 'GHI', 'load' => 1, ],
            [ 'value' => 'jkl', 'text' => 'JKL', 'load' => 1, ],
            [ 'value' => 'mno', 'text' => 'MNO', 'load' => 1, ],
            [ 'value' => 'pqr', 'text' => 'PQR', 'load' => 1, ],
            [ 'value' => 'stu', 'text' => 'STU', 'load' => 1, ],
            [ 'value' => 'vwz', 'text' => 'VWZ', 'load' => 0, ],
          ],
        ],
      'best_time' => [
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
      'time_posted' => [
        'element' => 'input',
        'type' => 'hidden',
        'name' => 'time_posted',
        'title' => [ 'text' => 'Time Posted', 'load' => 1 ],
        'placeholder' => [ 'text' => 'Time Posted', 'load' => 1 ],
        'value' => [ 'text' => time(), 'load' => 1 ],
        'length' => [ 'min' => 0, 'max' => 40 ],
        'required' => 1,
        'disallowed' => 1,
        'load' => 1,
      ],
      'checkbox' => [
        'element' => 'checkbox',
        'type' => 'checkbox',
        'name' => 'checkbox',
        'value' => 'check-1',
        'text' => 'Checkbox',
        'checked' => 1,
        'load' => 1,
      ],
      'radio' => [
        'element' => 'radio',
        'type' => 'radio',
        'name' => 'radio',
        'title' => [ 'text' => 'Radio', 'load' => 1 ],
        'placeholder' => null,
        'length' => null,
        'required' => null,
        'load' => 1,
        'items' => [
          [ 'value' => 'value-1', 'name' => 'type-1', 'text' => 'Type 1', 'load' => 1, 'default' => 1 ],
          [ 'value' => 'value-2', 'name' => 'type-1', 'text' => 'Type 2', 'load' => 1 ],
          [ 'value' => 'value-3', 'name' => 'type-1', 'text' => 'Type 3', 'load' => 1 ],
          [ 'value' => 'value-4', 'name' => 'type-1', 'text' => 'Type 4', 'load' => 0 ],
        ],
      ],
    ],
    1 => [
      'submit' => ['title' => 'Send', 'disabled' => 1, 'load' => 1, ],
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
      ],
    ];
    return $items;
  }

  /**
   * Authorized form fields
   */
  public function authorizedFields()
  {
    $items = [
      'name',
      'email',
      'phone',
      'subject',
      'message',
      'select',
      'radio',
      'checkbox',
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
