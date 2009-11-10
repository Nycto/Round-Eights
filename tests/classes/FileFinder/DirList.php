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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_filefinder_dirlist extends PHPUnit_Dir_Framework_TestCase
{

    public function testNoDirs ()
    {
        $mock = $this->getMock( 'h2o\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array() ) );

        $this->assertNull( $mock->find( "file/to/find" ) );
    }

    public function testObjectList_simple ()
    {
        $mock = $this->getMock( 'h2o\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    new \h2o\FileSys\Dir("/not/a/real/dir"),
                    new \h2o\FileSys\Dir($this->dir),
                    new \h2o\FileSys\Dir($this->dir ."/third")
                ) ) );

        $result = $mock->find( "third/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_simple ()
    {
        $mock = $this->getMock( 'h2o\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    "/not/a/real/dir",
                    $this->dir,
                    $this->dir ."/third"
                ) ) );

        $result = $mock->find( "third/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_withRoot ()
    {
        $mock = $this->getMock( 'h2o\FileFinder\DirList', array("getDirs") );

        $mock->expects( $this->once() )
            ->method("getDirs")
            ->will( $this->returnValue( array(
                    "/not/a/real/dir",
                    $this->dir,
                    $this->dir ."/third"
                ) ) );

        $result = $mock->find( "/third-one" );

        $this->assertThat( $result, $this->isInstanceOf('h2o\FileSys\File') );
        $this->assertSame(
                $this->dir ."/third/third-one",
                $result->getPath()
            );
    }

    public function testStringList_notFound ()
    {
        $mock = $this->getMock( 'h2o\FileFinder\DirList', array("getDirs") );

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