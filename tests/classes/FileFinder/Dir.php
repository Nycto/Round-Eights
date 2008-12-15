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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filefinder_dir extends PHPUnit_Framework_TestCase
{

    public function testDirAccessors ()
    {
        $finder = new \cPHP\FileFinder\Dir;

        $this->assertEquals( new \cPHP\Ary, $finder->getDirs() );

        $this->assertSame( $finder, $finder->addDir("/root/dir") );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        new \cPHP\FileSys\Dir("/root/dir")
                    )),
                $finder->getDirs()
            );


        $this->assertSame( $finder, $finder->addDir("") );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        new \cPHP\FileSys\Dir("/root/dir"),
                        new \cPHP\FileSys\Dir("")
                    )),
                $finder->getDirs()
            );


        $this->assertSame(
                $finder,
                $finder->addDir( new \cPHP\FileSys\Dir("path/to/dir") )
            );
        $this->assertEquals(
                new \cPHP\Ary(array(
                        new \cPHP\FileSys\Dir("/root/dir"),
                        new \cPHP\FileSys\Dir(""),
                        new \cPHP\FileSys\Dir("path/to/dir")
                    )),
                $finder->getDirs()
            );

        $this->assertSame( $finder, $finder->clearDirs() );
        $this->assertEquals( new \cPHP\Ary, $finder->getDirs() );
    }

}

?>