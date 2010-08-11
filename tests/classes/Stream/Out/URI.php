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
class classes_Stream_Out_Uri extends \r8\Test\TestCase\File
{

    public function testNoPermissiosn ()
    {
        chmod( $this->file, 0000 );

        try {
            new \r8\Stream\Out\URI( $this->file );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\FileSystem\Permissions $err ) {
            $this->assertSame(
                    "Could not open URI for writing",
                    $err->getMessage()
                );
        }
    }

    public function testWrite ()
    {
        $stream = new \r8\Stream\Out\URI( $this->file );

        $this->assertSame( $stream, $stream->write("Data") );

        $stream->close();

        $this->assertSame(
                "Data",
                file_get_contents( $this->file )
            );
    }

    public function testAppend ()
    {
        $stream = new \r8\Stream\Out\URI( $this->file, TRUE );

        $this->assertSame( $stream, $stream->write("\nData") );

        $stream->close();

        $this->assertSame(
                "This is a string\n"
                ."of data that is put\n"
                ."in the test file\n"
                ."Data",
                file_get_contents( $this->file )
            );
    }

}

?>