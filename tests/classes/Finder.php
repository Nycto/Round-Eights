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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * unit tests
 */
class classes_Finder extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        try {
            new \r8\Finder( "", $this->getMock('\r8\iface\Finder') );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testFind_Volatile ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( NULL ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        try {
            $finder->find("file.ext", TRUE);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Finder\Missing $err ) {
            $this->assertSame( "Finder was unable to locate file", $err->getMessage() );
        }
    }

    public function testFind_Unfound ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( NULL ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        $this->assertNull( $finder->find("file.ext") );
    }

    public function testFind_Found ()
    {
        $result = new \r8\Finder\Result('base', 'path');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( $result ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        $this->assertSame( $result, $finder->find("file.ext") );
    }

    public function testFindFile_Unfound ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( NULL ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        $this->assertNull( $finder->findFile("file.ext") );
    }

    public function testFindFile_Volatile ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( NULL ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        try {
            $finder->findFile("file.ext", TRUE);
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Finder\Missing $err ) {
            $this->assertSame( "Finder was unable to locate file", $err->getMessage() );
        }
    }

    public function testFindFile_Found ()
    {
        $file = $this->getMock('\r8\FileSys');

        $result = $this->getMock('\r8\Finder\Result', array(), array('base', 'path'));
        $result->expects( $this->once() )
            ->method( "getFile" )
            ->will( $this->returnValue( $file ) );

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->isInstanceOf('\r8\Finder\Tracker'),
                $this->equalTo("/base"),
                $this->equalTo("file.ext")
            )
            ->will( $this->returnValue( $result ) );

        $finder = new \r8\Finder( "/base", $wrapped );

        $this->assertSame( $file, $finder->findFile("file.ext") );
    }

}

?>