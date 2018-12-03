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
 * Time: 15:02 EST
 */

namespace Earth3300\EC01;

/**
 * DeviceScreen
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
  protected static $screen = [];

  /**
   * [set description]
   * @param [type] $device [description]
   */
  public function set( $device )
  {
    $this->screen = $device;
  }

  /**
   * Get
   *
   * Get the device being requested.
   *
   * @return array
   */
  public static function get()
  {
    /** Try to detect the device. */
    $device['detected'] = $this->getDetectedDevice();

    /** Get the device requested (if any) from the URL query string. */
    $device['requested'] = $this->getRequestedDevice();

    /** Determine the priority and the theme to deliver. */
    $device['theme'] = $this->getThemeToDeliver();

    return $device;
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
  $this->getDetectedDevice();

  /**
   * Get the Request for the Device from the URL.
   *
   * These cannot conflict with any other request parameters.
   *
   * @return array|false
   */
  function getDeviceRequest()
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
} // End class

/** Environment Check. */
if( function_exists( 'add_shortcode' ) )
{
  /** No direct access (NDA). */
  defined('ABSPATH') || exit('NDA');

  /**
   * WordPress global function.
   *   *
   * @param array  $args['dir']
   *
   * @return string  HTML as a list of images, wrapped in the article element.
   */
  function wp_device()
  {
    return Device::screen;
  }
}

/** Else Instantiate the Class Directly (not in WordPress). */
else
{
  $device = new DeviceScreen();
  echo $device::screen();
}
