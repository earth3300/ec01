<?php

/**
Plugin Name: EC01 Filter Survey
Plugin URI: http://wp.cbos.ca/plugins/ec01-filter-survey
Description: A set of surveys designed to filter through respondents to obtain the ones best suited for the task at hand.
Version: 2018.08.20
Author: wp.cbos.ca
Author URI: http://wp.cbos.ca
License: GPLv2+
*/

defined( 'ABSPATH' ) || exit;

class EC01_Filter_Survey {

	/**
	 * Initializes the class.
	 *
	 * @since 1.0.0
	 */
	public static function init() {
		/*
		if ( null === self::$class ) {
			self::$class = new self;
		}
		return self::$class;
		*/
	}

	/**
	 * Starts the class
	 *
	 * @since 1.0.0
	 */
    public function on_loaded() {

	}

} // end class

//decouple class from WordPress hook to increase portability of class.
add_action( 'init', array ( 'EC01_Filter_Survey', 'init' ), 0 );

//see: https://carlalexander.ca/designing-class-wordpress-hooks/
