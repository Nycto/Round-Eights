<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * numeric function unit tests
 */
class functions_array extends PHPUnit_Framework_TestCase
{

    public function testFlatten ()
    {
        $this->assertSame(
                array(1,2,3),
                \h2o\ary\flatten( array(array(1,2,3)) )
            );

        $this->assertSame(
                array(1,2,3,4,5,6),
                \h2o\ary\flatten( array(array(1,2,3),array(4,5,6)) )
            );

        $this->assertSame(
                array(1,2,3,4,5,6,7,8),
                \h2o\ary\flatten( array(array(1,2,3),array(4,5,array(6,7,8))) )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,6,7,8)),
                \h2o\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        2
                    )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                \h2o\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        3
                    )
            );

        $this->assertSame(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                \h2o\ary\flatten(
                        array(array(1,2,3),array(4,5,array(6,7,8))),
                        4
                    )
            );

        $this->assertSame(
                array(1 => 'one', 2 => 'two', 3 => 'three'),
                \h2o\ary\flatten( array(1 => 'one', 2 => 'two', 3 => 'three') )
            );
    }

    public function testBranch_basic ()
    {
        $ary = array();

        $this->assertNull(
                \h2o\ary\branch($ary, "new", array("one", "two", "three"))
            );
        $this->assertSame(
                array('one' => array('two' => array('three' => 'new'))),
                $ary
            );

        $this->assertNull(
                \h2o\ary\branch($ary, "other", array( array("one"), array(array("two"), "five")))
            );
        $this->assertSame(
                array('one' => array(
                        'two' => array('three' => 'new', 'five' => 'other')
                    )),
                $ary
            );

        $this->assertNull(
                \h2o\ary\branch($ary, "val", array('one', 'two'))
            );
        $this->assertSame(
                array( 'one' => array('two' => 'val') ),
                $ary
            );

        $this->assertNull(
                \h2o\ary\branch($ary, "value", array('first'))
            );
        $this->assertSame(
                array( 'one' => array('two' => 'val'), 'first' => 'value' ),
                $ary
            );

        $this->assertNull(
                \h2o\ary\branch($ary, "value", array(array('first', '2nd')))
            );
        $this->assertSame(
                array(
                        'one' => array('two' => 'val'),
                        'first' => array( '2nd' => 'value' )
                    ),
                $ary
            );

        $this->assertNull(
                \h2o\ary\branch($ary, "over", array(array('first', '2nd', '3rd')))
            );
        $this->assertSame(
                array(
                        'one' => array('two' => 'val'),
                        'first' => array( '2nd' => array( '3rd' => 'over' ) )
                    ),
                $ary
            );
    }

    public function testBranch_pushLastKey ()
    {
        $ary = array();

        $this->assertNull( \h2o\ary\branch($ary, "new", array(null)) );
        $this->assertSame(
                array('new'),
                $ary
            );

        $this->assertNull( \h2o\ary\branch($ary, "another", array(null)) );
        $this->assertSame(
                array('new', 'another'),
                $ary
            );


        $this->assertNull( \h2o\ary\branch($ary, "leaf", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf')),
                $ary
            );


        $this->assertNull( \h2o\ary\branch($ary, "leaf2", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf', 'leaf2')),
                $ary
            );


        $this->assertNull( \h2o\ary\branch($ary, "leaf3", array('push', null)) );
        $this->assertSame(
                array('new', 'another', 'push' => array('leaf', 'leaf2', 'leaf3')),
                $ary
            );
    }

    public function testBranch_pushMidKey ()
    {
        $ary = array();

        $this->assertNull( \h2o\ary\branch($ary, "new", array("one", null, "two")) );
        $this->assertSame(
                array('one' => array(array('two' => 'new'))),
                $ary
            );

        $this->assertNull( \h2o\ary\branch($ary, "val", array("one", null, "two")) );
        $this->assertSame(
                array('one' => array(
                        array('two' => 'new'),
                        array('two' => 'val')
                    )),
                $ary
            );

        $this->assertNull( \h2o\ary\branch($ary, 3, array("one", null, "three")) );
        $this->assertSame(
                array('one' => array(
                        array('two' => 'new'),
                        array('two' => 'val'),
                        array('three' => 3)
                    )),
                $ary
            );
    }

    public function testTranslateKeys ()
    {
        $ary = array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 );

        $this->assertEquals(
                array( 'eno' => 1, 'two' => 2, 'eerht' => 3, 'ruof' => 4, 'five' => 5 ),
                \h2o\ary\translateKeys(
                        $ary,
                        array('one' => 'eno', 'three' => 'eerht', 'four' => 'ruof')
                    )
            );

        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'six' => 3, 'four' => 4, 'five' => 5 ),
                \h2o\ary\translateKeys(
                        $ary,
                        array('one' => 'five', 'three' => 'six')
                    )
            );
    }

    public function testCalcOffset ()
    {
        try {
            \h2o\ary\calcOffset(array(), 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("List is empty", $err->getMessage() );
        }


        $this->assertEquals(0, \h2o\ary\calcOffset( range(1, 5), -5, \h2o\ary\OFFSET_NONE) );
        $this->assertEquals(3, \h2o\ary\calcOffset( range(1, 5), -2, \h2o\ary\OFFSET_NONE) );
        $this->assertEquals(4, \h2o\ary\calcOffset( range(1, 5), -1, \h2o\ary\OFFSET_NONE) );
        $this->assertEquals(0, \h2o\ary\calcOffset( range(1, 5),  0, \h2o\ary\OFFSET_NONE) );
        $this->assertEquals(3, \h2o\ary\calcOffset( range(1, 5),  3, \h2o\ary\OFFSET_NONE) );
        $this->assertEquals(4, \h2o\ary\calcOffset( range(1, 5),  4, \h2o\ary\OFFSET_NONE) );

        try {
            \h2o\ary\calcOffset(array(), 2, \h2o\ary\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("List is empty", $err->getMessage() );
        }

        try {
            \h2o\ary\calcOffset(range(1, 5), 5, \h2o\ary\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("Offset is out of bounds", $err->getMessage() );
        }

        try {
            \h2o\ary\calcOffset(range(1, 5), -6, \h2o\ary\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame("Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, \h2o\ary\calcOffset(range(1, 5), -14, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(2, \h2o\ary\calcOffset(range(1, 5), -8, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -5, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), -2, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), -1, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), 0, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), 3, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 4, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), 8, \h2o\ary\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), 15, \h2o\ary\OFFSET_WRAP) );

        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -14, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -8, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -5, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), -2, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), -1, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), 0, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), 3, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 4, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 8, \h2o\ary\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 15, \h2o\ary\OFFSET_RESTRICT) );

        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -2, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), -1, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(0, \h2o\ary\calcOffset(range(1, 5), 0, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(3, \h2o\ary\calcOffset(range(1, 5), 3, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 4, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 8, \h2o\ary\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\ary\calcOffset(range(1, 5), 15, \h2o\ary\OFFSET_LIMIT) );
    }

    public function testOffset ()
    {
        $ary = range(1, 15);

        $this->assertEquals(1, \h2o\ary\offset($ary, 0) );
        $this->assertEquals(15, \h2o\ary\offset($ary, -1) );
        $this->assertEquals(8, \h2o\ary\offset($ary, 7) );
    }

    public function testCompact ()
    {
        $ary = array( 0, TRUE, NULL, "string", FALSE, 1, array(), array(1.5, ""), "  ", "0" );

        $this->assertEquals(
                array( 1 => TRUE, 3 => "string", 5 => 1, 7 => array(1.5), 9 => "0"),
                \h2o\ary\compact($ary)
            );

        $this->assertEquals(
                array( 1 => TRUE, 2 => NULL, 3 => "string", 4 => FALSE, 5 => 1, 7 => array(1.5), 9 => "0"),
                \h2o\ary\compact($ary, \h2o\ALLOW_FALSE | \h2o\ALLOW_NULL )
            );


        $ary = array(
                array("full", "of", "stuff", FALSE),
                array(),
                array(1.5, ""),
            );

        $this->assertEquals(
                array( 0 => array("full", "of", "stuff"), 2 => array(1.5) ),
                \h2o\ary\compact($ary)
            );
    }

    public function testHone ()
    {
        $ary = array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 );

        $this->assertEquals(
                array ( 'five' => 5, 'three' => 3, 'six' => 1 ),
                \h2o\ary\hone($ary, 'five', 'three', 'six')
            );

        $this->assertEquals(
                array ( 'ten' => 1, 'eleven' => 2, 'twelve' => 3 ),
                \h2o\ary\hone($ary,  array('ten', 'eleven'), 'twelve')
            );

        $this->assertEquals(
                array ( 'seven' => 1, 'six' => 2, 'five' => 5, 'four' => 4 ),
                \h2o\ary\hone($ary,  array('seven', 'six'), 'five', array(array('four')) )
            );
    }

    public function testContains ()
    {
        $ary = array(
                3 => "d", 5 => "b", 6 => "a",
                1 => "f", 4 => "c", 2 => "e",
                5, 6, 7, 8
            );

        $this->assertTrue( \h2o\ary\contains($ary, 'd') );
        $this->assertTrue( \h2o\ary\contains($ary, 'a') );
        $this->assertTrue( \h2o\ary\contains($ary, 'e') );

        $this->assertTrue( \h2o\ary\contains($ary, 5) );
        $this->assertTrue( \h2o\ary\contains($ary, 6) );

        $this->assertTrue( \h2o\ary\contains($ary, "5") );
        $this->assertTrue( \h2o\ary\contains($ary, "6") );

        $this->assertFalse( \h2o\ary\contains($ary, 'not') );
        $this->assertFalse( \h2o\ary\contains($ary, 'D') );
        $this->assertFalse( \h2o\ary\contains($ary, 'A') );
        $this->assertFalse( \h2o\ary\contains($ary, 'E') );

        $this->assertFalse( \h2o\ary\contains($ary, "5", TRUE) );
        $this->assertFalse( \h2o\ary\contains($ary, "6", TRUE) );


        $obj = new stdClass;
        $ary = array( new stdClass );

        $this->assertTrue( \h2o\ary\contains($ary, $obj) );
        $this->assertFalse( \h2o\ary\contains($ary, $obj, TRUE) );
    }

    public function testInvoke ()
    {
        $obj1 = $this->getMock('stdClass', array('get'));
        $obj1->expects( $this->once() )
            ->method('get')
            ->with()
            ->will( $this->returnValue('one') );

        $obj2 = $this->getMock('stdClass', array('get'));
        $obj2->expects( $this->once() )
            ->method('get')
            ->with()
            ->will( $this->returnValue('two') );

        $ary = array(
                'meh' => $obj1,
                0 => 5,
                1 => $obj2,
                5 => 'string',
                8 => new stdClass,
            );

        $this->assertSame(
                array( 'meh' => 'one', 1 => 'two' ),
                \h2o\ary\invoke($ary, 'get' )
            );


        $obj1 = $this->getMock('stdClass', array('get'));
        $obj1->expects( $this->once() )
            ->method('get')
            ->with( $this->equalTo('arg1'), $this->equalTo('arg2') )
            ->will( $this->returnValue('one') );

        $obj2 = $this->getMock('stdClass', array('get'));
        $obj2->expects( $this->once() )
            ->method('get')
            ->with( $this->equalTo('arg1'), $this->equalTo('arg2') )
            ->will( $this->returnValue('two') );

        $ary = array(
                'meh' => $obj1,
                0 => 5,
                1 => $obj2,
                5 => 'string',
                8 => new stdClass,
            );

        $this->assertSame(
                array( 'meh' => 'one', 1 => 'two' ),
                \h2o\ary\invoke($ary, 'get', 'arg1', 'arg2' )
            );

    }

    public function testWithout ()
    {
        $ary = array( 1, 2, 3, "four", "five", "six" );

        $this->assertSame(
                array( 0 => 1, 2 => 3, 3 => "four", 4 => "five", 5 => "six"),
                \h2o\ary\without($ary, 2)
            );

        $this->assertSame(
                array( 0 => 1, 3 => "four", 4 => "five", 5 => "six"),
                \h2o\ary\without($ary, 2, "3")
            );

        $this->assertSame(
                array( 0 => 1, 3 => "four", 5 => "six"),
                \h2o\ary\without($ary, 2, "3", "five", array("six"))
            );
    }

    public function testFirst ()
    {
        $this->assertNull( \h2o\ary\first(array()) );
        $this->assertSame( "value", \h2o\ary\first(array("value")) );
        $this->assertSame( 1, \h2o\ary\first(range(1, 20)) );
    }

    public function testLast ()
    {
        $this->assertNull( \h2o\ary\last(array()) );
        $this->assertSame( "value", \h2o\ary\last(array("value")) );
        $this->assertSame( 20, \h2o\ary\last(range(1, 20)) );
    }

}

?>