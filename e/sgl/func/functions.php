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

  /**
   * Password Validation
   *
   * @link https://stackoverflow.com/a/21456918/5449906
   *
   * @param $string
   *
   * @return bool
   */
  private function validatePassword( $password )
  {
    if ( false ) {
      /** Minimum eight characters, at least one letter and one number: */
      $regex = "/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}/";

      /** Minimum eight characters, at least one letter, one number and one special character: */
      $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/";

      /** Minimum eight characters, at least one uppercase letter, one lowercase letter and one number: */
      $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/";

      /** Minimum eight and maximum 10 characters, at least one uppercase letter, one lowercase letter, one number and one special character: */
      $regex = "/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,10}$/";
    }
    else
    {
      return false;
    }
}
