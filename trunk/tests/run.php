#!/usr/bin/php
<?php
/**
 * Given a file name, this will try to find and run it's unit test
 */

$test = $_SERVER['argv'][1];

if ( $test == __FILE__ )
    die("This file can not be run as a unit test");

$test = preg_replace('/\.php$/i', '', $test);
$test = explode("/", $test);

$cutoff = array_search( "src", $test );

if ( $cutoff === FALSE ) {
    
    $cutoff = array_search( "tests", $test );
    
    if ( $cutoff === FALSE )
        die ( "Could not locate 'src' or 'tests' directory in given file: ". $$_SERVER['argv'][1] );
}

$test = array_slice($test, $cutoff + 1);

$file = __DIR__ ."/". implode("/", $test) .".php";

if ( !is_file($file) )
    die("Could not locate unit test file: ". $file);

system( "phpunit ". implode("_", $test) ." ". $file );

?>