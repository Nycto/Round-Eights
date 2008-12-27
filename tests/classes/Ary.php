<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * A stub used for callbacks
 */
class stub_classes_ary_boolCallbacks
{

    static public function callbackStatic ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new \cPHP\Exception\Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

    public function callbackObject ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new \cPHP\Exception\Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

    public function __invoke ( $value, $key )
    {
        if ( func_num_args() != 2 ) {
            $err = new \cPHP\Exception\Interaction("Number of arguments was not '2'");
            $err->setData("Number of Args", func_num_args());
            throw $err;
        }

        return stripos($value, "yes") !== FALSE ? TRUE : FALSE;
    }

}

/**
 * A stub class for testing the invoke method
 */
class stub_classes_ary_invokeTester
{

    /**
     * The value that will be returned when 'get' is invoked
     */
    public $return;

    /**
     * Sets the value to be returned
     */
    public function __construct ( $return )
    {
        $this->return = $return;
    }

    public function get ()
    {
        $args = func_get_args();
        return $this->return .":". implode(":", $args);
    }

}

/**
 * unit tests
 */
class classes_ary extends PHPUnit_Framework_TestCase
{

    public function callbackObject ()
    {
        $args = func_get_args();
        return "o". implode(":", $args);
    }

    static public function callbackStatic ()
    {
        $args = func_get_args();
        return "s". implode(":", $args);
    }

    public function __invoke ()
    {
        $args = func_get_args();
        return "i". implode(":", $args);
    }

    public function testConstruct ()
    {
        $ary = new \cPHP\Ary( array( 4, 3, "other") );
        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );


        $newAry = new \cPHP\Ary( $ary );
        $this->assertNotSame( $ary, $newAry );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $newAry->get()
            );


        $iterAggr = $this->getMock("IteratorAggregate", array("getIterator"));
        $iterAggr->expects( $this->once() )
            ->method("getIterator")
            ->with()
            ->will( $this->returnValue( new ArrayObject( array( 4, 3, "other" ) ) ) );

        $ary = new \cPHP\Ary( $iterAggr );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $ary->get()
            );


        $iter = new ArrayIterator( array( 4, 3, "other" ) );
        $ary = new \cPHP\Ary( $iter );
        $this->assertEquals(
                array( 4, 3, "other" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary( "string" );
        $this->assertEquals(
                array( "string" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary( 1 );
        $this->assertEquals(
                array( 1 ),
                $ary->get()
            );

    }

    public function testCreate ()
    {
        $ary = \cPHP\Ary::create(array( 4, 3, "other"));

        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );
    }

    public function testRange ()
    {
        $ary = \cPHP\Ary::range(5, 8);

        $this->assertEquals(
                array(5, 6, 7, 8),
                $ary->get()
            );
    }

    public function testExplode ()
    {
        $parts = \cPHP\Ary::explode(":", "parts:of:a:string");
        $this->assertThat( $parts, $this->isInstanceOf("cPHP\\Ary") );
        $this->assertSame(
                array("parts", "of", "a", "string"),
                $parts->get()
            );


        $parts = \cPHP\Ary::explode(":-:", "parts:-:of:-:a:-:string", 3);
        $this->assertThat( $parts, $this->isInstanceOf("cPHP\\Ary") );
        $this->assertSame(
                array("parts", "of", "a:-:string"),
                $parts->get()
            );
    }

    public function testIs ()
    {
        $this->assertTrue( \cPHP\Ary::is(array()) );
        $this->assertTrue( \cPHP\Ary::is( new \cPHP\Ary ) );
        $this->assertTrue( \cPHP\Ary::is( new ArrayObject ) );

        $this->assertFalse( \cPHP\Ary::is(5) );
        $this->assertFalse( \cPHP\Ary::is(5.0) );
        $this->assertFalse( \cPHP\Ary::is("string") );
        $this->assertFalse( \cPHP\Ary::is(FALSE) );
        $this->assertFalse( \cPHP\Ary::is(TRUE) );
        $this->assertFalse( \cPHP\Ary::is(NULL) );
    }

    public function testGet_noRef ()
    {
        $ary = new \cPHP\Ary;

        $ref = $ary->get();

        $this->assertType("array", $ref);

        $ref["new"] = "value";

        $this->assertSame( array(), $ary->get() );
    }

    public function testGet_byRef ()
    {
        $ary = new \cPHP\Ary;

        $ref =& $ary->get();

        $this->assertType("array", $ref);

        $ref["new"] = "value";

        $this->assertSame(
                array( "new" => "value" ),
                $ary->get()
            );
    }

    public function testIteration ()
    {

        $ary = new \cPHP\Ary(array(
                1 => 1,
                0 => 0,
                NULL => NULL,
                FALSE => FALSE,
                TRUE => TRUE,
                "string" => "string",
                "0" => "0"
            ));

        $iter = array();
        foreach ( $ary AS $key => $val ) {
            $iter[ $key ] = $val;
        }

        $this->assertEquals(
                array(
                        1 => 1,
                        0 => 0,
                        NULL => NULL,
                        FALSE => FALSE,
                        TRUE => TRUE,
                        "string" => "string",
                        "0" => "0"
                    ),
                $iter
            );

    }

    public function testCount ()
    {
        $ary = new \cPHP\Ary( array(3 => 1, 2 => 2, 1 => 3) );
        $this->assertEquals( 3, count($ary) );
    }

    public function testAccess ()
    {
        $ary = new \cPHP\Ary( array(3 => 1, 2 => 2, 1 => 3) );

        $this->assertEquals( 2, $ary[2] );


        $ary[0] = 4;
        $this->assertEquals( 4, $ary[0] );


        $ary[] = 5;
        $this->assertEquals( 5, $ary[4] );


        $this->assertTrue( isset($ary[1]) );
        $this->assertFalse( isset($ary[10]) );


        unset ( $ary[1] );
        $this->assertFalse( isset($ary[1]) );


        $this->assertEquals( 4, count($ary) );

    }

    public function testCalcOffset ()
    {
        try {
            \cPHP\Ary::create(array())->calcOffset(2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("List is empty", $err->getMessage() );
        }


        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-5, \cPHP\Ary::OFFSET_NONE) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(-2, \cPHP\Ary::OFFSET_NONE) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(-1, \cPHP\Ary::OFFSET_NONE) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(0, \cPHP\Ary::OFFSET_NONE) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(3, \cPHP\Ary::OFFSET_NONE) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(4, \cPHP\Ary::OFFSET_NONE) );

        try {
            \cPHP\Ary::create(array())->calcOffset(2, \cPHP\Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("List is empty", $err->getMessage() );
        }

        try {
            \cPHP\Ary::range(1, 5)->calcOffset(5, \cPHP\Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("Offset is out of bounds", $err->getMessage() );
        }

        try {
            \cPHP\Ary::range(1, 5)->calcOffset(-6, \cPHP\Ary::OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame("Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, \cPHP\Ary::range(1, 5)->calcOffset(-14, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(2, \cPHP\Ary::range(1, 5)->calcOffset(-8, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-5, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(-2, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(-1, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(0, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(3, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(4, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(8, \cPHP\Ary::OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(15, \cPHP\Ary::OFFSET_WRAP) );

        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-14, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-8, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-5, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(-2, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(-1, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(0, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(3, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(4, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(8, \cPHP\Ary::OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(15, \cPHP\Ary::OFFSET_RESTRICT) );

        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-2, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(-1, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(0, \cPHP\Ary::range(1, 5)->calcOffset(0, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(3, \cPHP\Ary::range(1, 5)->calcOffset(3, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(4, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(8, \cPHP\Ary::OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\Ary::range(1, 5)->calcOffset(15, \cPHP\Ary::OFFSET_LIMIT) );

    }

    public function testPushPop ()
    {
        $ary = new \cPHP\Ary( array(3 => 1, 1 => 3) );

        $this->assertSame(
                $ary,
                $ary->push( 4 )
            );

        $this->assertEquals(
                array(3 => 1, 1 => 3, 4 => 4),
                $ary->get()
            );


        $this->assertSame(
                $ary,
                $ary->pop()
            );

        $this->assertEquals(
                array(3 => 1, 1 => 3),
                $ary->get()
            );


        $this->assertEquals(
                3,
                $ary->pop( TRUE )
            );


        $this->assertSame(
                $ary,
                $ary->pop()->pop()->pop()
            );

        $this->assertEquals(
                array(),
                $ary->get()
            );

    }

    public function testShiftUnshift ()
    {
        $ary = new \cPHP\Ary( array(3 => 1, 1 => 3) );

        $this->assertSame(
                $ary,
                $ary->unshift( 4 )
            );

        $this->assertEquals(
                array(0 => 4, 1 => 1, 2 => 3 ),
                $ary->get()
            );


        $this->assertSame(
                $ary,
                $ary->shift()
            );

        $this->assertEquals(
                array(0 => 1, 1 => 3),
                $ary->get()
            );



        $this->assertEquals(
                1,
                $ary->shift( TRUE )
            );


        $this->assertSame(
                $ary,
                $ary->shift()->shift()->shift()
            );

        $this->assertEquals(
                array(),
                $ary->get()
            );

    }

    public function testEnd ()
    {
        $ary = \cPHP\Ary::range(0, 19);

        $this->assertSame(
                $ary,
                $ary->end()
            );

        $this->assertEquals(
                19,
                $ary->current()
            );
    }

    public function testPrev ()
    {
        $ary = \cPHP\Ary::range(0, 19);

        $this->assertSame(
                $ary,
                $ary->prev()
            );

        $this->assertFalse(
                $ary->valid()
            );

        $ary->end();


        $this->assertSame(
                $ary,
                $ary->prev()
            );

        $this->assertEquals(
                18,
                $ary->current()
            );
    }

    public function testKeyOffset ()
    {
        $ary = new \cPHP\Ary(
                array( 4 => "zero", 2 => "one", 8 => "two", 32 => "three", 16 => "four", 64 => "five"  )
            );

        $this->assertEquals( 0, $ary->keyOffset( 4 ) );
        $this->assertEquals( 5, $ary->keyOffset( 64 ) );
        $this->assertEquals( 3, $ary->keyOffset( 32 ) );

        try {
            \cPHP\Ary::range(1, 5)->keyOffset(60);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}
    }

    public function testPointer ()
    {
        $ary = \cPHP\Ary::range(0, 19);

        $this->assertEquals( 0, $ary->pointer() );

        $ary->end();

        $this->assertEquals( 19, $ary->pointer() );

        $ary->prev()->prev()->prev();

        $this->assertEquals( 16, $ary->pointer() );
    }

    public function testOffset ()
    {
        $ary = \cPHP\Ary::range(1, 15);

        $this->assertEquals(1, $ary->offset(0) );
        $this->assertEquals(15, $ary->offset(-1) );
        $this->assertEquals(8, $ary->offset(7) );
    }

    public function testFirst ()
    {
        $ary = \cPHP\Ary::range(1, 5);

        $first = $ary->first();
        $this->assertSame( 1, $first );
        $first = 'new';
        $this->assertSame( array(1, 2, 3, 4, 5), $ary->get() );


        $first =& $ary->first();
        $this->assertSame( 1, $first );
        $first = 'new';
        $this->assertSame( array('new', 2, 3, 4, 5), $ary->get() );


        $ary = new \cPHP\Ary(array( 2 => 'two', 1 => 'one', 0 => 'zero' ));
        $this->assertSame( 'two', $ary->first() );


        $ary = new \cPHP\Ary;

        try {
            $ary->first();
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "Offset does not exist", $err->getMessage() );
        }

    }

    public function testLast ()
    {
        $ary = \cPHP\Ary::range(1, 5);

        $last = $ary->last();
        $this->assertSame( 5, $last );
        $last = 'new';
        $this->assertSame( array(1, 2, 3, 4, 5), $ary->get() );


        $last =& $ary->last();
        $this->assertSame( 5, $last );
        $last = 'new';
        $this->assertSame( array(1, 2, 3, 4, 'new'), $ary->get() );


        $ary = new \cPHP\Ary(array( 2 => 'two', 1 => 'one', 0 => 'zero' ));
        $this->assertSame( 'zero', $ary->last() );


        $ary = new \cPHP\Ary;

        try {
            $ary->last();
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "Offset does not exist", $err->getMessage() );
        }

    }

    public function testKeyExists ()
    {
        $this->assertTrue( \cPHP\Ary::range(1, 15)->keyExists(0) );
        $this->assertFalse( \cPHP\Ary::range(1, 15)->keyExists(15) );

        $this->assertFalse( \cPHP\Ary::create(array("key" => "value"))->keyExists(14) );
        $this->assertTrue( \cPHP\Ary::create(array("key" => "value"))->keyExists("key") );
    }

    public function testSeek ()
    {
        $ary = \cPHP\Ary::range(0, 19);

        // Test setting the pointer to the end of the array
        $this->assertSame(
                $ary,
                $ary->seek(-1)
            );
        $this->assertEquals( 19, $ary->current() );


        // Test setting it to the beginning
        $this->assertSame(
                $ary,
                $ary->seek(0)
            );
        $this->assertEquals( 0, $ary->current() );


        // Test seeking to the current location of the pointer
        $ary->next()->next()->next();
        $this->assertSame(
                $ary,
                $ary->seek(3)
            );
        $this->assertEquals( 3, $ary->current() );


        // Test seeking to a point closest to the end of the array
        $this->assertSame(
                $ary,
                $ary->seek(17)
            );
        $this->assertEquals( 17, $ary->current() );


        // Test seeking to a point closest to the beginning of the array
        $this->assertSame(
                $ary,
                $ary->seek(5)
            );
        $this->assertEquals( 5, $ary->current() );


        // Test seeking forward when the closest point is the current pointer
        $this->assertSame(
                $ary,
                $ary->seek(7)
            );
        $this->assertEquals( 7, $ary->current() );


        // Test seeking backward when the closest point is the current pointer
        $this->assertSame(
                $ary,
                $ary->seek(6)
            );
        $this->assertEquals( 6, $ary->current() );

    }

    public function testKeys ()
    {
        $ary = new \cPHP\Ary( array(3 => 10, 1 => 9, 5 => 8, 10 => 7 ) );

        $keys = $ary->keys();

        $this->assertThat( $keys, $this->isInstanceOf("cPHP\Ary") );

        $this->assertEquals(array(3, 1, 5, 10), $keys->get());
    }

    public function testValues ()
    {
        $ary = new \cPHP\Ary( array(3 => 10, 1 => 9, 5 => 8, 10 => 7 ) );

        $keys = $ary->values();

        $this->assertThat( $keys, $this->isInstanceOf("cPHP\Ary") );

        $this->assertEquals(array(10, 9, 8, 7), $keys->get());
    }

    public function testClear ()
    {
        $ary = new \cPHP\Ary(array( 4, 3, "other"));

        $this->assertEquals(
                array( 4, 3, "other"),
                $ary->get()
            );

        $this->assertSame(
                $ary,
                $ary->clear()
            );


        $this->assertEquals( array(), $ary->get() );
    }

    public function testContains ()
    {
        $ary = new \cPHP\Ary(array(
                3 => "d", 5 => "b", 6 => "a",
                1 => "f", 4 => "c", 2 => "e",
                5, 6, 7, 8
            ));

        $this->assertTrue( $ary->contains('d') );
        $this->assertTrue( $ary->contains('a') );
        $this->assertTrue( $ary->contains('e') );

        $this->assertTrue( $ary->contains(5) );
        $this->assertTrue( $ary->contains(6) );

        $this->assertTrue( $ary->contains("5") );
        $this->assertTrue( $ary->contains("6") );

        $this->assertFalse( $ary->contains('not') );
        $this->assertFalse( $ary->contains('D') );
        $this->assertFalse( $ary->contains('A') );
        $this->assertFalse( $ary->contains('E') );

        $this->assertFalse( $ary->contains("5", TRUE) );
        $this->assertFalse( $ary->contains("6", TRUE) );


        $obj = new stdClass;
        $ary = new \cPHP\Ary(array( new stdClass ));

        $this->assertTrue( $ary->contains($obj) );
        $this->assertFalse( $ary->contains($obj, TRUE) );
    }

    public function testFlatten ()
    {

        $ary = \cPHP\Ary::create( array(array(1,2,3)) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,6)) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array( 1 => 'one', 2 => 'two' ) )->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array( 1 => 'one', 2 => 'two' ),
                $ary->get()
            );

    }

    public function testFlatten_maxDepth ()
    {


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,6,7,8)) )->flatten( 2 );

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,6,7,8)),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten( 3 );

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                $ary->get()
            );


        $ary = \cPHP\Ary::create( array(array(1,2,3),array(4,5,array(6,7,8))) )->flatten( 4 );

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(array(1,2,3),array(4,5,array(6,7,8))),
                $ary->get()
            );




        $ary = \cPHP\Ary::create(array(
                new \cPHP\Ary(array(1,2,3)),
                array(4,5, new \cPHP\Ary(array(6,7,8)) )
            ))->flatten();

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3,4,5,6,7,8),
                $ary->get()
            );


        $ary = \cPHP\Ary::create(array(
                new \cPHP\Ary(array(1,2,3)),
                array(4,5, new \cPHP\Ary(array(6,7,8)) )
            ))->flatten( 3 );

        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(1,2,3),
                $ary->offsetGet(0)->get()
            );

        $subAry = $ary->offsetGet(1);
        $this->assertType("array", $subAry);
        $this->assertEquals(
                array(6, 7, 8),
                $subAry[2]->get()
            );

    }

    public function testSort ()
    {
        $ary = \cPHP\Ary::create(array( 3 => "d", 5 => "b", 6 => "a", 1 => "f", 4 => "c", 2 => "e" ));

        $this->assertSame( $ary, $ary->sort() );
        $this->assertEquals(
                array( 6 => "a", 5 => "b", 4 => "c", 3 => "d", 2 => "e", 1 => "f" ),
                $ary->get()
            );


        $ary = \cPHP\Ary::create(array( 3 => "d", 5 => "b", 6 => "a", 1 => "f", 4 => "c", 2 => "e" ));

        $this->assertSame( $ary, $ary->sort( TRUE ) );
        $this->assertEquals(
                array( 1 => 'f', 2 => 'e', 3 => 'd', 4 => 'c', 5 => 'b', 6 => 'a' ),
                $ary->get()
            );


        try {
            $ary->sort( TRUE, "Invalid sort type" );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}
    }

    public function testSortyByKey ()
    {
        $ary = new \cPHP\Ary(array( 1 => 'x', 'b' => 'y', 'a' => 'z', '2' => 'w' ));
        $this->assertSame( $ary, $ary->sortByKey() );
        $this->assertSame(
                array( 'a' => 'z', 'b' => 'y', 1 => 'x', '2' => 'w' ),
                $ary->get()
            );

        $ary = new \cPHP\Ary(array( 1 => 'x', 'b' => 'y', 'a' => 'z', '2' => 'w' ));
        $this->assertSame( $ary, $ary->sortByKey( TRUE ) );
        $this->assertSame(
                array( '2' => 'w', 1 => 'x', 'b' => 'y', 'a' => 'z' ),
                $ary->get()
            );

        $ary = new \cPHP\Ary(array( 11 => 'x', '05' => 'y', '050' => 'z', '2' => 'w' ));
        $this->assertSame( $ary, $ary->sortByKey( FALSE, SORT_STRING ) );
        $this->assertSame(
                array( '05' => 'y', '050' => 'z', 11 => 'x', '2' => 'w' ),
                $ary->get()
            );

        $ary = new \cPHP\Ary(array( 11 => 'x', '05' => 'y', '050' => 'z', '2' => 'w' ));
        $this->assertSame( $ary, $ary->sortByKey( TRUE, SORT_STRING ) );
        $this->assertSame(
                array( '2' => 'w', 11 => 'x', '050' => 'z', '05' => 'y' ),
                $ary->get()
            );

        try {
            $ary->sortByKey(FALSE, "This is not a valid value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertEquals("Invalid Sort Type", $err->getMessage());
        }
    }

    public function testNaturalSort ()
    {
        $ary = new \cPHP\Ary(array( 'IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png' ));
        $this->assertSame( $ary, $ary->naturalSort() );
        $this->assertSame(
                array(
                        0 => 'IMG0.png',
                        4 => 'img1.png',
                        3 => 'img2.png',
                        5 => 'IMG3.png',
                        2 => 'img10.png',
                        1 => 'img12.png'
                    ),
                $ary->get()
            );

        $ary = new \cPHP\Ary(array( 'IMG0.png', 'img12.png', 'img10.png', 'img2.png', 'img1.png', 'IMG3.png' ));
        $this->assertSame( $ary, $ary->naturalSort( TRUE ) );
        $this->assertSame(
                array(
                        0 => 'IMG0.png',
                        5 => 'IMG3.png',
                        4 => 'img1.png',
                        3 => 'img2.png',
                        2 => 'img10.png',
                        1 => 'img12.png'
                    ),
                $ary->get()
            );
    }

    public function testCustomSort ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testReverse ()
    {
        $ary = new \cPHP\Ary(array( 5, 2, 9, 8 ));
        $this->assertSame( $ary, $ary->reverse() );
        $this->assertSame(
                array( 3 => 8, 2 => 9, 1 => 2, 0 => 5 ),
                $ary->get()
            );


        $ary = new \cPHP\Ary(array( 5, 2, 9, 8 ));
        $this->assertSame( $ary, $ary->reverse(FALSE) );
        $this->assertSame(
                array( 8, 9, 2, 5 ),
                $ary->get()
            );
    }

    public function testShuffle ()
    {
        $ary = new \cPHP\Ary(array( 'a' => 5, 'b' => 2, 'c' => 9, 'd' => 8 ));
        $this->assertSame( $ary, $ary->shuffle() );
        $this->assertNotSame(
                array( 'a' => 5, 'b' => 2, 'c' => 9, 'd' => 8 ),
                $ary->get()
            );

        $this->assertContains(5, $ary->get());
        $this->assertContains(2, $ary->get());
        $this->assertContains(9, $ary->get());
        $this->assertContains(8, $ary->get());

        $this->assertSame(
                array(0, 1, 2, 3),
                $ary->keys()->get()
            );
    }

    public function testBubbleKeys ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testHone ()
    {
        $ary = new \cPHP\Ary(array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ));

        $result = $ary->hone('five', 'three', 'six');
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );

        // Make sure the original array remains unchanged
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ),
                $ary->get()
            );

        $this->assertEquals(
                array ( 'five' => 5, 'three' => 3, 'six' => 1 ),
                $result->get()
            );


        $result = $ary->hone( array('ten', 'eleven'), 'twelve');
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array ( 'ten' => 1, 'eleven' => 2, 'twelve' => 3 ),
                $result->get()
            );


        $result = $ary->hone( array('seven', 'six'), 'five', array(array('four')));
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array ( 'seven' => 1, 'six' => 2, 'five' => 5, 'four' => 4 ),
                $result->get()
            );
    }

    public function testTranslateKeys ()
    {
        $ary = new \cPHP\Ary(array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ));

        $result = $ary->translateKeys(array('one' => 'eno', 'three' => 'eerht', 'four' => 'ruof'));
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );

        // Make sure the original array remains unchanged
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3, 'four' => 4, 'five' => 5 ),
                $ary->get()
            );

        $this->assertEquals(
                array( 'eno' => 1, 'two' => 2, 'eerht' => 3, 'ruof' => 4, 'five' => 5 ),
                $result->get()
            );


        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'six' => 3, 'four' => 4, 'five' => 5 ),
                $ary->translateKeys(array('one' => 'five', 'three' => 'six'))->get()
            );


        try {
            $ary->translateKeys("This is not a valid key map");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertEquals("Must be an array or a \cPHP\Ary object", $err->getMessage());
        }


        try {
            $ary->translateKeys(array("five" => NULL) );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertEquals("Invalid key value", $err->getMessage());
        }


        try {
            $ary->translateKeys(array("five" => $this->getMock("stub_translateKeys")) );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertEquals("Invalid key value", $err->getMessage());
        }
    }

    public function testChangeKeyCase ()
    {
        $ary = new \cPHP\Ary(array( 'One' => 1, 'Two' => 2, 'Three' => 3 ));

        $result = $ary->changeKeyCase();

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3 ),
                $result->get()
            );


        $result = $ary->changeKeyCase( CASE_LOWER );

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'one' => 1, 'two' => 2, 'three' => 3 ),
                $result->get()
            );


        $result = $ary->changeKeyCase( CASE_UPPER );

        $this->assertEquals( array( 'One' => 1, 'Two' => 2, 'Three' => 3 ), $ary->get() );

        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertNotSame( $ary, $result );
        $this->assertEquals(
                array( 'ONE' => 1, 'TWO' => 2, 'THREE' => 3 ),
                $result->get()
            );
    }

    public function testImplode ()
    {
        $ary = new \cPHP\Ary(array(5, "string", "other"));

        $this->assertEquals( "5stringother", $ary->implode() );
        $this->assertEquals( "5, string, other", $ary->implode(", ") );
    }

    public function testCollect ()
    {

        $this->assertEquals(
                array(50, 90),
                \cPHP\Ary::create( array( "50", "90") )->collect("floatval")->get()
            );

        $lambda = function ( $value, $key ) { return "$key:$value"; };
        $this->assertEquals(
                array("0:50", "1:90"),
                \cPHP\Ary::create( array( "50", "90") )->collect($lambda)->get()
            );

        $this->assertEquals(
                array( 1 => "o50:1", 3 => "o90:3" ),
                \cPHP\Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect(array($this, "callbackObject"))
                    ->get()
            );

        $this->assertEquals(
                array( 1 => "s50:1", 3 => "s90:3" ),
                \cPHP\Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect(array(__CLASS__, "callbackStatic"))
                    ->get()
            );

        $this->assertEquals(
                array( 1 => "i50:1", 3 => "i90:3" ),
                \cPHP\Ary::create( array( 1 => "50",  3 => "90") )
                    ->collect( $this )
                    ->get()
            );

        try {
            \cPHP\Ary::create()->collect("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}
    }

    public function testFilter ()
    {

        $oddTest = function( $first, $second ) {
            return $first % 2 == 0;
        };

        $ary = new \cPHP\Ary( array(1, 2, 3, 4, 5 ) );

        $this->assertEquals(
                array(1 => 2, 3 => 4),
                $ary->filter( $oddTest )->get()
            );


        $ary = new \cPHP\Ary( array("1", "2", 3, 4, "5" ) );

        $this->assertEquals(
                array(0 => "1", 1 => "2", 4 => "5"),
                $ary->filter( "is_string" )->get()
            );


        try {
            \cPHP\Ary::create()->filter("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}

    }

    public function testEach ()
    {
        $result = array();
        $lambda = function ( $value, $key ) use ( &$result ) {
            $result[ $value ] = $key;
        };

        $ary = \cPHP\Ary::create( array( "one" => 50, "two" => 90 ) );

        $this->assertSame( $ary, $ary->each( $lambda ) );
        $this->assertEquals( array( 50 => "one", 90 => "two"), $result );
        $this->assertEquals( array( "one" => 50, "two" => 90 ), $ary->get() );

        $ary->each($this);
        $ary->each(array( $this, "callbackObject" ));
        $ary->each(array( __CLASS__, "callbackStatic" ));

        try {
            $ary->each("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {}

    }

    public function testInject ()
    {
        // Test an empty array
        $ary = new \cPHP\Ary;
        $this->assertSame(
                'original',
                $ary->inject( 'original', function () { return 'changed'; } )
            );


        // Create a callback
        $lambda = create_function(
                '$injected, $value, $key=0',
                'return $injected ."$value-$key,";'
            );

        $ary = new \cPHP\Ary( array( 1 => 2, 3 => 4, 5 => 6 ) );
        $this->assertSame(
                'start2-1,4-3,6-5,',
                $ary->inject( 'start', $lambda )
            );


        $this->assertSame(
                'start2-1,4-3,6-5,',
                $ary->inject( 'start', function ( $injected, $value, $key ) {
                    return $injected ."$value-$key,";
                } )
            );


        $obj = $this->getMock('mock', array('__invoke'));
        $obj->expects( $this->any() )
            ->method('__invoke')
            ->will( $this->returnCallback( function ( $injected, $value, $key ) {
                return $injected ."$value-$key,";
            } ) );

        $this->assertSame( 'start2-1,4-3,6-5,', $ary->inject('start', $obj) );
    }

    public function testCompact ()
    {

        $ary = new \cPHP\Ary( array( 0, TRUE, NULL, "string", FALSE, 1, array(), array(1.5, ""), "  ", "0" ) );

        $this->assertEquals(
                array( 1 => TRUE, 3 => "string", 5 => 1, 7 => array(1.5), 9 => "0"),
                $ary->compact()->get()
            );

        $this->assertEquals(
                array( 1 => TRUE, 2 => NULL, 3 => "string", 4 => FALSE, 5 => 1, 7 => array(1.5), 9 => "0"),
                $ary->compact( \cPHP\ALLOW_FALSE | \cPHP\ALLOW_NULL )->get()
            );


        $ary = new \cPHP\Ary(array(
                new \cPHP\Ary(array("full", "of", "stuff", FALSE)),
                new \cPHP\Ary,
                array(1.5, ""),
            ));

        $ary = $ary->compact();
        $this->assertThat( $ary, $this->isInstanceOf("cPHP\Ary") );

        $ary = $ary->get();

        $this->assertArrayHasKey( 0, $ary );
        $this->assertArrayHasKey( 2, $ary );
        $this->assertArrayNotHasKey( 1, $ary );

        $this->assertThat( $ary[0], $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array("full", "of", "stuff"),
                $ary[0]->get()
            );

        $this->assertType("array", $ary[2]);
        $this->assertEquals( array(1.5), $ary[2] );

    }

    public function testPluck ()
    {
        $obj = new stdClass;
        $obj->one = 'vive';
        $obj->four = 'viks';

        $ary = new \cPHP\Ary(array(
                2 => 'String',
                5 => new \cPHP\Ary(array( 'one' => 'vun', 'two' => 'voo' )),
                'blah' => 1,
                8 => array( 'one' => 'vee', 'four' => 'vour' ),
                'meh' => $obj,
                'str' => new \ArrayObject(array( 'one' => 'vevn' ))
            ));


        $result = $ary->pluck( 'one' );
        $this->assertNotSame( $ary, $result );
        $this->assertThat( $result, $this->isInstanceOf('\cPHP\Ary') );
        $this->assertSame(
                array( 5 => 'vun', 8 => 'vee', 'meh' => 'vive', 'str' => 'vevn' ),
                $result->get()
            );


        $result = $ary->pluck( 'four' );
        $this->assertNotSame( $ary, $result );
        $this->assertThat( $result, $this->isInstanceOf('\cPHP\Ary') );
        $this->assertSame(
                array( 8 => 'vour', 'meh' => 'viks' ),
                $result->get()
            );
    }

    public function testInvoke ()
    {
        $ary = new \cPHP\Ary(array(
                'meh' => new stub_classes_ary_invokeTester('one'),
                0 => 5,
                1 => new stub_classes_ary_invokeTester('two'),
                5 => 'string',
                8 => new stdClass,
            ));


        $result = $ary->invoke( 'get' );
        $this->assertNotSame( $ary, $result );
        $this->assertThat( $result, $this->isInstanceOf('\cPHP\Ary') );
        $this->assertSame(
                array( 'meh' => 'one:', 1 => 'two:' ),
                $result->get()
            );


        $result = $ary->invoke( 'get', 'arg1', 'arg2' );
        $this->assertNotSame( $ary, $result );
        $this->assertThat( $result, $this->isInstanceOf('\cPHP\Ary') );
        $this->assertSame(
                array( 'meh' => 'one:arg1:arg2', 1 => 'two:arg1:arg2' ),
                $result->get()
            );

    }

    public function testUnique ()
    {
        $ary = new \cPHP\Ary(array(1, 2, 4, 2, 9, 1, 6 ));

        $this->assertThat( $ary->unique(), $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array(0 => 1, 1 => 2, 2 => 4, 4 => 9, 6 => 6 ),
                $ary->unique()->get()
            );
    }

    public function testMerge ()
    {
        $ary = new \cPHP\Ary( array( 1, 2, "con" => 4 ) );

        $merged = $ary->merge( array( 5, 6, "con" => 7 ) );

        $this->assertThat( $merged, $this->isInstanceOf( "\cPHP\Ary" ) );

        $this->assertEquals(
                array( 1, 2, 5, 6, "con" => 7 ),
                $merged->get()
            );
    }

    public function testAdd()
    {
        $ary = new \cPHP\Ary( array( 1, 2, "con" => 4 ) );

        $merged = $ary->add( array( 2 => 3, "con" => 7 ) );

        $this->assertThat( $merged, $this->isInstanceOf( "\cPHP\Ary" ) );

        $this->assertEquals(
                array( 1, 2, 3, "con" => 4 ),
                $merged->get()
            );
    }

    public function testAny_true ()
    {

        // Test a closure callback
        $ary = new \cPHP\Ary( range(0, 10) );
        $result = $ary->any(function ( $value, $key ) {
            return $value == 6 ? TRUE : FALSE;
        });
        $this->assertTrue( $result );


        // Test a function name as a callback
        $ary = new \cPHP\Ary( range(5, -5) );
        $result = $ary->any("cPHP\\num\\negative");
        $this->assertTrue( $result );


        $ary = new \cPHP\Ary( array(5, 4, "yes", 3, "also yes", 1) );


        // Static method callback
        $result = $ary->any(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertTrue( $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertTrue(
                $ary->any(array($callback, "callbackObject"))
            );

        // Callable object callback
        $this->assertTrue( $ary->any($callback) );
    }

    public function testAny_false ()
    {
        // Test an empty array
        $ary = new \cPHP\Ary;
        $result = $ary->any(function ( $value, $key ) {
            return TRUE;
        });
        $this->assertFalse( $result );


        // Test a closure callback
        $ary = new \cPHP\Ary( range(0, 5) );
        $result = $ary->any(function ( $value, $key ) {
            return $value == 6 ? TRUE : FALSE;
        });
        $this->assertFalse( $result );


        // Test a function name as a callback
        $ary = new \cPHP\Ary( range(5, 0) );
        $result = $ary->any("cPHP\\num\\negative");
        $this->assertFalse( $result );


        $ary = new \cPHP\Ary( array(5, 4, "no", 3, "also no", 1) );


        // Static method callback
        $result = $ary->any(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertFalse( $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertFalse(
                $ary->any(array($callback, "callbackObject"))
            );


        // Callable object callback
        $this->assertFalse( $ary->any($callback) );
    }

    public function testAny_error ()
    {
        $ary = new \cPHP\Ary;

        // invoked without a proper callback
        try {
            $ary->any("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must be callable", $err->getMessage());
        }
    }

    public function testAll_true ()
    {
        // Test an empty array
        $ary = new \cPHP\Ary;
        $result = $ary->all(function ( $value, $key ) {
            return FALSE;
        });
        $this->assertTrue( $result );


        // Test a closure callback
        $ary = new \cPHP\Ary( range(0, 5) );
        $result = $ary->all(function ( $value, $key ) {
            return $value <= 5 ? TRUE : FALSE;
        });
        $this->assertTrue( $result );


        // Test a function name as a callback
        $ary = new \cPHP\Ary( range(-1, -5) );
        $result = $ary->all("cPHP\\num\\negative");
        $this->assertTrue( $result );


        $ary = new \cPHP\Ary( array("YES", "yes", "also yes") );


        // Static method callback
        $result = $ary->all(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertTrue( $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertTrue(
                $ary->all(array($callback, "callbackObject"))
            );

        // Callable object callback
        $this->assertTrue( $ary->all($callback) );
    }

    public function testAll_false ()
    {

        // Test a closure callback
        $ary = new \cPHP\Ary( range(0, 6) );
        $result = $ary->all(function ( $value, $key ) {
            return $value <= 5 ? TRUE : FALSE;
        });
        $this->assertFalse( $result );


        // Test a function name as a callback
        $ary = new \cPHP\Ary( range(5, -1) );
        $result = $ary->all("cPHP\\num\\negative");
        $this->assertFalse( $result );


        $ary = new \cPHP\Ary( array("yes", "yes", "no", 3, "also yes", 1) );


        // Static method callback
        $result = $ary->all(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertFalse( $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertFalse(
                $ary->all(array($callback, "callbackObject"))
            );


        // Callable object callback
        $this->assertFalse( $ary->all($callback) );
    }

    public function testAll_error ()
    {
        $ary = new \cPHP\Ary;

        // invoked without a proper callback
        try {
            $ary->all("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must be callable", $err->getMessage());
        }
    }

    public function testFind ()
    {

        // Test a closure callback
        $ary = new \cPHP\Ary( range(0, 10) );
        $result = $ary->find(function ( $value, $key ) {
            return $value == 6 ? TRUE : FALSE;
        });
        $this->assertSame( 6, $result );


        // Test a closure without a match
        $ary = new \cPHP\Ary( range(0, 10) );
        $result = $ary->find(function ( $value, $key ) {
            return $value == 50 ? TRUE : FALSE;
        });
        $this->assertFalse( $result );


        // Test a function name as a callback
        $ary = new \cPHP\Ary( range(5, -5) );
        $result = $ary->find("cPHP\\num\\negative");
        $this->assertSame( -1, $result );


        $ary = new \cPHP\Ary( array(5, 4, "yes", 3, "also yes", 1) );


        // Static method callback
        $result = $ary->find(array(
                "stub_classes_ary_boolCallbacks",
                "callbackStatic"
            ));
        $this->assertSame( "yes", $result );


        $callback = new stub_classes_ary_boolCallbacks;

        // Object method callback
        $this->assertSame(
                "yes",
                $ary->find(array($callback, "callbackObject"))
            );

        // Callable object callback
        $this->assertSame( "yes", $ary->find($callback) );

        // invoked without a proper callback
        try {
            $ary->find("This is an uncallable value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must be callable", $err->getMessage());
        }

    }

    public function testSearch ()
    {
        $ary = new \cPHP\Ary(array( 0 => 4, 5 => 3, "key" => "other"));

        $this->assertSame( 0, $ary->search(4) );
        $this->assertSame( 0, $ary->search("4") );
        $this->assertSame( 5, $ary->search(3) );
        $this->assertSame( "key", $ary->search("other") );

        $this->assertFalse( $ary->search("not in") );
        $this->assertFalse( $ary->search("OTHER") );
    }

    public function testWithout ()
    {
        $ary = new \cPHP\Ary(array( 1, 2, 3, "four", "five", "six" ));

        $result = $ary->without( 2 );
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 0 => 1, 2 => 3, 3 => "four", 4 => "five", 5 => "six"),
                $result->get()
            );

        $result = $ary->without( 2, "3" );
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 0 => 1, 3 => "four", 4 => "five", 5 => "six"),
                $result->get()
            );

        $result = $ary->without( 2, "3", "five", array("six") );
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array( 0 => 1, 3 => "four", 5 => "six"),
                $result->get()
            );
    }

    public function testWithoutKeys ()
    {
        $ary = new \cPHP\Ary(array(
                'One' => 1, 'Two' => 2, 'Three' => 3, 4 => 'Four', 5 => 'Five'
            ));

        $result = $ary->withoutKeys('One');
        $this->assertNotSame( $ary, $result );
        $this->assertSame(
                array( 'One' => 1, 'Two' => 2, 'Three' => 3, 4 => 'Four', 5 => 'Five' ),
                $ary->get()
            );
        $this->assertSame(
                array( 'Two' => 2, 'Three' => 3, 4 => 'Four', 5 => 'Five' ),
                $result->get()
            );


        $result = $ary->withoutKeys( array('One', 'two'), 5 );
        $this->assertNotSame( $ary, $result );
        $this->assertSame(
                array( 'One' => 1, 'Two' => 2, 'Three' => 3, 4 => 'Four', 5 => 'Five' ),
                $ary->get()
            );
        $this->assertSame(
                array( 'Two' => 2, 'Three' => 3, 4 => 'Four' ),
                $result->get()
            );

    }

    public function testSetBranch_basic ()
    {
        $ary = new \cPHP\Ary;

        $this->assertSame( $ary, $ary->branch("new", "one", "two", "three", "four") );
        $this->assertSame(
                array('one'),
                $ary->keys()->get()
            );

        $this->assertThat( $ary['one'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('two'),
                $ary['one']->keys()->get()
            );

        $this->assertThat( $ary['one']['two'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('three'),
                $ary['one']['two']->keys()->get()
            );

        $this->assertThat( $ary['one']['two']['three'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('four' => 'new'),
                $ary['one']['two']['three']->get()
            );
    }

    public function testSetBranch_flatten ()
    {
        $ary = new \cPHP\Ary;

        $this->assertSame( $ary, $ary->branch("new", array( array("one") ), "two", array( "three", "four" ) ) );
        $this->assertSame(
                array('one'),
                $ary->keys()->get()
            );

        $this->assertThat( $ary['one'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('two'),
                $ary['one']->keys()->get()
            );

        $this->assertThat( $ary['one']['two'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('three'),
                $ary['one']['two']->keys()->get()
            );

        $this->assertThat( $ary['one']['two']['three'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('four' => 'new'),
                $ary['one']['two']['three']->get()
            );
    }

    public function testSetBranch_pushLastKey ()
    {
        $ary = new \cPHP\Ary;

        $this->assertSame( $ary, $ary->branch("new", null) );
        $this->assertSame(
                array('new'),
                $ary->get()
            );

        $this->assertSame( $ary, $ary->branch("another", null) );
        $this->assertSame(
                array('new', 'another'),
                $ary->get()
            );


        $this->assertSame( $ary, $ary->branch("leaf", 'push', null) );
        $this->assertSame(
                array(0, 1, 'push'),
                $ary->keys()->get()
            );

        $this->assertThat( $ary['push'], $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array ('leaf'),
                $ary['push']->get()
            );
    }

    public function testSetBranch_pushMidKey ()
    {
        $ary = new \cPHP\Ary;

        $this->assertSame( $ary, $ary->branch("new", "one", null, "two") );
        $this->assertSame(
                array('one'),
                $ary->keys()->get()
            );

        $this->assertThat( $ary['one'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array(0),
                $ary['one']->keys()->get()
            );

        $this->assertThat( $ary['one'][0], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('two' => 'new'),
                $ary['one'][0]->get()
            );
    }

    public function testSetBranch_add ()
    {
        $ary = new \cPHP\Ary( array( "one" => array( "two" => "value" ) ) );

        $this->assertSame( $ary, $ary->branch("new", "one", "two", "three", "four") );
        $this->assertSame(
                array('one'),
                $ary->keys()->get()
            );

        $this->assertType( "array", $ary['one'] );
        $this->assertSame(
                array('two'),
                array_keys( $ary['one'] )
            );

        $this->assertThat( $ary['one']['two'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('three'),
                $ary['one']['two']->keys()->get()
            );

        $this->assertThat( $ary['one']['two']['three'], $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame(
                array('four' => 'new'),
                $ary['one']['two']['three']->get()
            );

    }

    public function testGetBranch ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testBranchExists ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testStringize ()
    {
        $ary = new \cPHP\Ary( array( 0,  array( 4.5, "another" ), "string", 1.5 ) );

        $this->assertSame( $ary, $ary->stringize() );

        $this->assertSame(
                array( "0", "4.5", "string", "1.5" ),
                $ary->get()
            );
    }

    public function testIntegerize ()
    {
        $ary = new \cPHP\Ary( array( 0,  array( 4.5, "another" ), "string", 1.5 ) );

        $this->assertSame( $ary, $ary->integerize() );

        $this->assertSame(
                array( 0, 1, 0, 1 ),
                $ary->get()
            );
    }

    public function testChangeCase ()
    {
        $ary = new \cPHP\Ary(
                array( "lower", "First", "lasT", "SHOUT", "Small phrase", "other Phrase" )
            );

        $this->assertSame( $ary, $ary->changeCase( \cPHP\Ary::CASE_LOWER ) );
        $this->assertSame(
                array( "lower", "first", "last", "shout", "small phrase", "other phrase" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary(
                array( "lower", "First", "lasT", "SHOUT", "Small phrase", "other Phrase" )
            );

        $this->assertSame( $ary, $ary->changeCase( \cPHP\Ary::CASE_UPPER ) );
        $this->assertSame(
                array( "LOWER", "FIRST", "LAST", "SHOUT", "SMALL PHRASE", "OTHER PHRASE" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary(
                array( "lower", "First", "lasT", "SHOUT", "Small phrase", "other Phrase" )
            );

        $this->assertSame( $ary, $ary->changeCase( \cPHP\Ary::CASE_UCFIRST ) );
        $this->assertSame(
                array( "Lower", "First", "LasT", "SHOUT", "Small phrase", "Other Phrase" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary(
                array( "lower", "First", "lasT", "SHOUT", "Small phrase", "other Phrase" )
            );

        $this->assertSame( $ary, $ary->changeCase( \cPHP\Ary::CASE_UCWORDS ) );
        $this->assertSame(
                array( "Lower", "First", "LasT", "SHOUT", "Small Phrase", "Other Phrase" ),
                $ary->get()
            );


        $ary = new \cPHP\Ary(
                array( "lower", "First", "lasT", "SHOUT", "Small phrase", "other Phrase" )
            );

        $this->assertSame( $ary, $ary->changeCase( \cPHP\Ary::CASE_NOSHOUT ) );
        $this->assertSame(
                $array = array( "lower", "First", "last", "Shout", "Small phrase", "other Phrase" ),
                $ary->get()
            );


        try {
            $ary->changeCase( "Invalid flag" );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Invalid Case Flag", $err->getMessage());
        }


        try {
            $ary->changeCase( 10000 );
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Invalid Case Flag", $err->getMessage());
        }
    }

    public function testToQuery_flat ()
    {
        $this->iniSet("arg_separator.output", "&");

        $ary = new \cPHP\Ary(array( "var" => "val", "other" => "something" ));
        $this->assertSame( "var=val&other=something", $ary->toQuery() );

        $ary = new \cPHP\Ary(array( "var" => "", "other" => "   " ));
        $this->assertSame( "var=&other=+++", $ary->toQuery() );
    }

    public function testToQuery_delim ()
    {
        $this->iniSet("arg_separator.output", "--");


        $ary = new \cPHP\Ary(array( "one" => "1", "two" => "2", "three" => 3 ));
        $this->assertSame( "one=1--two=2--three=3", $ary->toQuery() );


        $ary = new \cPHP\Ary(array( "one" => "1", "two" => "2", "three" => 3 ));
        $this->assertSame( "one=1--two=2--three=3", $ary->toQuery( FALSE ) );


        $ary = new \cPHP\Ary(array( "one" => "1", "two" => "2", "three" => 3 ));
        $this->assertSame( "one=1--two=2--three=3", $ary->toQuery( TRUE ) );


        $ary = new \cPHP\Ary(array( "one" => "1", "two" => "2", "three" => 3 ));
        $this->assertSame( "one=1--two=2--three=3", $ary->toQuery( NULL ) );


        $ary = new \cPHP\Ary(array( "one" => "1", "two" => "2", "three" => 3 ));
        $this->assertSame( "one=1!-!two=2!-!three=3", $ary->toQuery( "!-!" ) );
    }

    public function testToQuery_multi ()
    {
        $this->iniSet("arg_separator.output", "&");

        $ary = new \cPHP\Ary(array(
            "var" => array( "one", "two" ),
        ));
        $this->assertSame( "var%5B0%5D=one&var%5B1%5D=two", $ary->toQuery() );


        $ary = new \cPHP\Ary(array(
            "0" => array( "one", "two" ),
        ));
        $this->assertSame( "0%5B0%5D=one&0%5B1%5D=two", $ary->toQuery() );


        $ary = new \cPHP\Ary(array(
            "var" => array( "one", "two" => array(
                    "double" => "depth"
                )),
        ));
        $this->assertSame( "var%5B0%5D=one&var%5Btwo%5D%5Bdouble%5D=depth", $ary->toQuery() );

    }

    public function testToQuery_encoding ()
    {
        $this->iniSet("arg_separator.output", "&");

        $ary = new \cPHP\Ary(array( "var[]" => "encode" ));
        $this->assertSame( "var%5B%5D=encode", $ary->toQuery() );


        $ary = new \cPHP\Ary(array( "!@#" => "%^&" ));
        $this->assertSame( "%21%40%23=%25%5E%26", $ary->toQuery() );
    }

    public function testToQuery_iterators ()
    {
        $this->iniSet("arg_separator.output", "&");

        $ary = new \cPHP\Ary(array(
            "var" => new \cPHP\Ary(array( "one", "two" )),
        ));
        $this->assertSame( "var%5B0%5D=one&var%5B1%5D=two", $ary->toQuery() );


        $ary = new \cPHP\Ary(array(
            "var" => new ArrayIterator(array( "one", "two" )),
        ));
        $this->assertSame( "var%5B0%5D=one&var%5B1%5D=two", $ary->toQuery() );

    }

    public function testToQuery_object ()
    {
        $this->iniSet("arg_separator.output", "&");

        $obj = new stdClass;
        $obj->one = 1;
        $obj->two = "2";
        $ary = new \cPHP\Ary(array(
            "var" => $obj
        ));
        $this->assertSame( "var%5Bone%5D=1&var%5Btwo%5D=2", $ary->toQuery() );
    }

}

?>