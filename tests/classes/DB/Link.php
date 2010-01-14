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

    public function getMockLink ( $args = array() )
    {
        return $this->getMock(
                '\r8\DB\Link',
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected"),
                $args
            );
    }

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

    public function testIsConnected ()
    {
        $mock = $this->getMockLink();

        $this->assertFalse( $mock->isConnected() );
    }

    public function testGetLink_invalidResource ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method( "rawConnect" )
            ->will( $this->returnValue(FALSE) );

        $mock->fromURI("db://user:pword@localhost/dbname");

        try {
            $mock->getLink();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertSame( "Database connector did not return a resource or an object", $err->getMessage() );
        }
    }

    public function testGetIdentifier ()
    {
        $mock = $this->getMockLink();
        $mock->clearHost();

        $this->assertRegExp(
                '/^db\:\/\/hash\:[0-9a-zA-Z\_]+$/',
                $mock->getIdentifier()
            );

        $mock->setHost('127.0.0.1');
        $this->assertRegExp(
                '/^db\:\/\/127\.0\.0\.1$/',
                $mock->getIdentifier()
            );

        $mock->setPort( 8080 );
        $this->assertRegExp(
                '/^db\:\/\/127\.0\.0\.1\:8080$/',
                $mock->getIdentifier()
            );

        $mock->setUserName( 'uname' );
        $this->assertRegExp(
                '/^db\:\/\/uname\@127\.0\.0\.1\:8080$/',
                $mock->getIdentifier()
            );

        $mock->clearPort();
        $this->assertRegExp(
                '/^db\:\/\/uname\@127\.0\.0\.1$/',
                $mock->getIdentifier()
            );
    }

    public function testDisconnect ()
    {
        $mock = $this->getMockLink();
        $this->assertSame( $mock, $mock->disconnect() );
    }

    public function testDisconnect_fakedConnection ()
    {

        $mock = $this->getMock(
                '\r8\DB\Link',
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected", "isConnected", "getLink")
            );

        $mock->expects( $this->at( 0 ) )
            ->method("isConnected")
            ->will( $this->returnValue(TRUE) );

        $mock->expects( $this->once() )
            ->method("rawDisconnect");

        $this->assertSame( $mock, $mock->disconnect() );
    }

    public function testDestruct ()
    {

        $mock = $this->getMock(
                '\r8\DB\Link',
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected", "isConnected", "getLink")
            );

        $mock->expects( $this->at( 0 ) )
            ->method("isConnected")
            ->will( $this->returnValue(TRUE) );

        $mock->expects( $this->once() )
            ->method("rawDisconnect");

        $mock->__destruct();
    }

    public function testQuery_emptyQuery()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->never() )
            ->method( "rawQuery" );

        try {
            $mock->query("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument  $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $mock->query("    ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument  $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testQuery_invalidResult ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method( "rawQuery" )
            ->with( $this->equalTo("SELECT * FROM table") )
            ->will( $this->returnValue("not a result") );

        try {
            $mock->query("SELECT * FROM table");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Query  $err ) {
            $this->assertSame( "Query did not return a \r8\DB\Result object", $err->getMessage() );
        }
    }

    public function testQuery_throw ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method( "rawQuery" )
            ->with( $this->equalTo("SELECT * FROM table") )
            ->will(
                    $this->throwException(
                            new \r8\Exception\DB\Query(
                                    "SELECT * FROM table",
                                    "Example Exception"
                                )
                        )
                );

        try {
            $mock->query("SELECT * FROM table");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Query  $err ) {
            $this->assertSame( "Example Exception", $err->getMessage() );
        }
    }

    public function testQuote_nonStrings ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->never() )
            ->method("quoteString");

        $this->assertSame( "1", $mock->quote( 1 ) );
        $this->assertSame( "10.5", $mock->quote( 10.5 ) );
        $this->assertSame( "0", $mock->quote( 00 ) );

        $this->assertSame( "1", $mock->quote( true ) );
        $this->assertSame( "0", $mock->quote( false ) );

        $this->assertSame( "NULL", $mock->quote( null ) );

        // Now look for strings that can be treated as numbers
        $this->assertSame( "100", $mock->quote( "100" ) );
        $this->assertSame( "0.5", $mock->quote( "0.5" ) );
        $this->assertSame( ".5", $mock->quote( ".5" ) );
    }

    public function testQuote_array ()
    {
        $mock = $this->getMockLink();
        $this->assertSame(
                array("5", "5.5"),
                $mock->quote(array(5, 5.5))
            );
    }

    public function testQuote_Strings ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("string thing") )
            ->will( $this->returnValue("quoted thing") );
        $this->assertSame( "'quoted thing'", $mock->quote( "string thing" ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("") )
            ->will( $this->returnValue("") );
        $this->assertSame( "''", $mock->quote( null, FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("+0123.45e6") )
            ->will( $this->returnValue("+0123.45e6") );
        $this->assertSame( "'+0123.45e6'", $mock->quote( "+0123.45e6", FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("0xFF") )
            ->will( $this->returnValue("0xFF") );
        $this->assertSame( "'0xFF'", $mock->quote( "0xFF", FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("+5") )
            ->will( $this->returnValue("+5") );
        $this->assertSame( "'+5'", $mock->quote( "+5", FALSE ) );
    }

    public function testEscape_nonStrings ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->never() )
            ->method("escapeString");

        $this->assertSame( "1", $mock->escape( 1 ) );
        $this->assertSame( "10.5", $mock->escape( 10.5 ) );
        $this->assertSame( "0", $mock->escape( 00 ) );

        $this->assertSame( "1", $mock->escape( true ) );
        $this->assertSame( "0", $mock->escape( false ) );

        $this->assertSame( "NULL", $mock->escape( null ) );

        // Now look for strings that can be treated as numbers
        $this->assertSame( "100", $mock->escape( "100" ) );
        $this->assertSame( "0.5", $mock->escape( "0.5" ) );
        $this->assertSame( ".5", $mock->escape( ".5" ) );
    }

    public function testEscape_array ()
    {
        $mock = $this->getMockLink();
        $this->assertSame(
                array("5", "5.5"),
                $mock->escape(array(5, 5.5))
            );
    }

    public function testEscape_Strings ()
    {
        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("string thing") )
            ->will( $this->returnValue("escaped thing") );
        $this->assertSame( "escaped thing", $mock->escape( "string thing" ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("") )
            ->will( $this->returnValue("") );
        $this->assertSame( "", $mock->escape( null, FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("+0123.45e6") )
            ->will( $this->returnValue("+0123.45e6") );
        $this->assertSame( "+0123.45e6", $mock->escape( "+0123.45e6", FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("0xFF") )
            ->will( $this->returnValue("0xFF") );
        $this->assertSame( "0xFF", $mock->escape( "0xFF", FALSE ) );


        $mock = $this->getMockLink();
        $mock->expects( $this->once() )
            ->method("escapeString")
            ->with( $this->equalTo("+5") )
            ->will( $this->returnValue("+5") );
        $this->assertSame( "+5", $mock->escape( "+5", FALSE ) );
    }

}

?>