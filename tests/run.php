#!/usr/bin/php
<?php
/**
 * Given a file name, this will try to find and run it's unit test
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
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