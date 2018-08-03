<?php

defined( 'FIREFLY' ) || exit;

function wlog( $blob = '' ){
	if ( ! empty( $blob ) ){
		$file = SITE_LOG_PATH . SITE_LOG_DEBUG_FILE;
		echo $file;
		file_put_contents( $file, $blob );
	}
}
