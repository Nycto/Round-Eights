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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Session_Transform extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test session
     *
     * @return \r8\iface\Session
     */
    public function getTestSession ( $result )
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo( "key" ) )
            ->will( $this->returnValue( "From Wrapped" ) );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo( "From Wrapped" ) )
            ->will( $this->returnValue( $result ) );

        return new \r8\Session\Transform($trans, $wrapped);
    }

    public function testGet_String ()
    {
        $sess = $this->getTestSession( serialize("Data") );
        $this->assertSame( "Data", $sess->get("key") );
    }

    public function testGet_Int ()
    {
        $sess = $this->getTestSession( serialize( 50 ) );
        $this->assertSame( 50, $sess->get("key") );
    }

    public function testGet_Float ()
    {
        $sess = $this->getTestSession( serialize( 3.14 ) );
        $this->assertSame( 3.14, $sess->get("key") );
    }

    public function testGet_NULL ()
    {
        $sess = $this->getTestSession( serialize( NULL ) );
        $this->assertNull( $sess->get("key") );
    }

    public function testGet_True ()
    {
        $sess = $this->getTestSession( serialize( TRUE ) );
        $this->assertTrue( $sess->get("key") );
    }

    public function testGet_False ()
    {
        $sess = $this->getTestSession( serialize( FALSE ) );
        $this->assertFalse( $sess->get("key") );
    }

    public function testGet_Ary ()
    {
        $sess = $this->getTestSession( serialize( array(1,2,3) ) );
        $this->assertSame( array(1,2,3), $sess->get("key") );
    }

    public function testGet_Obj ()
    {
        $sess = $this->getTestSession( serialize( new stdClass ) );
        $this->assertEquals( new stdClass, $sess->get("key") );
    }

    public function testGet_Unserializable ()
    {
        $sess = $this->getTestSession( "Unserializable" );
        $this->assertNull( $sess->get("key") );
    }

    public function testGet_NonString ()
    {
        $sess = $this->getTestSession( 1234 );
        $this->assertNull( $sess->get("key") );
    }

    public function testSet ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("idx"), $this->equalTo("transformed") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo( 's:5:"Value";' ) )
            ->will( $this->returnValue( "transformed" ) );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( $sess, $sess->set("idx", "Value") );
    }

    public function testPush_None ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(FALSE) );
        $wrapped->expects( $this->never() )->method( "get" );
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("key"), $this->equalTo("transformed") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->never() )->method( "from" );
        $trans->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo( 'a:1:{i:0;s:5:"value";}' ) )
            ->will( $this->returnValue( "transformed" ) );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( $sess, $sess->push("key", "value") );
    }

    public function testPush_NonArray ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("From Wrapped") );
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("key"), $this->equalTo("transformed") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("From Wrapped") )
            ->will( $this->returnValue( serialize("Blah") ) );
        $trans->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo( 'a:2:{i:0;s:4:"Blah";i:1;s:5:"value";}' ) )
            ->will( $this->returnValue( "transformed" ) );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( $sess, $sess->push("key", "value") );
    }

    public function testPush_Array ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("From Wrapped") );
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("key"), $this->equalTo("transformed") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("From Wrapped") )
            ->will( $this->returnValue( serialize( array("1st", "2nd") ) ) );
        $trans->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo( 'a:3:{i:0;s:3:"1st";i:1;s:3:"2nd";i:2;s:5:"value";}' ) )
            ->will( $this->returnValue( "transformed" ) );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( $sess, $sess->push("key", "value") );
    }

    public function testPop_NonExisting ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(FALSE) );
        $wrapped->expects( $this->never() )->method( "get" );
        $wrapped->expects( $this->never() )->method( "set" );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->never() )->method( "from" );
        $trans->expects( $this->never() )->method( "to" );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertNull( $sess->pop("key") );
    }

    public function testPop_NonArray ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("From Wrapped") );
        $wrapped->expects( $this->once() )
            ->method( "clear" )
            ->with( $this->equalTo("key") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("From Wrapped") )
            ->will( $this->returnValue( serialize("Blah") ) );
        $trans->expects( $this->never() )->method( "to" );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( "Blah", $sess->pop("key") );
    }

    public function testPop_ToEmpty ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("From Wrapped") );
        $wrapped->expects( $this->once() )
            ->method( "clear" )
            ->with( $this->equalTo("key") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("From Wrapped") )
            ->will( $this->returnValue( serialize( array("Blah") ) ) );
        $trans->expects( $this->never() )->method( "to" );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( "Blah", $sess->pop("key") );
    }

    public function testPop_Array ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "exists" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue(TRUE) );
        $wrapped->expects( $this->once() )
            ->method( "get" )
            ->with( $this->equalTo("key") )
            ->will( $this->returnValue("From Wrapped") );
        $wrapped->expects( $this->once() )
            ->method( "set" )
            ->with( $this->equalTo("key"), $this->equalTo("transformed") );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->once() )
            ->method( "from" )
            ->with( $this->equalTo("From Wrapped") )
            ->will( $this->returnValue( serialize( array("1st", "2nd") ) ) );
        $trans->expects( $this->once() )
            ->method( "to" )
            ->with( $this->equalTo('a:1:{i:0;s:3:"1st";}') )
            ->will( $this->returnValue("transformed") );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame( "2nd", $sess->pop("key") );
    }

    public function testGetAll ()
    {
        $wrapped = $this->getMock('r8\iface\Session');
        $wrapped->expects( $this->once() )
            ->method( "getAll" )
            ->will( $this->returnValue( array(
                "key1" => "Wrapped1",
            	"key2" => "Wrapped2",
            ) ) );

        $trans = $this->getMock('r8\iface\Transform');
        $trans->expects( $this->at(0) )
            ->method( "from" )
            ->with( $this->equalTo( "Wrapped1" ) )
            ->will( $this->returnValue( serialize("Result1") ) );
        $trans->expects( $this->at(1) )
            ->method( "from" )
            ->with( $this->equalTo( "Wrapped2" ) )
            ->will( $this->returnValue( "unserializable" ) );

        $sess = new \r8\Session\Transform($trans, $wrapped);

        $this->assertSame(
            array( "key1" => "Result1", "key2" => NULL),
            $sess->getAll()
        );
    }

}

?>