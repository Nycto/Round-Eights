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
class classes_Session_Value extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        $sess = $this->getMock('r8\iface\Session');

        try {
            new \r8\Session\Value( "   ", $sess );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid key", $err->getMessage() );
        }
    }

    public function testGet ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("idx") )
            ->will( $this->returnValue("Data") );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertSame( "Data", $value->get() );
    }

    public function testSet ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("idx"), $this->equalTo("Data") )
            ->will( $this->returnValue("Data") );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertSame( $value, $value->set( "Data" ) );
    }

    public function testExists ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("idx") )
            ->will( $this->returnValue( TRUE ) );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertTrue( $value->exists() );
    }

    public function testClear ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "clear" )
            ->with( $this->equalTo("idx") );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertSame( $value, $value->clear() );
    }

    public function testPush ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "push" )
            ->with( $this->equalTo("idx"), $this->equalTo("Data") )
            ->will( $this->returnValue("Data") );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertSame( $value, $value->push( "Data" ) );
    }

    public function testPop ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "pop" )
            ->with( $this->equalTo("idx") )
            ->will( $this->returnValue("Data") );

        $value = new \r8\Session\Value( "idx", $sess );

        $this->assertSame( "Data", $value->pop() );
    }

}

