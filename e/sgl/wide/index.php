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
 * File: index.php
 * Created: 2015-00-00
 * Updated: 2018-12-03
 * Time: 11:24 EST
 */

/**
 * WideScreen
 *
 * Functions related to optimizing the use of a high definition, wide screen
 * in the range of ~21" or greater (1920px x 1080px or better).
 */
class WideScreen
{
  /**
   * Try to Detect the Device ffrom the USER AGENT String
   *
   * @return array
   */
  function tryDetectDevice()
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
  function getDetectedDevice()
  {
    /** Get the array containing the detected devices. */
    if ( $device = $this->tryDetectDevice() )
    {

      global $detected;

      if ( $device['phone'] )
      {
          $detected = 'm';
          return $detected;
      }
      elseif ( $device['mobile'] && $device['android'] )
      {
          $detected = 'm';
          return $detected;
      }
      elseif ( ! $device['mobile'] && $device['android'] )
      {
          $detected = 't';
          return $detected;
      }
      elseif ( $device['ipad'] )
      {
          $detected = 't';
          return $detected;
      }
      elseif ( $device['ns'] )
      {
          $detected = 'ns'; //not serviced
          return $detected;
      }
      else {
          $detected = 'd';
          return $detected;
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
   * Also set the global.
   *
   * @return array|false
   */
  function getDeviceRequest()
  {
      /** Set the device type as a global. */
      global $device;

      /** If the device reuquest is for a tablet, set the global to tablet (t). */
      if ( isset( $_GET['t'] ) )
      {
          $device = 't';
          return $device;
      }

      /** If the device request is for a mobile device (generic), set the global to mobile (m). */
      elseif ( isset( $_GET['mobile'] ) )
      {
          $device = 'm';
          return $device;
      }
      /** If the device request is for a desktop (d), set the global to tablet (d). */
      elseif ( isset( $_GET['d'] ) )
      {
          $device = 'd';
          return $device;
      }
      /** If the device request is for a hd screen, set the global to tablet (hd). */
      elseif ( isset( $_GET['hd'] ) )
      {
          $device = 'hd';
          return $device;
      }
      else
      {
        /** We don't know what it is. Set it to false so it can be used as a boolean. */
          $device = false;
          return $device;
      }
  }
  $this->getDeviceRequest();

  /**
   * Get the Theme to Deliver
   *
   * Merge the Detected Device with the Theme to Deliver.
   *
   * @return string
   */
  function getDeliveredTheme()
  {
    /** Use this, if nothing else. */
    global $detected;

    /** The simplified device. */
    global $device;

    /** The theme to deliver. */
    global $delivered;


    if ( $device == 'm' ) {
        $delivered = 'm';
    }
    else if ( $device == 't' ) {
        $delivered = 't';
    }
    else if ( $device == 'd' ){
        $delivered = 'd';
    }
    else if ( $device == 'hd' ) {
        $delivered = 'hd';
    }
    else {
        $delivered = $detected;
    }
  }
  $this->getDeliveredTheme();
}
