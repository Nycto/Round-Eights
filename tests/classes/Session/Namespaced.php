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
class classes_Session_Namespaced extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        $sess = $this->getMock('r8\iface\Session');

        try {
            new \r8\Session\Namespaced( "   ", $sess );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid key", $err->getMessage() );
        }
    }

    public function testGet_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->get("key") );
    }

    public function testGet_NotFound ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( Array() ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->get("key") );
    }

    public function testGet_Found ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( Array( "key" => "Data" ) ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( "Data", $ns->get("key") );
    }

    public function testSet_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array("key" => "Value")) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->set("key", "Value") );
    }

    public function testSet_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "over", "key2" => "data" ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo("ns"),
                $this->equalTo(array( "key" => "Value", "key2" => "data" ))
            );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->set("key", "Value") );
    }

    public function testExists_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->exactly(3) )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "Data", "key2" => NULL ) ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertTrue( $ns->exists("key") );
        $this->assertFalse( $ns->exists("NonKey") );
        $this->assertFalse( $ns->exists("key2") );
    }

    public function testExists_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "String" ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertFalse( $ns->exists("key") );
    }

    public function testClear_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "String" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array()) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testClear_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "Value", "i" => "v" ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array( "i" => "v" )) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testClear_NonExisting ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v" ) ) );
        $sess->expects( $this->never() )->method( "set" );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testPush_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo("ns"),
                $this->equalTo( array("key" => array("Value")) )
            );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPush_NonArrayValue ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "Blah" ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo("ns"),
                $this->equalTo( array("key" => array("Blah", "Value")) )
            );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPush_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => array("Blah") ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo("ns"),
                $this->equalTo( array("key" => array("Blah", "Value")) )
            );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPop_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo( array() ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->pop("key") );
    }

    public function testPop_NonExisting ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array() ) );
        $sess->expects( $this->never() )->method( "set" );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->pop("key") );
    }

    public function testPop_NonArrayValue ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v", "key" => "Blah" ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo( array("i" => "v") ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( "Blah", $ns->pop("key") );
    }

    public function testPop_ToEmpty ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v", "key" => array("Blah") ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo( array("i" => "v") ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( "Blah", $ns->pop("key") );
    }

    public function testPop_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue(
                array( "i" => "v", "key" => array("1st", "2nd") )
            ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with(
                $this->equalTo("ns"),
                $this->equalTo( array("i" => "v", "key" => array("1st")) )
            );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( "2nd", $ns->pop("key") );
    }

    public function testClearAll ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array()) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clearAll() );
    }

    public function testGetAll_Array ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v" ) ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( array( "i" => "v" ), $ns->getAll() );
    }

    public function testGetAll_NonArray ()
    {
        $sess = $this->getMock('r8\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );

        $ns = new \r8\Session\Namespaced( "ns", $sess );

        $this->assertSame( array(), $ns->getAll() );
    }

}

