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
class classes_Session_Cookie extends PHPUnit_Framework_TestCase
{

    /**
     * A copy of the cookie data
     *
     * @var array
     */
    private $cookie;

    /**
     * Sets up the test scenario
     *
     * @return Null
     */
    public function setUp ()
    {
        $this->cookie = $_COOKIE;
        $_COOKIE = array();
    }

    /**
     * Resets the global state after a test is done
     *
     * @return Null
     */
    public function tearDown ()
    {
        $_COOKIE = $this->cookie;
    }

    public function testGet_serialized ()
    {
        $_COOKIE['str'] = serialize( "Data" );
        $_COOKIE['int'] = serialize( 50 );
        $_COOKIE['flt'] = serialize( 5.04 );
        $_COOKIE['t'] = serialize( TRUE );
        $_COOKIE['f'] = serialize( FALSE );
        $_COOKIE['null'] = serialize( NULL );
        $_COOKIE['ary'] = serialize( array(1,2,3) );
        $_COOKIE['obj'] = serialize( new stdClass );

        $sess = new \r8\Session\Cookie;

        $this->assertSame( "Data", $sess->get("str") );
        $this->assertSame( 50, $sess->get("int") );
        $this->assertSame( 5.04, $sess->get("flt") );
        $this->assertTrue( $sess->get("t") );
        $this->assertFalse( $sess->get("f") );
        $this->assertNull( $sess->get("null") );
        $this->assertSame( array(1,2,3), $sess->get("ary") );
        $this->assertEquals( new stdClass, $sess->get("obj") );
        $this->assertNull( $sess->get("Not A Key") );
    }

    public function testGet_NotSerialized ()
    {
        $_COOKIE['str'] = "Data";
        $_COOKIE['int'] = 50;
        $_COOKIE['flt'] = 5.04;
        $_COOKIE['t'] = TRUE;
        $_COOKIE['f'] = FALSE;
        $_COOKIE['null'] = NULL;
        $_COOKIE['ary'] = array(1,2,3);
        $_COOKIE['obj'] = new stdClass;

        $sess = new \r8\Session\Cookie;

        $this->assertSame( "Data", $sess->get("str") );
        $this->assertSame( 50, $sess->get("int") );
        $this->assertSame( 5.04, $sess->get("flt") );
        $this->assertTrue( $sess->get("t") );
        $this->assertFalse( $sess->get("f") );
        $this->assertNull( $sess->get("null") );
        $this->assertSame( array(1,2,3), $sess->get("ary") );
        $this->assertSame( $_COOKIE['obj'], $sess->get("obj") );
        $this->assertNull( $sess->get("Not A Key") );
    }

    public function testSet_Expiration ()
    {
        $sess = $this->getMock(
            'r8\Session\Cookie',
            array('setCookie'),
            array( 50 )
        );

        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("index"),
                $this->equalTo(5050),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() + 50 - 5 ),
                    $this->lessThan( time() + 50 + 5 )
                )
            );

        $this->assertSame( $sess, $sess->set("index", 5050) );
        $this->assertSame( array("index" => 5050), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testSet_NULL ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->isNull(),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() - (3600 * 24) - 5 ),
                    $this->lessThan( time() - (3600 * 24) + 5 )
                )
            );

        $this->assertSame( $sess, $sess->set("key", NULL) );
        $this->assertSame( array(), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testSet_String ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("index"),
                $this->equalTo("Some Value"),
                $this->equalTo( 0 )
            );

        $this->assertSame( $sess, $sess->set("index", "Some Value") );
        $this->assertSame( array("index" => "Some Value"), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testSet_Object ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("index"),
                $this->equalTo('O:8:"stdClass":0:{}'),
                $this->equalTo( 0 )
            );

        $obj = new stdClass;
        $this->assertSame( $sess, $sess->set("index", $obj) );
        $this->assertSame( array("index" => $obj), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testSet_Array ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("index"),
                $this->equalTo('a:3:{i:0;i:1;i:1;i:2;i:2;i:3;}'),
                $this->equalTo( 0 )
            );

        $this->assertSame( $sess, $sess->set("index", array(1,2,3)) );
        $this->assertSame( array("index" => array(1,2,3)), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testPush_New ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->equalTo('a:1:{i:0;s:3:"new";}'),
                $this->equalTo( 0 )
            );

        $this->assertSame( $sess, $sess->push("key", "new") );
        $this->assertSame( array( "key" => array( "new" ) ), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testPush_NonArray ()
    {
        $_COOKIE['key'] = "Data";

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->equalTo('a:2:{i:0;s:4:"Data";i:1;s:3:"new";}'),
                $this->equalTo( 0 )
            );

        $this->assertSame( $sess, $sess->push("key", "new") );
        $this->assertSame( array( "key" => array( "Data", "new" ) ), $sess->getAll() );
        $this->assertSame( array( "key" => "Data" ), $_COOKIE );
    }

    public function testPush_Array ()
    {
        $_COOKIE['key'] = array( "Data" );

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->equalTo('a:2:{i:0;s:4:"Data";i:1;s:3:"new";}'),
                $this->equalTo( 0 )
            );

        $this->assertSame( $sess, $sess->push("key", "new") );
        $this->assertSame( array( "key" => array( "Data", "new" ) ), $sess->getAll() );
        $this->assertSame( array( "key" => array("Data") ), $_COOKIE );
    }

    public function testPop_NotSet ()
    {
        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->never() )->method( "setCookie" );

        $this->assertNull( $sess->pop("key") );
        $this->assertSame( array(), $sess->getAll() );
        $this->assertSame( array(), $_COOKIE );
    }

    public function testPop_NonArray ()
    {
        $_COOKIE['key'] = "Data";

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->isNull(),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() - (3600 * 24) - 5 ),
                    $this->lessThan( time() - (3600 * 24) + 5 )
                )
            );

        $this->assertSame( "Data", $sess->pop("key") );
        $this->assertSame( array(), $sess->getAll() );
        $this->assertSame( array( "key" => "Data" ), $_COOKIE );
    }

    public function testPop_ToEmpty ()
    {
        $_COOKIE['key'] = array( "Data" );

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->isNull(),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() - (3600 * 24) - 5 ),
                    $this->lessThan( time() - (3600 * 24) + 5 )
                )
            );

        $this->assertSame( "Data", $sess->pop("key") );
        $this->assertSame( array(), $sess->getAll() );
        $this->assertSame( array( "key" => array( "Data" ) ), $_COOKIE );
    }

    public function testPop_Array ()
    {
        $_COOKIE['key'] = array( "1st", "2nd" );

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->once() )
            ->method( "setCookie" )
            ->with(
                $this->equalTo("key"),
                $this->equalTo('a:1:{i:0;s:3:"1st";}'),
                $this->equalTo(0)
            );

        $this->assertSame( "2nd", $sess->pop("key") );
        $this->assertSame( array( "key" => array( "1st" ) ), $sess->getAll() );
        $this->assertSame( array( "key" => array( "1st", "2nd" ) ), $_COOKIE );
    }

    public function testExists ()
    {
        $_COOKIE['key'] = "Data";
        $_COOKIE['key2'] = new stdClass;
        $_COOKIE['key3'] = NULL;

        $sess = new \r8\Session\Cookie;

        $this->assertTrue( $sess->exists("key") );
        $this->assertTrue( $sess->exists("key2") );
        $this->assertFalse( $sess->exists("key3") );
        $this->assertFalse( $sess->exists("Not A Key") );
    }

    public function testClear ()
    {
        $_COOKIE['key'] = "Data";
        $_COOKIE['key3'] = NULL;

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->exactly(3) )
            ->method( "setCookie" )
            ->with(
                $this->matchesRegularExpression('/^(key|key3)$/'),
                $this->isNull(),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() - (3600 * 24) - 5 ),
                    $this->lessThan( time() - (3600 * 24) + 5 )
                )
            );

        $this->assertSame( $sess, $sess->clear('key') );
        $this->assertSame( array('key3' => NULL), $sess->getAll() );
        $this->assertSame( array('key' => 'Data', 'key3' => NULL), $_COOKIE );

        $this->assertSame( $sess, $sess->clear('key') );
        $this->assertSame( array('key3' => NULL), $sess->getAll() );
        $this->assertSame( array('key' => 'Data', 'key3' => NULL), $_COOKIE );

        $this->assertSame( $sess, $sess->clear('key3') );
        $this->assertSame( array(), $sess->getAll() );
        $this->assertSame( array('key' => 'Data', 'key3' => NULL), $_COOKIE );
    }

    public function testClearAll ()
    {
        $_COOKIE['key'] = "Data";
        $_COOKIE['key3'] = NULL;

        $sess = $this->getMock('r8\Session\Cookie', array('setCookie'));
        $sess->expects( $this->exactly(2) )
            ->method( "setCookie" )
            ->with(
                $this->matchesRegularExpression('/^(key|key3)$/'),
                $this->isNull(),
                $this->logicalAnd(
                    $this->isType("integer"),
                    $this->greaterThan( time() - (3600 * 24) - 5 ),
                    $this->lessThan( time() - (3600 * 24) + 5 )
                )
            );

        $this->assertSame( $sess, $sess->clearAll() );
        $this->assertSame( array(), $sess->getAll() );
    }

    public function testGetAll ()
    {
        $_COOKIE['key'] = "Data";
        $_COOKIE['key3'] = NULL;

        $sess = new \r8\Session\Cookie;

        $this->assertSame( array('key' => 'Data', 'key3' => NULL), $sess->getAll() );

        $_COOKIE = array();

        $this->assertSame( array('key' => 'Data', 'key3' => NULL), $sess->getAll() );
    }

}

