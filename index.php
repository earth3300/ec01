<?php

if ( file_exists( __DIR__ . '/main.php' ) ){
	require_once( __DIR__ . '/main.php' );
}
else {
	echo "Missing a required file";
}
