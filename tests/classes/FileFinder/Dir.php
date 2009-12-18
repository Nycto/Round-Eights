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
class classes_filefinder_dir extends PHPUnit_Framework_TestCase
{

    public function testDirAccessors ()
    {
        $finder = new \r8\FileFinder\Dir;

        $this->assertEquals( array(), $finder->getDirs() );

        $this->assertSame( $finder, $finder->addDir("/root/dir") );
        $this->assertEquals(
                array(
                        new \r8\FileSys\Dir("/root/dir")
                    ),
                $finder->getDirs()
            );


        $this->assertSame( $finder, $finder->addDir("") );
        $this->assertEquals(
                array(
                        new \r8\FileSys\Dir("/root/dir"),
                        new \r8\FileSys\Dir("")
                    ),
                $finder->getDirs()
            );


        $this->assertSame(
                $finder,
                $finder->addDir( new \r8\FileSys\Dir("path/to/dir") )
            );
        $this->assertEquals(
                array(
                        new \r8\FileSys\Dir("/root/dir"),
                        new \r8\FileSys\Dir(""),
                        new \r8\FileSys\Dir("path/to/dir")
                    ),
                $finder->getDirs()
            );

        $this->assertSame( $finder, $finder->clearDirs() );
        $this->assertEquals( array(), $finder->getDirs() );
    }

}

?>