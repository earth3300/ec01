<?php
/**
 * CSS Minifier Data
 *
 * @var [type]
 */

 namespace Earth3300\EC01;

 class Minifier extends CSSProcessor
{

  /**
   * Minifier Data
   *
   * Keys as input. Values as output.
   *
   * @return [type] [description]
   */
  protected function data()
  {
  	$js = [ "js/main.js" => "js/main.min.js" ];

  	$css = [ "css/main.css" => "css/main.min.css" ];
  }
  
} // End Minifier
