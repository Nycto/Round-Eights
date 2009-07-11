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
 * @author James Frasca <James@RaindropPHP.com>
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
            new \h2o\Stream\In\File(
                new \h2o\FileSys\File( "This is not a file" )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem\Missing $err ) {
            $this->assertSame( "Path does not exist", $err->getMessage() );
        }
    }

    public function testUnreadable ()
    {
        chmod( $this->file, 0000 );

        try {
            new \h2o\Stream\In\File(
                new \h2o\FileSys\File( $this->file )
            );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\FileSystem\Permissions $err ) {
            $this->assertSame( "File is not readable", $err->getMessage() );
        }
    }

    public function testRead ()
    {
        $stream = new \h2o\Stream\In\File(
                new \h2o\FileSys\File( $this->file )
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