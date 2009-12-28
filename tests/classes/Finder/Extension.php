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
class classes_Finder_Extension extends PHPUnit_Framework_TestCase
{

    public function testAddExt ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');

        $ext = new \r8\Finder\Extension( $wrapped );
        $this->assertSame( array(), $ext->getExts() );

        $this->assertSame( $ext, $ext->addExt("one") );
        $this->assertSame( array("one"), $ext->getExts() );

        $this->assertSame( $ext, $ext->addExt(".two") );
        $this->assertSame( array("one", "two"), $ext->getExts() );
    }

    public function testConstruct ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');
        $ext = new \r8\Finder\Extension( $wrapped, "one", ".two" );
        $this->assertSame( array("one", "two"), $ext->getExts() );
    }

    public function testFind_NoExts ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file")
            )
            ->will( $this->returnValue( TRUE ) );

        $ext = new \r8\Finder\Extension( $wrapped );

        $this->assertTrue( $ext->find( $tracker, "base", "file" ) );
    }

    public function testFind_FirstExt ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file.one")
            )
            ->will( $this->returnValue( TRUE ) );

        $ext = new \r8\Finder\Extension( $wrapped, "one", "two" );

        $this->assertTrue( $ext->find( $tracker, "base", "file" ) );
    }

    public function testFind_SecondExt ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file.one")
            )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file.two")
            )
            ->will( $this->returnValue( TRUE ) );

        $ext = new \r8\Finder\Extension( $wrapped, "one", "two" );

        $this->assertTrue( $ext->find( $tracker, "base", "file" ) );
    }

    public function testFind_Unfound ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file.one")
            )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("file.two")
            )
            ->will( $this->returnValue( FALSE ) );

        $ext = new \r8\Finder\Extension( $wrapped, "one", "two" );

        $this->assertFalse( $ext->find( $tracker, "base", "file" ) );
    }

}

?>