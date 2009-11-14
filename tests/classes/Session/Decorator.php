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
class classes_Session_Decorator extends PHPUnit_Framework_TestCase
{

    public function testGet ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("Data") );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertSame( "Data", $sess->get("key") );
    }

    public function testSet ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("key"), $this->equalTo("Data") );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertSame( $sess, $sess->set("key", "Data") );
    }

    public function testExists ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertTrue( $sess->exists("key") );
    }

    public function testClear ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "clear" )
            ->with( $this->equalTo("key") );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertSame( $sess, $sess->clear("key") );
    }

    public function testClearAll ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "clearAll" );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertSame( $sess, $sess->clearAll() );
    }

    public function testGetAll ()
    {
        $wrapped = $this->getMock('h2o\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "getAll" )
            ->will( $this->returnValue( array( "key" => "Data" ) ) );

        $sess = $this->getMock('h2o\Session\Decorator', array('_mock'), array($wrapped));

        $this->assertSame( array( "key" => "Data" ), $sess->getAll() );
    }

}

?>