<?php

if ( 1 ) {
defined('NDA') || exit('Access denied.');
}

/**
 * Plugin Name: WP Bundle Developer OOP Trials
 * Plugin URI: http://wp.cbos.ca/plugins/wb-developer-oop-trial
 * Description: A very basic plugin using OOP (Interface, Abstract Class, Class and Function). NO DocBlocks.
 * Version: 2018.09.11
 * Author: wp.cbos.ca
 * Author URI: http://wp.cbos.ca
 * License: GPLv2+
 */

interface iInterface
{
	public function echoHello();

	public function returnHello();
}

abstract class aClass implements iInterface
{
	public function returnHello()
	{
		return "Hello";
	}
}

class cClass extends aClass
{
	public function echoHello()
	{
		$hello = aClass::returnHello();
		echo $hello;
	}
}

$oop = new cClass();
$oop->echoHello();
