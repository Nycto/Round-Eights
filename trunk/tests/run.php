#!/usr/bin/php
<?php
/**
 * Given a file name, this will try to find and run it's unit test
 */

$file = $_SERVER['argv'][1];

if ( $file == __FILE__ )
    die("This file can not be run as a unit test");

$file = preg_replace('/\.php$/i', '', $file);
$file = explode("/", $file);

$cutoff = array_search( "src", $file );

if ( $cutoff === FALSE ) {
    
    $cutoff = array_search( "tests", $file );
    
    if ( $cutoff === FALSE )
        die ( "Could not locate 'src' or 'tests' directory" );
}

$file = array_slice($file, $cutoff + 1);

system( "phpunit ". implode("_", $file) ." ". __DIR__ ."/". implode("/", $file) .".php" );

?>