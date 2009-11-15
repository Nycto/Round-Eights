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

    public function testSet ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testExists ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testClear ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testPush ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testPop ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testClearAll ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testGetAll ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>