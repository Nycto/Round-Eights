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
class classes_Finder_BaseDir extends PHPUnit_Framework_TestCase
{

    public function testAddDir ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');

        $dir = new \r8\Finder\BaseDir( $wrapped );
        $this->assertSame( array(), $dir->getDirs() );

        $this->assertSame( $dir, $dir->addDir("subdir") );
        $this->assertSame( array("subdir"), $dir->getDirs() );

        $this->assertSame( $dir, $dir->addDir("/dir/path/") );
        $this->assertSame( array("subdir", "/dir/path"), $dir->getDirs() );
    }

    public function testConstruct ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $dir = new \r8\Finder\BaseDir( $wrapped, "subdir", "/dir/path/" );
        $this->assertSame( array("subdir", "/dir/path"), $dir->getDirs() );
    }

    public function testFind_FirstDir ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("first"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( $result ) );

        $dir = new \r8\Finder\BaseDir( $wrapped, "first", "second" );

        $this->assertSame( $result, $dir->find( $tracker, "base", "file" ) );
    }

    public function testFind_SecondDir ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("first"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( NULL ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("second"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( $result ) );

        $dir = new \r8\Finder\BaseDir( $wrapped, "first", "second" );

        $this->assertSame( $result, $dir->find( $tracker, "base", "file" ) );
    }

    public function testFind_Unfound ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("first"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("second"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( FALSE ) );

        $dir = new \r8\Finder\BaseDir( $wrapped, "first", "second" );

        $this->assertNULL( $dir->find( $tracker, "base", "file" ) );
    }

}

?>