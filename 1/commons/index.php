<?php

/** Used to ensure files are not accessed directly. */
define( 'NDA', true );

/** Call a separate file in case this one is overwritten */
if ( file_exists( __DIR__ . '/main.php' ) ) {
	require_once( __DIR__ . '/main.php' );
}
else {
	echo "<p>The main.php file is required to continue.</p>" . PHP_EOL;
}

