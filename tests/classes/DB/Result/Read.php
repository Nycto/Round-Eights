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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_DB_Result_Read extends PHPUnit_Framework_TestCase
{

    public function testSerialize ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        try {
            \serialize( $read );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame(
                'Database results can not be serialized. Consider using an \r8\Iterator\Cache object.',
                $err->getMessage()
            );
        }
    }

    public function testGetAdapter ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( $adapter, $read->getAdapter() );
    }

    public function testGetAdapter_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertNull( $read->getAdapter() );
    }

    public function testFree ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("free");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( $read, $read->free() );
        $this->assertSame( $read, $read->free() );
        $this->assertSame( $read, $read->free() );
    }

    public function testDestruct ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("free");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $read->__destruct();
    }

    public function testHasResult ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("free");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertTrue( $read->hasResult() );
        $this->assertTrue( $read->hasResult() );

        $read->free();

        $this->assertFalse( $read->hasResult() );
        $this->assertFalse( $read->hasResult() );
    }

    public function testCount ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(20) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, $read->count() );
        $this->assertSame( 20, count( $read ) );
    }

    public function testCount_FreeWithCount ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(20) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( 20, $read->count() );

        $read->free();
        $this->assertSame( 20, $read->count() );
    }

    public function testCount_FreeWithoutCount ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $read->free();

        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, $read->count() );
        $this->assertSame( 0, count( $read ) );
    }

    public function testGetFields ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("getFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
        $this->assertSame( array("one", "two"), $read->getFields() );
    }

    public function testGetFields_FreeWithFields ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("getFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $this->assertSame( array("one", "two"), $read->getFields() );

        $read->free();
        $this->assertSame( array("one", "two"), $read->getFields() );
    }

    public function testGetFields_FreeWithoutFields ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("getFields");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $read->free();

        $this->assertSame( array(), $read->getFields() );
    }

    public function testIsField ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("getFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertTrue( $read->isField("one") );
        $this->assertTrue( $read->isField("two") );

        $this->assertFalse( $read->isField("One") );
        $this->assertFalse( $read->isField("TWO") );
        $this->assertFalse( $read->isField("NOT A FIELD") );
    }

    public function testFieldCount ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("getFields")
            ->will( $this->returnValue( array("one", "two") ) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( 2, $read->fieldCount() );
        $this->assertSame( 2, $read->fieldCount() );
        $this->assertSame( 2, $read->fieldCount() );
    }

    public function testSeek ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(5) );

        $adapter->expects( $this->at(1) )
            ->method("seek")
            ->with( $this->equalTo(0) );
        $adapter->expects( $this->at(2) )
            ->method("fetch")
            ->will( $this->returnValue( array() ) );

        $adapter->expects( $this->at(3) )
            ->method("seek")
            ->with( $this->equalTo(4) );
        $adapter->expects( $this->at(4) )
            ->method("fetch")
            ->will( $this->returnValue( array() ) );

        $adapter->expects( $this->at(5) )
            ->method("seek")
            ->with( $this->equalTo(1) );
        $adapter->expects( $this->at(6) )
            ->method("fetch")
            ->will( $this->returnValue( array() ) );


        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );


        $this->assertSame( $read, $read->seek( 0 ) );
        $this->assertSame( $read, $read->seek( 6 ) );

        // This seek shouldn't cause an invokation because it doesn't cause an offset change
        $this->assertSame( $read, $read->seek( 4 ) );

        $this->assertSame( $read, $read->seek( 1 ) );
    }

    public function testSeek_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");
        $adapter->expects( $this->never() )->method("seek");
        $adapter->expects( $this->never() )->method("fetch");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertSame( $read, $read->seek( 0 ) );
    }

    public function testIteration ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(3) );

        $adapter->expects( $this->at(1) )
            ->method("fetch")
            ->will( $this->returnValue( array("one", "two") ) );
        $adapter->expects( $this->at(2) )
            ->method("fetch")
            ->will( $this->returnValue( array("three", "four") ) );
        $adapter->expects( $this->at(3) )
            ->method("fetch")
            ->will( $this->returnValue( array("six", "five") ) );

        $adapter->expects( $this->at(4) )
            ->method("seek")
            ->with( $this->equalTo(0) );

        $adapter->expects( $this->at(5) )
            ->method("fetch")
            ->will( $this->returnValue( array("one", "two") ) );
        $adapter->expects( $this->at(6) )
            ->method("fetch")
            ->will( $this->returnValue( array("three", "four") ) );
        $adapter->expects( $this->at(7) )
            ->method("fetch")
            ->will( $this->returnValue( array("six", "five") ) );

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );


        \r8\Test\Constraint\Iterator::assert(
            array(
                array("one", "two"),
                array("three", "four"),
                array("six", "five"),
            ),
            $read
        );

        \r8\Test\Constraint\Iterator::assert(
            array(
                array("one", "two"),
                array("three", "four"),
                array("six", "five"),
            ),
            $read
        );
    }

    public function testIterate_FreeWithoutIterating ()
    {
        $result = new \r8\DB\Result\Read(
            new \r8\DB\BlackHole\Result(
                array( 1, 2 ), array( 3, 4 )
            ),
            "SELECT 1"
        );

        $result->free();

        \r8\Test\Constraint\Iterator::assert(
            array(),
            $result
        );
    }

    public function testIterate_FreeAfterIterating ()
    {
        $result = new \r8\DB\Result\Read(
            new \r8\DB\BlackHole\Result(
                array( 1, 2 ),
                array( 3, 4 )
            ),
            "SELECT 1"
        );

        \r8\Test\Constraint\Iterator::assert(
            array( array( 1, 2 ), array( 3, 4 ) ),
            $result
        );

        $result->free();
        \r8\Test\Constraint\Iterator::assert(
            array(),
            $result
        );
    }

    public function testCurrent ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(3) );

        $adapter->expects( $this->once() )
            ->method("fetch")
            ->will( $this->returnValue( array("one", "two") ) );


        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );

        $this->assertSame( array("one", "two"), $read->current() );
        $this->assertSame( array("one", "two"), $read->current() );
        $this->assertSame( array("one", "two"), $read->current() );
    }

    public function testCurrent_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");
        $adapter->expects( $this->never() )->method("fetch");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertNull( $read->current() );
    }

    public function testNext_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");
        $adapter->expects( $this->never() )->method("fetch");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertSame( $read, $read->next() );
    }

    public function testKey_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");
        $adapter->expects( $this->never() )->method("fetch");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertNull( $read->key() );
    }

    public function testRewind_Freed ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter->expects( $this->never() )->method("count");
        $adapter->expects( $this->never() )->method("fetch");
        $adapter->expects( $this->never() )->method("seek");

        $read = new \r8\DB\Result\Read( $adapter, "SELECT *" );
        $read->free();

        $this->assertSame( $read, $read->rewind() );
    }

}

?>