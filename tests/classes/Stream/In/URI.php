<?php
/**
 * Unit Test File
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
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_stream_in_uri extends PHPUnit_TestFile_Framework_TestCase
{

    public function testInvalidURI ()
    {
        try {
            new \cPHP\Stream\In\URI( "This is not a file" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Permissions $err ) {
            $this->assertSame(
                    "Could not open URI for reading",
                    $err->getMessage()
                );
        }
    }

    public function testNoPermissiosn ()
    {
        chmod( $this->file, 0000 );

        try {
            new \cPHP\Stream\In\URI( $this->file );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\FileSystem\Permissions $err ) {
            $this->assertSame(
                    "Could not open URI for reading",
                    $err->getMessage()
                );
        }
    }

    public function testRead ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $this->assertSame( "This is a ", $stream->read(10) );
        $this->assertTrue( $stream->canRead() );

        $this->assertSame( "string\nof data that ", $stream->read(20) );
        $this->assertTrue( $stream->canRead() );

        $this->assertSame( "is put\nin ", $stream->read(10) );
        $this->assertTrue( $stream->canRead() );

        $this->assertSame( "the test file", $stream->read(20) );
        $this->assertFalse( $stream->canRead() );
        $this->assertFalse( $stream->canRead() );
    }

    public function testRead_Zero ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $this->assertSame( "", $stream->read(0) );

        $this->assertSame( "This is a ", $stream->read(10) );
    }

    public function testReadAll_fromStart ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $this->assertSame(
        		"This is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $stream->readAll()
            );
    }

    public function testReadAll_fromOffset ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $stream->read(5);

        $this->assertSame(
        		"is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $stream->readAll()
            );
    }

    public function testRewind ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $this->assertSame( "This is a ", $stream->read(10) );

        $this->assertSame( $stream, $stream->rewind() );

        $this->assertSame( "This is a ", $stream->read(10) );
    }

    public function testClose ()
    {
        $stream = new \cPHP\Stream\In\URI( $this->file );

        $this->assertTrue( $stream->canRead() );

        $this->assertSame( $stream, $stream->close() );

        $this->assertFalse( $stream->canRead() );
    }

}

?>