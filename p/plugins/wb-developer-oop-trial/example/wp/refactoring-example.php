<?php

defined('NDA') || exit('Access denied');

/**
 * The abstract class provides common functionality for two classes that extend it.
 *
 * Thus,the abstract class can provide checks that would be duplicated otherwise.
 *
 * @link https://deliciousbrains.com/refactoring-php-code-better-readability/
 */

abstract class Addon
{
    protected $settings;

    protected function set_settings( $settings )
	{
        if ( ! is_array( $settings ) )
		{
            throw new \Exception( 'Invalid settings' );
        }
        $this->settings = $settings;
    }
}

class AwesomeAddon extends Addon
{
    public function __construct( $settings )
	{
        $this->set_settings( $settings );
    }

    protected function do_something_awesome()
	{
        //...
    }
}

class EvenMoreAwesomeAddon extends Addon
{
    public function __construct( $settings )
	{
        $this->set_settings( $settings );
    }

    protected function do_something_even_more_awesome()
	{
        //...
    }
}
