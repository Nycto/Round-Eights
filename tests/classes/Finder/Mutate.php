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
class classes_Finder_Mutate extends PHPUnit_Framework_TestCase
{

    public function testAddMutation ()
    {
        $wrapped = $this->getMock('\r8\iface\Finder');

        $mutate = new \r8\Finder\Mutate( $wrapped );
        $this->assertSame( array(), $mutate->getMutations() );

        $this->assertSame( $mutate, $mutate->addMutation("one", "first") );
        $this->assertSame(
            array(
                array( "from" => "one", "to" => "first" )
            ),
            $mutate->getMutations()
        );

        $this->assertSame( $mutate, $mutate->addMutation("/two/twice/", "/second/2/") );
        $this->assertSame(
            array(
                array( "from" => "one", "to" => "first" ),
                array( "from" => "two/twice", "to" => "second/2" )
            ),
            $mutate->getMutations()
        );
    }

    public function testFind_NoMutation ()
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

        $mutate = new \r8\Finder\Mutate( $wrapped );

        $this->assertTrue( $mutate->find( $tracker, "base", "file" ) );
    }

    public function testFind_PartialMatch ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("new/file")
            )
            ->will( $this->returnValue( $result ) );

        $mutate = new \r8\Finder\Mutate( $wrapped );
        $mutate->addMutation( "src", "destination" );
        $mutate->addMutation( "sub/path/", "new/" );

        $this->assertSame( $result, $mutate->find( $tracker, "base", "sub/path/file" ) );
    }

    public function testFind_FullMatch ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->once() )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("new")
            )
            ->will( $this->returnValue( $result ) );

        $mutate = new \r8\Finder\Mutate( $wrapped );
        $mutate->addMutation( "src", "destination" );
        $mutate->addMutation( "sub/path/file", "new" );

        $this->assertSame( $result, $mutate->find( $tracker, "base", "sub/path/file" ) );
    }

    public function testFind_Unfound ()
    {
        $result = $this->getMock('\r8\Finder\Result', array(), array(), '', FALSE);

        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("new")
            )
            ->will( $this->returnValue( NULL ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("second/file")
            )
            ->will( $this->returnValue( $result ) );

        $mutate = new \r8\Finder\Mutate( $wrapped );
        $mutate->addMutation( "sub/path/file", "new" );
        $mutate->addMutation( "/sub/path/", "/second/" );

        $this->assertSame( $result, $mutate->find( $tracker, "base", "sub/path/file" ) );
    }

    public function testFind_NoMatch ()
    {
        $tracker = $this->getMock('\r8\Finder\Tracker');

        $wrapped = $this->getMock('\r8\iface\Finder');
        $wrapped->expects( $this->at(0) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("new")
            )
            ->will( $this->returnValue( FALSE ) );
        $wrapped->expects( $this->at(1) )
            ->method( "find" )
            ->with(
                $this->equalTo($tracker),
                $this->equalTo("base"),
                $this->equalTo("sub/path/file")
            )
            ->will( $this->returnValue( TRUE ) );

        $mutate = new \r8\Finder\Mutate( $wrapped );
        $mutate->addMutation( "sub/path/file", "new" );

        $this->assertTrue( $mutate->find( $tracker, "base", "sub/path/file" ) );
    }

}

?>