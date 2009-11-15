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
class classes_Session_Namespaced extends PHPUnit_Framework_TestCase
{

    public function testConstruct_Error ()
    {
        $sess = $this->getMock('h2o\iface\Session');

        try {
            new \h2o\Session\Namespaced( "   ", $sess );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must be a valid key", $err->getMessage() );
        }
    }

    public function testGet_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->get("key") );
    }

    public function testGet_NotFound ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( Array() ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertNull( $ns->get("key") );
    }

    public function testGet_Found ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( Array( "key" => "Data" ) ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( "Data", $ns->get("key") );
    }

    public function testSet_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array("key" => "Value")) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->set("key", "Value") );
    }

    public function testSet_Array ()
    {
        $sess = $this->getMock('h2o\iface\Session');
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

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->set("key", "Value") );
    }

    public function testExists_Array ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->exactly(3) )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "Data", "key2" => NULL ) ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertTrue( $ns->exists("key") );
        $this->assertFalse( $ns->exists("NonKey") );
        $this->assertFalse( $ns->exists("key2") );
    }

    public function testExists_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "String" ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertFalse( $ns->exists("key") );
    }

    public function testClear_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "String" ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array()) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testClear_Array ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "key" => "Value", "i" => "v" ) ) );
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array( "i" => "v" )) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testClear_NonExisting ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v" ) ) );
        $sess->expects( $this->never() )->method( "set" );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clear("key") );
    }

    public function testPush_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
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

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPush_NonArrayValue ()
    {
        $sess = $this->getMock('h2o\iface\Session');
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

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPush_Array ()
    {
        $sess = $this->getMock('h2o\iface\Session');
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

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->push("key", "Value") );
    }

    public function testPop ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testClearAll ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("ns"), $this->equalTo(array()) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( $ns, $ns->clearAll() );
    }

    public function testGetAll_Array ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( array( "i" => "v" ) ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( array( "i" => "v" ), $ns->getAll() );
    }

    public function testGetAll_NonArray ()
    {
        $sess = $this->getMock('h2o\iface\Session');
        $sess->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("ns") )
            ->will( $this->returnValue( "Blah" ) );

        $ns = new \h2o\Session\Namespaced( "ns", $sess );

        $this->assertSame( array(), $ns->getAll() );
    }

}

?>