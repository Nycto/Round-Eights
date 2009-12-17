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
class classes_filefinder_dirlist extends PHPUnit_Dir_Framework_TestCase
{

    public function testNoDirs ()
    {
        $mock = $this->getMock( 'r8\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array() ) );

        $this->assertNull( $mock->find( "file/to/find" ) );
    }

    public function testObjectList_simple ()
    {
        $mock = $this->getMock( 'r8\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    new \r8\FileSys\Dir("/not/a/real/dir"),
                    new \r8\FileSys\Dir($this->dir),
                    new \r8\FileSys\Dir($this->dir ."/third")
                ) ) );

        $result = $mock->find( "third/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('r8\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_simple ()
    {
        $mock = $this->getMock( 'r8\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    "/not/a/real/dir",
                    $this->dir,
                    $this->dir ."/third"
                ) ) );

        $result = $mock->find( "third/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('r8\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_withRoot ()
    {
        $mock = $this->getMock( 'r8\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    "/not/a/real/dir",
                    $this->dir,
                    $this->dir ."/third"
                ) ) );

        $result = $mock->find( "/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('r8\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_notFound ()
    {
        $mock = $this->getMock( 'r8\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    "/not/a/real/dir",
                    $this->dir,
                    $this->dir ."/third"
                ) ) );

        $this->assertNull( $mock->find( "file/to/find" ) );
    }

}

?>