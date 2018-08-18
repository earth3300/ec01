<?php

defined( 'SITE' ) || exit;

if ( file_exists( __DIR__ . '/functions.php' ) ) {
	require_once( __DIR__ . '/functions.php' );
}
else {
	echo "The functions.php file is missing";
}
