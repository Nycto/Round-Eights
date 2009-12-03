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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_stream_in_file extends PHPUnit_TestFile_Framework_TestCase
{

    public function testInvalidFile ()
    {
        try {
            new \r8\Stream\In\File(
                new \r8\FileSys\File( "This is not a file" )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testUnreadable ()
    {
        chmod( $this->file, 0000 );

        try {
            new \r8\Stream\In\File(
                new \r8\FileSys\File( $this->file )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Permissions $err ) {
            $this->assertSame( "File is not readable", $err->getMessage() );
        }
    }

    public function testRead ()
    {
        $stream = new \r8\Stream\In\File(
                new \r8\FileSys\File( $this->file )
            );

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

}

?>