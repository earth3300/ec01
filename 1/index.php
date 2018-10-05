<?php

/**
 * The index file to the HTML directory as a peer to the "engine" directory.
 */
if ( file_exists( __DIR__ . '/../index.php' ) )
{
	require_once( __DIR__ . '/../index.php' );
}
else
{
	echo "Missing the <code>/index.php</code> file.";
}
