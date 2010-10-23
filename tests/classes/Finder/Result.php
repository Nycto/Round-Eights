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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Finder_Result extends PHPUnit_Framework_TestCase
{

    public function testConstruct_error ()
    {
        try {
            new \r8\Finder\Result( "", "path" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            new \r8\Finder\Result( "base", "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testAccessors ()
    {
        $result = new \r8\Finder\Result( "/test/path/", "file.ext" );

        $this->assertSame( "/test/path", $result->getBase() );
        $this->assertSame( "file.ext", $result->getPath() );
    }

    public function testGetFile ()
    {
        $result = new \r8\Finder\Result( "/test/path/./", "file.ext" );

        $file = $result->getFile();
        $this->assertThat( $file, $this->isInstanceOf( '\r8\FileSys' ) );
        $this->assertSame( "/test/path/file.ext", $file->getPath() );

        $this->assertNotSame( $file, $result->getFile() );
        $this->assertNotSame( $file, $result->getFile() );
    }

    public function testGetAbsolute ()
    {
        $result = new \r8\Finder\Result( "/test/path/./", "file.ext" );
        $this->assertSame( "/test/path/file.ext", $result->getAbsolute() );
    }

}

