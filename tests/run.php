#!/usr/bin/php
<?php
/**
 * Given a file name, this will try to find and run it's unit test
 *
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 */

// Grab the first argument passed in
$test = realpath( $_SERVER['argv'][1] );

// They shouldn't be able to run this file
if ( $test == __FILE__ )
    die("This file can not be run as a unit test");

$test = preg_replace('/\.php$/i', '', $test);

// Split the string based on it's forward slashes
// This will allow us to remove any leading directories that dont belong
$test = explode("/", $test);

$cutoff = array_search( "src", $test );

if ( $cutoff === FALSE ) {

    $cutoff = array_search( "tests", $test );

    if ( $cutoff === FALSE )
        die ( "Could not locate 'src' or 'tests' directory in given file: ". $_SERVER['argv'][1] );
}

// Remove the leading directories
$test = array_slice($test, $cutoff + 1);

// Ensure the file they are requesting actually exists
$file = __DIR__ ."/". implode("/", $test) .".php";
if ( !is_file($file) )
    die("Could not locate unit test file: ". $file);

// Finally... execute phpunit
system( "phpunit ". escapeshellarg( implode("_", $test) ) ." ". escapeshellarg($file) );

?>