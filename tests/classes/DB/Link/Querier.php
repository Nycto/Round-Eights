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
class classes_DB_Link_Querier extends PHPUnit_Framework_TestCase
{

    public function testBegin ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("BEGIN") )
            ->will( $this->returnValue("Result Set") );

        $this->assertSame( $query, $query->begin() );
    }

    public function testCommit ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("COMMIT") )
            ->will( $this->returnValue("Result Set") );

        $this->assertSame( $query, $query->commit() );
    }

    public function testRollBack ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $link->expects( $this->once() )
            ->method("query")
            ->with( $this->equalTo("ROLLBACK") )
            ->will( $this->returnValue("Result Set") );

        $this->assertSame( $query, $query->rollBack() );
    }

    public function testGetFieldList ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );


        $link->expects( $this->at(0) )
            ->method("quote")
            ->with( $this->equalTo("value") )
            ->will( $this->returnValue("'value'") );

        $link->expects( $this->at(1) )
            ->method("quote")
            ->with( $this->equalTo("wakka") )
            ->will( $this->returnValue("'wakka'") );

        $this->assertEquals(
                $query->getFieldList( array('data' => 'value', 'label' => 'wakka') ),
                "`data` = 'value', `label` = 'wakka'"
            );

        $link->expects( $this->at(0) )
            ->method("quote")
            ->with( $this->equalTo(5) )
            ->will( $this->returnValue("5") );

        $this->assertEquals(
                $query->getFieldList( array('data' => 5) ),
                "`data` = 5"
            );

        try {
            $query->getFieldList( array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testInsert_Errors ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        try {
            $query->insert( "", array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            $query->insert( "tablename", array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testInsert_Success ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("INSERT INTO table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue(
                    new \r8\DB\Result\Write(1, 20, "INSERT")
                ));

        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));

        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));

        $this->assertSame(
                20,
                $query->insert("table", array('field1' => 404, 'field2' => 'error'))
            );
    }

    public function testInsert_ReturnFalse ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("INSERT INTO table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue( FALSE ));

        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));

        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));

        $this->assertFalse(
                $query->insert("table", array('field1' => 404, 'field2' => 'error'))
            );
    }

    public function testUpdate_Errors ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        try {
            $query->update( "", null, array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

        try {
            $query->update( "tablename", null, array() );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testUpdate_NoWhere ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $result = new \r8\DB\Result\Write(1, null, "UPDATE");

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET `field1` = 404, `field2` = 'error'" ) )
            ->will( $this->returnValue( $result ));

        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));

        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));

        $this->assertSame(
                $result,
                $query->update("table", null, array('field1' => 404, 'field2' => 'error'))
            );
    }

    public function testUpdate_WithWhere ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $result = new \r8\DB\Result\Write(1, null, "UPDATE");

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET `field1` = 404, `field2` = 'error' WHERE id > 5" ) )
            ->will( $this->returnValue( $result ));

        $link->expects($this->at(0))
            ->method("quote")
            ->with( $this->equalTo(404) )
            ->will( $this->returnValue( 404 ));

        $link->expects($this->at(1))
            ->method("quote")
            ->with( $this->equalTo('error') )
            ->will( $this->returnValue("'error'"));

        $this->assertSame(
                $result,
                $query->update("table", "id > 5", array('field1' => 404, 'field2' => 'error'))
            );
    }

    public function testGetRow_WrongResult ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );


        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET id = 1" ) )
            ->will( $this->returnValue( new \r8\DB\Result\Write(0, null, "UPDATE") ));

        try {
            $query->getRow( "UPDATE table SET id = 1" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame("Query did not a valid Read result object", $err->getMessage());
        }

    }

    public function testGetRow_valid ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $result = $this->getMock( '\r8\iface\DB\Result\Read' );

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));

        $result->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(5));

        $result->expects( $this->once() )
            ->method("seek")
            ->with( $this->equalTo(0) );

        $result->expects( $this->once() )
            ->method("current")
            ->will( $this->returnValue(array( 'one', 'two' )));

        $result->expects( $this->once() )
            ->method("free");

        $this->assertSame(
                array( 'one', 'two' ),
                $query->getRow( "SELECT * FROM table" )
            );
    }

    public function testGetRow_otherRow ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $result = $this->getMock( '\r8\iface\DB\Result\Read' );

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));


        $result->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(5));

        $result->expects( $this->once() )
            ->method("seek")
            ->with( $this->equalTo(3) );

        $result->expects( $this->once() )
            ->method( "current" )
            ->will( $this->returnValue( array( 'one', 'two' ) ) );

        $result->expects( $this->once() )
            ->method("free");

        $this->assertSame(
            array( 'one', 'two' ),
            $query->getRow( "SELECT * FROM table", 3 )
        );

    }

    public function testGetRow_noResults ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        $result = $this->getMock( '\r8\iface\DB\Result\Read' );

        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( $result ));


        $result->expects( $this->never() )
            ->method("seek");

        $result->expects( $this->once() )
            ->method("count")
            ->will( $this->returnValue(0));

        $this->assertNull( $query->getRow( "SELECT * FROM table" ) );
    }

    public function testGetField_errors ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        try {
            $query->getField( "", "SELECT * FROM TABLE");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testGetField_wrongResult ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );


        $link->expects($this->once())
            ->method("query")
            ->with( $this->equalTo("UPDATE table SET id = 1" ) )
            ->will( $this->returnValue( new \r8\DB\Result\Write(0, null, "UPDATE") ));

        try {
            $query->getField( "fld", "UPDATE table SET id = 1" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame("Query did not a valid Read result object", $err->getMessage());
        }

    }

    public function testGetField_nonArray ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( "This is not valid" ));

        try {
            $query->getField( "fld", "SELECT * FROM table" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Interaction $err ) {
            $this->assertSame("Row was not an array or accessable as an array", $err->getMessage());
        }

    }

    public function testGetField_noField ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( array( 'id' => 1, 'value' => 'cejijunto' ) ));

        try {
            $query->getField( "fld", "SELECT * FROM table" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Field does not exist in row", $err->getMessage());
        }

    }

    public function testGetField_array ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue( array( 'id' => 1, 'value' => 'cejijunto' ) ));

        $this->assertSame(
                'cejijunto',
                $query->getField( "value", "SELECT * FROM table" )
            );

    }

    public function testGetField_object ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT * FROM table" ) )
            ->will( $this->returnValue(
                    array( 'id' => 1, 'value' => 'cejijunto' )
                ));

        $this->assertSame(
                'cejijunto',
                $query->getField( "value", "SELECT * FROM table" )
            );

    }

    public function testCount_errors ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = new \r8\DB\Link\Querier( $link );

        try {
            $query->count( "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }

    }

    public function testCount_valid ()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT COUNT(*) AS cnt FROM table" ) )
            ->will( $this->returnValue( array( 'cnt' => 25 ) ));

        $this->assertSame( 25, $query->count("table") );

    }

    public function testCount_withWhere()
    {
        $link = $this->getMock( '\r8\iface\DB\Link' );

        $query = $this->getMock(
                '\r8\DB\Link\Querier',
                array("getRow"),
                array( $link )
            );

        $query->expects($this->once())
            ->method("getRow")
            ->with( $this->equalTo("SELECT COUNT(*) AS cnt FROM table WHERE value = 'yes'" ) )
            ->will( $this->returnValue( array( 'cnt' => 16 ) ));

        $this->assertSame( 16, $query->count("table", "value = 'yes'") );

    }

}

?>