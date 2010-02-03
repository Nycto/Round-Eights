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
class classes_DB_Link extends PHPUnit_Framework_TestCase
{

    public function testCleanseValue_nonStrings ()
    {
        $this->assertSame(
                "1",
                \r8\DB\Link::cleanseValue( 1, true, function () {} )
            );

        $this->assertSame(
                "10.5",
                \r8\DB\Link::cleanseValue( 10.5, true, function () {} )
            );

        $this->assertSame(
                "0",
                \r8\DB\Link::cleanseValue( 00, true, function () {} )
            );

        $this->assertSame(
                "1",
                \r8\DB\Link::cleanseValue( true, true, function () {} )
            );

        $this->assertSame(
                "0",
                \r8\DB\Link::cleanseValue( false, true, function () {} )
            );

        $this->assertSame(
                "NULL",
                \r8\DB\Link::cleanseValue( null, true, function () {} )
            );

        $this->assertSame(
                "100",
                \r8\DB\Link::cleanseValue( "100", true, function () {} )
            );

        $this->assertSame(
                "0.5",
                \r8\DB\Link::cleanseValue( "0.5", true, function () {} )
            );

        $this->assertSame(
                ".5",
                \r8\DB\Link::cleanseValue( ".5", true, function () {} )
            );

        $this->assertSame(
            "",
            \r8\DB\Link::cleanseValue(
                null,
                false,
                function ( $value ) { return $value; }
            )
        );

        $this->assertSame(
            "escaped string",
            \r8\DB\Link::cleanseValue(
                "string",
                false,
                function ( $value ) { return "escaped ". $value; }
            )
        );
    }

    public function testIsSelect ()
    {
        $this->assertTrue(
                \r8\DB\Link::isSelect("SELECT * FROM table")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("    SELECT * FROM table")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect(" \r \n \t  SELECT * FROM table")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("  (  SELECT * FROM table )")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("(((SELECT * FROM table)))")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect(" (  ( ( SELECT * FROM table)))")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("EXPLAIN SELECT * FROM table")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("( (EXPLAIN SELECT * FROM table))")
            );

        $this->assertTrue(
                \r8\DB\Link::isSelect("( ( EXPLAIN   \n \t SELECT * FROM table))")
            );

        $this->assertFalse(
                \r8\DB\Link::isSelect("UPDATE table SET field = 1")
            );

        $this->assertFalse(
                \r8\DB\Link::isSelect("INSERT INTO table SET field = 1")
            );
    }

    public function testIsInsert ()
    {
        $this->assertTrue(
                \r8\DB\Link::isInsert("INSERT INTO table SET field = 1")
            );

        $this->assertTrue(
                \r8\DB\Link::isInsert("INSERT INTO table VALUES (1, 2)")
            );

        $this->assertTrue(
                \r8\DB\Link::isInsert(" \n\t  INSERT INTO table SET field = 1")
            );

        $this->assertTrue(
                \r8\DB\Link::isInsert("insert into table set field = 1")
            );

        $this->assertFalse(
                \r8\DB\Link::isInsert("INSERTER")
            );

        $this->assertFalse(
                \r8\DB\Link::isInsert("SELECT * FROM table")
            );

        $this->assertFalse(
                \r8\DB\Link::isInsert("UPDATE table SET field = 1")
            );
    }

    public function testConstruct_MissingExtension ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method( "getExtension" )
            ->will( $this->returnValue( "Not A Real Extension" ) );

        try {
            new \r8\DB\Link( $adapter );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Extension $err ) {
            $this->assertSame( "Extension is not loaded", $err->getMessage() );
        }
    }

    public function testConstruct_NoExtension ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method( "getExtension" )
            ->will( $this->returnValue( NULL ) );

        new \r8\DB\Link( $adapter );
    }

    public function testIsConnected ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');

        // Called twice because of the destructor
        $adapter->expects( $this->exactly(2) )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $link = new \r8\DB\Link( $adapter );

        $this->assertTrue( $link->isConnected() );
    }

    public function testDisconnect ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->at(1) )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );
        $adapter->expects( $this->once() )
            ->method( "disconnect" );

        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( $link, $link->disconnect() );
        $this->assertSame( $link, $link->disconnect() );
        $this->assertSame( $link, $link->disconnect() );
    }

    public function testQuery_emptyQuery()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $link = new \r8\DB\Link( $adapter );

        try {
            $link->query("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument  $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $link->query("    ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument  $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testQuery_invalidResult ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $adapter->expects( $this->once() )
            ->method( "query" )
            ->with( new PHPUnit_Framework_Constraint_SQL( "SELECT *" ) )
            ->will( $this->returnValue("not a result") );

        $link = new \r8\DB\Link( $adapter );

        try {
            $link->query("SELECT *");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Query  $err ) {
            $this->assertSame( 'Query did not return a \r8\iface\DB\Result object', $err->getMessage() );
        }
    }

    public function testQuery_throw ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $adapter->expects( $this->once() )
            ->method( "query" )
            ->with( new PHPUnit_Framework_Constraint_SQL( "SELECT *" ) )
            ->will( $this->throwException(
                new \r8\Exception\DB\Query(
                    "SELECT *",
                    "Example Exception"
                )
            ) );

        $link = new \r8\DB\Link( $adapter );

        try {
            $link->query("SELECT *");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Query  $err ) {
            $this->assertSame( "Example Exception", $err->getMessage() );
        }
    }

    public function testQuery_Valid ()
    {
        $result = $this->getMock('\r8\DB\Result\Read', array(), array(), '', FALSE);

        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $adapter->expects( $this->once() )
            ->method( "query" )
            ->with( new PHPUnit_Framework_Constraint_SQL( "SELECT *" ) )
            ->will( $this->returnValue( $result ) );

        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( $result, $link->query("SELECT *") );
    }

    public function testQuery_SQLObject ()
    {
        $result = $this->getMock('\r8\DB\Result\Read', array(), array(), '', FALSE);

        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "isConnected" )
            ->will( $this->returnValue( TRUE ) );

        $adapter->expects( $this->once() )
            ->method( "query" )
            ->with( new PHPUnit_Framework_Constraint_SQL( "SELECT *" ) )
            ->will( $this->returnValue( $result ) );

        $link = new \r8\DB\Link( $adapter );

        $query = $this->getMock('\r8\iface\DB\Query');
        $query->expects( $this->once() )
            ->method( "toSQL" )
            ->with( $this->equalTo($link) )
            ->will( $this->returnValue( "SELECT *" ) );


        $this->assertSame( $result, $link->query( $query ) );
    }

    public function testQuote_nonStrings ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->never() )
            ->method( "escape" )
            ->will( $this->returnValue( TRUE ) );

        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( "1", $link->quote( 1 ) );
        $this->assertSame( "10.5", $link->quote( 10.5 ) );
        $this->assertSame( "0", $link->quote( 00 ) );

        $this->assertSame( "1", $link->quote( true ) );
        $this->assertSame( "0", $link->quote( false ) );

        $this->assertSame( "NULL", $link->quote( null ) );

        // Now look for strings that can be treated as numbers
        $this->assertSame( "100", $link->quote( "100" ) );
        $this->assertSame( "0.5", $link->quote( "0.5" ) );
        $this->assertSame( ".5", $link->quote( ".5" ) );
    }

    public function testQuote_array ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->never() )
            ->method( "escape" )
            ->will( $this->returnValue( TRUE ) );

        $link = new \r8\DB\Link( $adapter );

        $this->assertSame(
            array("5", "5.5"),
            $link->quote(array(5, 5.5))
        );
    }

    public function testQuote_Strings ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method('escape')
            ->with( $this->equalTo("string thing") )
            ->will( $this->returnValue("quoted thing") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "'quoted thing'", $link->quote( "string thing" ) );


        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method('escape')
            ->with( $this->equalTo("") )
            ->will( $this->returnValue("") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "''", $link->quote( null, FALSE ) );


        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("+0123.45e6") )
            ->will( $this->returnValue("+0123.45e6") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "'+0123.45e6'", $link->quote( "+0123.45e6", FALSE ) );


        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("0xFF") )
            ->will( $this->returnValue("0xFF") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "'0xFF'", $link->quote( "0xFF", FALSE ) );


        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("+5") )
            ->will( $this->returnValue("+5") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "'+5'", $link->quote( "+5", FALSE ) );
    }

    public function testEscape_nonStrings ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->never() )->method("escape");
        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( "1", $link->escape( 1 ) );
        $this->assertSame( "10.5", $link->escape( 10.5 ) );
        $this->assertSame( "0", $link->escape( 00 ) );

        $this->assertSame( "1", $link->escape( true ) );
        $this->assertSame( "0", $link->escape( false ) );

        $this->assertSame( "NULL", $link->escape( null ) );

        // Now look for strings that can be treated as numbers
        $this->assertSame( "100", $link->escape( "100" ) );
        $this->assertSame( "0.5", $link->escape( "0.5" ) );
        $this->assertSame( ".5", $link->escape( ".5" ) );
    }

    public function testEscape_array ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->never() )->method("escape");
        $link = new \r8\DB\Link( $adapter );

        $this->assertSame(
                array("5", "5.5"),
                $link->escape(array(5, 5.5))
            );
    }

    public function testEscape_Strings ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("string thing") )
            ->will( $this->returnValue("escaped thing") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "escaped thing", $link->escape( "string thing" ) );



        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("") )
            ->will( $this->returnValue("") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "", $link->escape( null, FALSE ) );



        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("+0123.45e6") )
            ->will( $this->returnValue("+0123.45e6") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "+0123.45e6", $link->escape( "+0123.45e6", FALSE ) );



        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("0xFF") )
            ->will( $this->returnValue("0xFF") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "0xFF", $link->escape( "0xFF", FALSE ) );



        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->once() )
            ->method("escape")
            ->with( $this->equalTo("+5") )
            ->will( $this->returnValue("+5") );
        $link = new \r8\DB\Link( $adapter );
        $this->assertSame( "+5", $link->escape( "+5", FALSE ) );
    }

    public function testQuoteName ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->exactly(4) )
            ->method("quoteName")
            ->will( $this->returnCallback(function ($value) {
                return "`". $value ."`";
            }) );
        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( "`ident`", $link->quoteName( "ident" ) );
        $this->assertSame( "`_`", $link->quoteName( "_" ) );
        $this->assertSame( "`IN`", $link->quoteName( "IN" ) );
        $this->assertSame( "`As`", $link->quoteName( "As" ) );
        $this->assertSame( "I", $link->quoteName( "I" ) );
        $this->assertSame( "JF", $link->quoteName( "JF" ) );
        $this->assertSame( "Bla", $link->quoteName( "Bla" ) );
    }

    public function testGetIdentifier ()
    {
        $adapter = $this->getMock('\r8\iface\DB\Adapter\Link');
        $adapter->expects( $this->any() )
            ->method( "getIdentifier" )
            ->will( $this->returnValue( "db:uri" ) );

        $link = new \r8\DB\Link( $adapter );

        $this->assertSame( "db:uri", $adapter->getIdentifier() );
    }

}

?>