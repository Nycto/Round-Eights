<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/Extensions/OutputTestCase.php';

/**
 * Unit test that includes all the library files and ensures there is no white space
 */
class IncludeTests
{

    /**
     * Adds the files in a directory, recursively. This will add files first
     * before moving to subdirectories.
     */
    protected static function addDir ( PHPUnit_Framework_TestSuite $suite, $dir )
    {
        // Add the files in this directory
        foreach ( new DirectoryIterator($dir) AS $path ) {

            if ( $path->isFile() && !$path->isDot() ) {
                $suite->addTest(
                        new PHPUnit_Include_Framework_TestCase(
                                "testInclude",
                                array( $path->getPathName() ),
                                "Include File"
                            )
                    );
            }
        }

        // Now go back and add the subdirectories
        foreach ( new DirectoryIterator($dir) AS $path ) {
            if ( $path->isDir() && !$path->isDot() )
                self::addDir( $suite, $path->getPathName() );
        }
    }

    /**
     * Constructs the set of tests to be executed
     */
    public static function suite()
    {

        $srcDir = realpath( rtrim( __DIR__, "/" ) ."/../src" );
        $srcDir = rtrim( $srcDir, "/" ) ."/";

        $suite = new PHPUnit_Framework_TestSuite('RaindropPHP Include Tests');

        // Including this will also include the functions
        $suite->addTest(
                new PHPUnit_Include_Framework_TestCase(
                        "testInclude",
                        array( $srcDir ."RaindropPHP.php"),
                        "Include File"
                    )
            );

        self::addDir( $suite, $srcDir ."interfaces" );
        self::addDir( $suite, $srcDir ."classes" );

        return $suite;
    }

}

/**
 * Test class that includes a file and ensures there is no data sent to the buffer
 */
class PHPUnit_Include_Framework_TestCase extends PHPUnit_Framework_TestCase
{

    public function testInclude ( $file )
    {
        if ( !file_exists($file) )
            $this->fail("Include file does not exist");

        if ( !is_readable($file) )
            $this->fail("Include file is not readable");

        // If it is already included, just skip it. It probably got picked up
        // by the autoloader due to a dependency.
        if ( in_array( $file, get_included_files() ) )
            return;

        $files = get_included_files();

        ob_start();

        try {
            include $file;
        }

        catch (Exception $e) {
            ob_end_clean();
            throw $e;
        }

        $this->assertSame(
                "",
                ob_get_clean(),
                "Including file caused data to be output.\n"
                ."Included Files:\n"
                .implode("\n", array_diff(get_included_files(), $files))
            );
    }

}

?>