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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * This is a stub used to test the iteration
 */
class stub_db_result_read extends \h2o\DB\Result\Read
{

    /**
     * The data that will be iterated over
     */
    public $ary = array();

    /**
     * Counts the elements in the test array
     *
     * @return Integer
     */
    protected function rawCount ()
    {
        return count( $this->ary );
    }

    /**
     * Returns an empty array
     *
     * @return Array
     */
    protected function rawFields ()
    {
        return array();
    }

    /**
     * Returns the next value from the test array
     *
     * @return Array
     */
    protected function rawFetch ()
    {
        $nextData = each( $this->ary );
        return $nextData['value'];
    }

    /**
     * Seeks to a specific row in the test array
     *
     * @param Integer The raw to seek to
     */
    protected function rawSeek ( $offset )
    {
        if ( $offset == 0 ) {
            $return = reset( $this->ary );
            next( $this->ary );
            return $return;
        }
    }

    /**
     * Resets the result array to empty
     */
    protected function rawFree ()
    {
        $this->ary = array();
    }

}

/**
 * unit tests
 */
class classes_db_result_read extends PHPUnit_Framework_TestCase
{

    public function testHasResult_None ()
    {
        $mock = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array("not a resource", "SELECT * FROM table")
            );

        $this->assertFalse(
                $mock->hasResult()
            );
    }

    public function testHasResult_Object ()
    {
        $mock = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array( $this->getMock("MockResult"), "SELECT * FROM table")
            );

        $this->assertTrue(
                $mock->hasResult()
            );
    }

    public function testFree_noResult ()
    {
        $mock = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array("not a resource", "SELECT * FROM table")
            );

        $mock->expects( $this->never() )
            ->method("rawFree");

        $this->assertSame( $mock, $mock->free() );
    }

    public function testFree_fakedResult ()
    {
        $mock = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree", "hasResult"),
                array("not a resource", "SELECT * FROM table")
            );

        $mock->expects( $this->at(0) )
            ->method("hasResult")
            ->will( $this->returnValue(TRUE) );

        $mock->expects( $this->once() )
            ->method("rawFree");

        $this->assertSame( $mock, $mock->free() );
    }

    public function testDestruct ()
    {
        $mock = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree", "hasResult"),
                array("not a resource", "SELECT * FROM table")
            );

        $mock->expects( $this->at(0) )
            ->method("hasResult")
            ->will( $this->returnValue(TRUE) );

        $mock->expects( $this->once() )
            ->method("rawFree");

        $mock->__destruct();
    }

    public function testCount_valid ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(20) );

        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, count( $read ) );
    }

    public function testCount_invalid ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(null) );

        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, count( $read ) );
    }

    public function testGetFields_valid ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
    }

    public function testGetFields_invalid ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue(null) );

        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
        $this->assertSame( array(), $read->getFields() );
    }

    public function testIsField ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $this->assertTrue( $read->isField("one") );
        $this->assertTrue( $read->isField("two") );

        $this->assertFalse( $read->isField("One") );
        $this->assertFalse( $read->isField("TWO") );
        $this->assertFalse( $read->isField("NOT A FIELD") );
    }

    public function testFieldCount ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $this->assertSame( 2, $read->fieldCount() );
    }

    public function testSeek ()
    {
        $read = $this->getMock(
                "\h2o\DB\Result\Read",
                array("rawCount", "rawFetch", "rawSeek", "rawFields", "rawFree"),
                array(null, "SELECT * FROM table")
            );

        $read->expects( $this->once() )
            ->method("rawCount")
            ->will( $this->returnValue(5) );

        $read->expects( $this->at(1) )
            ->method("rawSeek")
            ->with( $this->equalTo(0) );

        $this->assertSame( $read, $read->seek( 0 ) );


        $read->expects( $this->at(0) )
            ->method("rawSeek")
            ->with( $this->equalTo(4) );

        $this->assertSame( $read, $read->seek( 6 ) );


        $read->expects( $this->never() )
            ->method("rawSeek");

        $this->assertSame( $read, $read->seek( 4 ) );

    }

    public function testIteration_forEach ()
    {
        $read = new stub_db_result_read( null, "SELECT * FROM test" );
        $input = array(
                array("one", "two"),
                array("three", "four"),
                array("six", "five"),
            );
        $read->ary = $input;

        $result = array();
        foreach($read AS $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame( $result, $input );


        $result = array();
        foreach($read AS $key => $value) {
            $result[$key] = $value;
        }

        $this->assertSame( $result, $input );

    }

    public function testIteration_Manual()
    {
        $read = new stub_db_result_read( null, "SELECT * FROM test" );
        $input = array(
                array("one", "two"),
                array("three", "four")
            );
        $read->ary = $input;


        $this->assertSame( $read, $read->next() );
        $this->assertSame(
                array("one", "two"),
                $read->current()
            );
        $this->assertSame( 0, $read->key() );


        $this->assertSame( $read, $read->next() );
        $this->assertSame(
                array("three", "four"),
                $read->current()
            );
        $this->assertSame( 1, $read->key() );


        $this->assertSame( $read, $read->next() );
        $this->assertFalse( $read->current() );
        $this->assertSame( 2, $read->key() );


        $this->assertSame( $read, $read->next() );
        $this->assertFalse( $read->current() );
        $this->assertSame( 2, $read->key() );
    }

}

?>