<?php
/**
 * Device Detection and Theme Delivery
 *
 * This set of code was developed as an attempt to determine the device the
 * viewer was using, so that the content sent could be optimized to that device.
 * In addition, then, to knowing what device the viewer is using, a sense of the
 * context in which the content is being viewed can be ascertained. For example,
 * a person viewing a smartphone may either be on the street or inside in a business
 * setting. However a person on a desktop computer (not a tablet, not a smartphone)
 * will likely NOT be walking down the street looking at a 21" monitor. This will
 * be highly unlikely. In addition, when a person is viewing a large wide screened
 * monitor, they may be more engaged and focussed on their task. Thus it makes sense
 * to do justice to this context. The original code set was developed using global
 * functions. Here these have simply been converted to a class. Some modifications
 * may be made.
 *
 * Standards: https://www.php-fig.org/psr/psr-2/  Static declared *after* visibility.
 *
 * File: index.php
 * Created: 2015-00-00
 * Updated: 2018-12-03
 * Time: 18:36 EST
 */

namespace Earth3300\EC01;

/**
 * Device
 *
 * To optimize the display to the device being used, we need to know what screen
 * the device is using. Although this may appear to be self-evident, in current
 * mobile first design, the *width* of the browser is used in a stylesheet,
 * without reference to either the device or the type of screen being used.
 * Although this may be sufficient in many cases, it loses the richness of the
 * context. The browser window may be one of many open on a wide screen, but
 * simply set at a low width. The viewer has the capacity to view it at full width
 * (and thus benefit from the full size of the screen), but if this is not
 * programmed directly into the page that has been delivered, that viewer will
 * never be able to fully benefit from that screen. This attempts to rectify that.
 * In other words, this helps to optimize the use of a wide screen display in the
 * range of ~21" or greater (1920px x 1080px or better).
 */
class Device
{
  /** @var $device */
  protected $screen = [];

  /** @var array Default options. */
  protected $opts = [
    'page' => [
      'title' => 'Device',
      'robots' => [ 'index' => 0, ],
      'theme' => [ 'name' => 'theme-dark', ],
      'css' => [ 'load' => 1, 'url' => '/0/theme/css/style.css', ],
      'footer' => [ 'load' => 0, ],
      'javascript' => [ 'load' => 1, ],
      'url' => 'https://github.com/earth3300/ec01-device',
    ],
    'file' => [
      'max' => [ 'num' => 1, 'size' => 100*10, ],
      'read' => [ 'load' => 1, 'name' => 'device.html', ],
      'type' => 'html',
      'ext' => '.html',
      'this' => 'index.php',
    ],
    'security' => [ 'check' => 1, ],
    'mode' => [ 'manual' => 1, 'automatic' => 0, ],
    'testing' => 1,
    ];

  /**
   * Get
   *
   * Get the device being requested.
   *
   * @return array
   */
  public function get()
  {
    /** Try to detect the device. */
    $device['detected'] = $this->getDetectedDevice();

    /** Get the device requested (if any) from the URL query string. */
    $device['requested'] = $this->getRequestedDevice();

    /** Determine the priority and the theme to deliver. */
    $device['theme'] = $this->getThemeToDeliver( $device );

    return $device;
  }

  /**
   * HTML
   *
   * Get HTML to render a page.
   *
   * @return string
   */
  public function html()
  {
    $article = $this->article();
    $html = $this->getPageHtml( $article );

    return $html;
  }

  /**
   * Article
   *
   * Get the article HTML.
   *
   * @return string
   */
  public function article()
  {
    $str = '<article>' . PHP_EOL;
    $str .= '<h1>Title</h1>' . PHP_EOL;
    $str .= '<p>Your text here.</p>' . PHP_EOL;
    $str .= '</article>' . PHP_EOL;

    return $str;
  }

  /**
   * Try to Detect the Device from the USER AGENT String
   *
   * @return array
   */
  private function tryDetectDevice()
  {
    /** Get the user agent (Browser, device, etc.) and assigned it to an internal variable. */
    $ua = $_SERVER['HTTP_USER_AGENT'];

    /** Match common browsers (need Safari). */
    preg_match("/(Firefox|Chrome|MSIE)[.\/]([\d.]+)/", $ua, $matches);

    /** Match IE explicitly. */
    preg_match("/(MSIE) ([\d.]+)/", $ua, $ie);

    /** Generic mobile device. */
    $detected['mobile'] = strstr( strtolower( $ua ), 'mobile' ) ? true : false;

    /** Android. */
    $detected['android'] = strstr( strtolower( $ua ), 'android' ) ? true : false;

    /** Phone. */
    $detected['phone'] = strstr( strtolower( $ua ), 'phone' ) ? true : false;

    /** Ipad. */
    $detected['ipad'] = strstr( strtolower( $ua ), 'ipad' ) ? true : false;

    /** IE (again). */
    $detected['msie'] = strstr( strtolower( $ua ), 'msie' ) ? true : false;

    /** Version. */
    $detected['version'] = isset( $matches[2] ) ? $matches[2] : null;

    /** Not serviced (lower IE versions). */
    $detected['ns'] = isset( $ie[2] ) && $ie[2] < 10 ? true : false;

    /** Return the detected array. */
    return $detected;
  }

  /**
   * Get the Detected Device
   *
   * Reduce the attempt to detect the device to a global variable which is
   * a single letter. Also set the global.
   *
   * @return string
   */
  private function getDetectedDevice()
  {
    /** Get the array containing the detected devices. */
    if ( $device = $this->tryDetectDevice() )
    {
      if ( $device['phone'] )
      {
        return 'm';
      }
      elseif ( $device['mobile'] && $device['android'] )
      {
        return 'm';
      }
      elseif ( ! $device['mobile'] && $device['android'] )
      {
        return 't';
      }
      elseif ( $device['ipad'] )
      {
        return 't';
      }
      elseif ( $device['ns'] )
      {
        return 'ns';
      }
      else
      {
        return 'd'; // desktop (default).
      }
    }
    else
    {
      return false;
    }
  }

  /**
   * Get the Request for the Device from the URL.
   *
   * These cannot conflict with any other request parameters.
   *
   * @return array|false
   */
  function getRequestedDevice()
  {
    /** If the request is for a mobile device (generic), set to mobile (m). */
    if ( isset( $_GET['m'] ) )
    {
      return 'm';
    }
    /** If the request is for a tablet, set to tablet (t). */
    elseif ( isset( $_GET['t'] ) )
    {
      return 't';
    }
    /** If the request is for a desktop (d), set to desktop (d). */
    elseif ( isset( $_GET['d'] ) )
    {
      return 'd';
    }
    /** If the request is for an hd screen, set to High Definition (hd). */
    elseif ( isset( $_GET['hd'] ) )
    {
      return 'hd';
    }
    else
    {
      /** We don't know what it is. Return false. */
      return false;
    }
  }

  /**
   * Get the Theme to Deliver
   *
   * Merge the Detected Device with the Theme to Deliver.
   * Give priority to the requested format.
   *
   * @param array $device
   *
   * @return string
   */
  function getThemeToDeliver( $device )
  {
    if ( 'm' == $device['requested'] )
    {
      return 'm';
    }
    elseif ( 't' == $device['requested'] )
    {
      return 't';
    }
    elseif ( 'd' == $device['requested'] )
    {
      return 'd'; // Desktop
    }
    elseif ( 'hd' == $device['requested'] )
    {
      return 'hd';
    }
    else
    {
      /** Return whatever device is detected. */
      return $device['detected'];
    }
  }

  /**
   * Get Javascript
   *
   * window.innerWidth  //includes scrollbar
   * window.outerWidth
   * window.innerHeight //includes scrollbar
   * window.outerHeight
   * window.fullScreen
   *
   * @link https://developer.mozilla.org/en-US/docs/Web/API/Window/  Reference
   *
   * @return string
   */
  private function getJavascript()
  {
    $str = '<script>' . PHP_EOL;
    $str .= '  if (typeof window.innerWidth !== "undefined"';
    $str .= '  && typeof window.innerHeight !== "underfined") {' . PHP_EOL;
    $str .= '  var foo = document.getElementsByTagName("html");' . PHP_EOL;
    $str .= '  foo[0].classList.add("screen-wide");' . PHP_EOL;
    $str .= '  }' . PHP_EOL;
    $str .= '</script>' . PHP_EOL;
    return $str;
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
  public function getPageHtml( $article )
  {
    $html = '<!DOCTYPE html>' . PHP_EOL;
    $html .= '<html ';
    $html .= 'class="dynamic';
    $html .= ' ' . $this->opts['page']['theme']['name'];
    $html .= isset( $article['class'] ) ? ' ' . $article['class'] : '';
    $html .= '"';
    $html .= ' lang="en-CA"';
    $html .= '>' . PHP_EOL;
    $html .= '<head>' . PHP_EOL;
    $html .= '<meta charset="UTF-8">' . PHP_EOL;
    $html .= '<meta name="viewport" content="width=device-width, initial-scale=1"/>' . PHP_EOL;
    $html .= sprintf( '<title>%s</title>%s', $this->opts['page']['title'], PHP_EOL);
    $html .= $this->opts['page']['robots']['index'] ? '' : '<meta name="robots" content="noindex,nofollow" />' . PHP_EOL;
    $html .= $this->opts['page']['css']['load'] ? sprintf('<link rel=stylesheet href="%s">%s', $this->opts['page']['css']['url'], PHP_EOL) : '';
    $html .= '</head>' . PHP_EOL;
    $html .= '<body>' . PHP_EOL;
    $html .= '<main>' . PHP_EOL;
    $html .= $article;
    $html .= '</main>' . PHP_EOL;
    if ( $this->opts['page']['footer']['load'] )
    {
      $html .= '<footer>' . PHP_EOL;
      $html .= '<div class="text-center"><small>';
      $html .= sprintf( 'Note: This page has been <a href="%s">', $this->opts['page']['url'] );
      $html .= 'automatically generated</a>. No header, footer, menus or sidebars are available.';
      $html .= '</small></div>' . PHP_EOL;
      $html .= '</footer>' . PHP_EOL;
    }
    $html .= $this->opts['page']['javascript'] ? $this->getJavascript() : '';
    $html .= '</body>' . PHP_EOL;
    $html .= '</html>' . PHP_EOL;

    /** Return the string. */
    return $html;
  }

} // End class

/** Helper Function */
if ( ! function_exists( 'pre_dump' ) )
{
  function pre_dump( $arr )
  {
    echo "<pre>" . PHP_EOL;
    var_dump( $arr );
    echo "</pre>" . PHP_EOL;
  }
}

/** Environment Check. */
if( function_exists( 'add_shortcode' ) )
{
  /** No direct access (NDA). */
  defined('ABSPATH') || exit('NDA');

  /**
   * WordPress global function.
   *
   * This function name could be the same as when not used within WordPress (below).
   * However, there may be some WordPress specific functionality that could be added.
   * For example, the function wp_is_mobile() exists, but only determines whether
   * or not the device is mobile. It does not provide the richer context sought
   * here. wp_is_mobile() could be combined with wp_device() as is here, but at
   * a later date (>2018).
   *
   * @return array  Device detected. Device Requested. Theme to Deliver.
   */
  function wp_device()
  {
    $device = new Device();
    return $device->get();
  }
}
/** Else Instantiate the Class Directly (not in WordPress). */
else
{
  /**
   * EC Device
   *
   * Gets the device based on a bunch of logic (see above).
   *
   * @return array  Device detected. Device Requested. Theme to Deliver.
   */
  function ec_device()
  {
    $device = new Device();
    return $device->get();
  }

  if( 1 )
  {
    $device = new Device();
    echo $device->html();
  }
}
