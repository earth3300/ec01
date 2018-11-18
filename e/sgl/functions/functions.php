<?php
/** Functions
 *
 * A collection of functions that may be useful.
 */

/**
 * Gets the File Size, with the Correct Suffix Appended.
 *
 * @param array $file
 * @param int $dec decimals
 *
 * @return string
 *
 * @link http://php.net/manual/en/function.filesize.php
 */
private function getFileSizeFormatted( $file, $dec = 2 )
{
  /** Get the file size in bytes. */
  $bytes =  filesize( $file['patf'] );

  /** Find the factor */
  $factor = floor( ( strlen( $bytes ) - 1 ) / 3 );

  if ( $factor > 0 )
  {
    $prefix = 'KMGT';
  }

  $size = sprintf("%.{$dec} f", $bytes / pow( 1024, $factor ) ) . @$prefix[ $factor - 1 ] . 'B';

  return $size;
  }
}
