<?php
/**
 * Unit Test File
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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_stream_in_uri extends \r8\Test\TestCase\File
{

    public function testInvalidURI ()
    {
        try {
            new \r8\Stream\In\URI( "This is not a file" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Permissions $err ) {
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
            new \r8\Stream\In\URI( $this->file );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Permissions $err ) {
            $this->assertSame(
                    "Could not open URI for reading",
                    $err->getMessage()
                );
        }
    }

    public function testRead ()
    {
        $stream = new \r8\Stream\In\URI( $this->file );

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
        $stream = new \r8\Stream\In\URI( $this->file );

        $this->assertSame( "", $stream->read(0) );

        $this->assertSame( "This is a ", $stream->read(10) );
    }

    public function testReadAll_fromStart ()
    {
        $stream = new \r8\Stream\In\URI( $this->file );

        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file",
                $stream->readAll()
            );
    }

    public function testReadAll_fromOffset ()
    {
        $stream = new \r8\Stream\In\URI( $this->file );

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
        $stream = new \r8\Stream\In\URI( $this->file );

        $this->assertSame( "This is a ", $stream->read(10) );

        $this->assertSame( $stream, $stream->rewind() );

        $this->assertSame( "This is a ", $stream->read(10) );
    }

    public function testClose ()
    {
        $stream = new \r8\Stream\In\URI( $this->file );

        $this->assertTrue( $stream->canRead() );

        $this->assertSame( $stream, $stream->close() );

        $this->assertFalse( $stream->canRead() );
    }

}

?>