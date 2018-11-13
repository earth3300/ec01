<?php
/**
 * EC01 Optional Files (Earth3300\EC01)
 *
 * This loads the optional files.
 *
 * These files may be in this directory (/optional) or in sub directories,
 * within this directory, as needed.
 *
 * File: index.php
 * Created: 2018-11-13
 * Updated: 2018-11-13
 * Time: 10:15 EST
 */

namespace Earth3300\EC01;

/** No direct access (NDA). */
defined('NDA') || exit('NDA');

/** Contains optional information. */
if ( file_exists( __DIR__ . '/data.php' ) )
{
  /** Uses optional data to help define the site */
  require_once( __DIR__ . '/data.php' );

  /** Use tiers (to construct more complex headers). */
  define('SITE_USE_TIERS', true );

  if ( SITE_USE_TIERS )
  {
    if ( file_exists( __DIR__ . '/tiers.php' ) )
    {
      /** Site has tiers. Let it know that. */
      define('SITE_HAS_TIERS', true );

      /** Works together with the data file. */
      require_once( __DIR__ . '/tiers.php' );
    }
    else {
      /** Site does not have tiers. Let it know that. */
      define('SITE_HAS_TIERS', false );
      echo "The file <code>tiers.php</code> is not available.";
    }
  }
}
else
{
  /** Don't use tiers. */
  define('SITE_USE_TIERS', false );
}
