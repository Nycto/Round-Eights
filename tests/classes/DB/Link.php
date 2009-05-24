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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_db_link extends PHPUnit_Framework_TestCase
{

    public function getMockLink ( $args = array() )
    {
        return $this->getMock(
                "\cPHP\DB\Link",
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected"),
                $args
            );
    }

    public function testConstruct ()
    {
        $mock = $this->getMockLink( array("db://example.com/datab") );

        $this->assertSame( "example.com", $mock->getHost() );
        $this->assertSame( "datab", $mock->getDatabase() );


        $mock = $this->getMockLink(array( array( "host" => "db.com", "port" => 42 ) ));

        $this->assertSame( "db.com", $mock->getHost() );
        $this->assertSame( 42, $mock->getPort() );
    }


    public function testCleanseValue_nonStrings ()
    {
        $this->assertSame(
        		"1",
                \cPHP\DB\Link::cleanseValue( 1, true, function () {} )
            );

        $this->assertSame(
        		"10.5",
                \cPHP\DB\Link::cleanseValue( 10.5, true, function () {} )
            );

        $this->assertSame(
        		"0",
                \cPHP\DB\Link::cleanseValue( 00, true, function () {} )
            );

        $this->assertSame(
        		"1",
                \cPHP\DB\Link::cleanseValue( true, true, function () {} )
            );

        $this->assertSame(
        		"0",
                \cPHP\DB\Link::cleanseValue( false, true, function () {} )
            );

        $this->assertSame(
        		"NULL",
                \cPHP\DB\Link::cleanseValue( null, true, function () {} )
            );

        $this->assertSame(
        		"100",
                \cPHP\DB\Link::cleanseValue( "100", true, function () {} )
            );

        $this->assertSame(
        		"0.5",
                \cPHP\DB\Link::cleanseValue( "0.5", true, function () {} )
            );

        $this->assertSame(
        		".5",
                \cPHP\DB\Link::cleanseValue( ".5", true, function () {} )
            );

        $this->assertSame(
    		"",
            \cPHP\DB\Link::cleanseValue(
        		null,
                false,
                function ( $value ) { return $value; }
            )
        );

        $this->assertSame(
    		"escaped string",
            \cPHP\DB\Link::cleanseValue(
        		"string",
                false,
                function ( $value ) { return "escaped ". $value; }
            )
        );
    }

    public function testIsSelect ()
    {
        $this->assertTrue(
                \cPHP\DB\Link::isSelect("SELECT * FROM table")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("    SELECT * FROM table")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect(" \r \n \t  SELECT * FROM table")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("  (  SELECT * FROM table )")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("(((SELECT * FROM table)))")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect(" (  ( ( SELECT * FROM table)))")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("EXPLAIN SELECT * FROM table")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("( (EXPLAIN SELECT * FROM table))")
            );

        $this->assertTrue(
                \cPHP\DB\Link::isSelect("( ( EXPLAIN   \n \t SELECT * FROM table))")
            );

        $this->assertFalse(
                \cPHP\DB\Link::isSelect("UPDATE table SET field = 1")
            );

        $this->assertFalse(
                \cPHP\DB\Link::isSelect("INSERT INTO table SET field = 1")
            );
    }

    public function testPersistentAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertFalse( $mock->getPersistent() );

        $this->assertSame( $mock, $mock->setPersistent(TRUE) );
        $this->assertTrue( $mock->getPersistent() );

        $this->assertSame( $mock, $mock->setPersistent("off") );
        $this->assertFalse( $mock->getPersistent() );

        $this->assertSame( $mock, $mock->setPersistent("on") );
        $this->assertTrue( $mock->getPersistent() );

    }

    public function testForceNewAccessors ()
    {

        $mock = $this->getMockLink();
        $this->assertFalse( $mock->getForceNew() );

        $this->assertSame( $mock, $mock->setForceNew(TRUE) );
        $this->assertTrue( $mock->getForceNew() );

        $this->assertSame( $mock, $mock->setForceNew("off") );
        $this->assertFalse( $mock->getForceNew() );

        $this->assertSame( $mock, $mock->setForceNew("on") );
        $this->assertTrue( $mock->getForceNew() );

    }

    public function testUsernameAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );

        $this->assertSame( $mock, $mock->setUserName("uname") );
        $this->assertTrue( $mock->userNameExists() );
        $this->assertSame( "uname", $mock->getUserName() );

        $this->assertSame( $mock, $mock->clearUserName() );
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );

        $this->assertSame( $mock, $mock->setUserName("uname") );
        $this->assertTrue( $mock->userNameExists() );
        $this->assertSame( "uname", $mock->getUserName() );

        $this->assertSame( $mock, $mock->setUserName("  ") );
        $this->assertFalse( $mock->userNameExists() );
        $this->assertNull( $mock->getUserName() );
    }

    public function testPasswordAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );

        $this->assertSame( $mock, $mock->setPassword("pword") );
        $this->assertTrue( $mock->passwordExists() );
        $this->assertSame( "pword", $mock->getPassword() );

        $this->assertSame( $mock, $mock->clearPassword() );
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );

        $this->assertSame( $mock, $mock->setPassword("pword") );
        $this->assertTrue( $mock->passwordExists() );
        $this->assertSame( "pword", $mock->getPassword() );

        $this->assertSame( $mock, $mock->setPassword("   ") );
        $this->assertFalse( $mock->passwordExists() );
        $this->assertNull( $mock->getPassword() );
    }

    public function testHostAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "localhost", $mock->getHost() );

        $this->assertSame( $mock, $mock->setHost("127.0.0.1") );
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "127.0.0.1", $mock->getHost() );

        $this->assertSame( $mock, $mock->clearHost() );
        $this->assertFalse( $mock->hostExists() );
        $this->assertNull( $mock->getHost() );

        $this->assertSame( $mock, $mock->setHost("127.0.0.1") );
        $this->assertTrue( $mock->hostExists() );
        $this->assertSame( "127.0.0.1", $mock->getHost() );

        $this->assertSame( $mock, $mock->setHost("   ") );
        $this->assertFalse( $mock->hostExists() );
        $this->assertNull( $mock->getHost() );
    }

    public function testPortAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );

        $this->assertSame( $mock, $mock->setPort(80) );
        $this->assertTrue( $mock->portExists() );
        $this->assertSame( 80, $mock->getPort() );

        $this->assertSame( $mock, $mock->clearPort() );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );

        $this->assertSame( $mock, $mock->setPort("100") );
        $this->assertTrue( $mock->portExists() );
        $this->assertSame( 100, $mock->getPort() );

        $this->assertSame( $mock, $mock->setPort( 0 ) );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );

        $this->assertSame( $mock, $mock->setPort( -50 ) );
        $this->assertFalse( $mock->portExists() );
        $this->assertNull( $mock->getPort() );
    }

    public function testDatabaseAccessors ()
    {
        $mock = $this->getMockLink();
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );

        $this->assertSame( $mock, $mock->setDatabase("dbase") );
        $this->assertTrue( $mock->databaseExists() );
        $this->assertSame( "dbase", $mock->getDatabase() );

        $this->assertSame( $mock, $mock->clearDatabase() );
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );

        $this->assertSame( $mock, $mock->setDatabase("dbase") );
        $this->assertTrue( $mock->databaseExists() );
        $this->assertSame( "dbase", $mock->getDatabase() );

        $this->assertSame( $mock, $mock->setDatabase("   ") );
        $this->assertFalse( $mock->databaseExists() );
        $this->assertNull( $mock->getDatabase() );
    }

    public function testValidateCredentials ()
    {
        $mock = $this->getMockLink();
        $mock->setUserName("uname")
            ->setHost("localhost")
            ->setDatabase("dbase");

        $this->assertSame( $mock, $mock->validateCredentials() );

        $mock = $this->getMockLink();
        $mock->clearHost();

        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Link $err ) {
            $this->assertSame( "UserName must be set", $err->getMessage() );
        }

        $mock->setUserName("uname");

        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Link $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }

        $mock->setHost("127.0.0.1");

        try {
            $mock->validateCredentials();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Link $err ) {
            $this->assertSame( "Database name must be set", $err->getMessage() );
        }

        $mock->setDatabase("dbname");

        $this->assertSame( $mock, $mock->validateCredentials() );
    }

    public function testGetHostWithPort ()
    {
        $mock = $this->getMockLink();
        $mock->clearHost();

        try {
            $mock->getHostWithPort();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Interaction $err ) {
            $this->assertSame( "Host must be set", $err->getMessage() );
        }

        $mock->setHost("localhost");
        $this->assertSame("localhost", $mock->getHostWithPort());

        $mock->setPort(80);
        $this->assertSame("localhost:80", $mock->getHostWithPort());
    }

    public function testFromArray ()
    {
        $mock = $this->getMockLink();

        $this->assertSame(
                $mock,
                $mock->fromArray(array(
                        "host" => "127.0.0.1",
                        "PoRt" => 50,
                        "!@#$  DATABASE" => "dbname"
                    ))
            );

        $this->assertSame( "127.0.0.1", $mock->getHost() );
        $this->assertSame( 50, $mock->getPort() );
        $this->assertSame( "dbname", $mock->getDatabase() );

    }

    public function testFromString ()
    {
        $mock = $this->getMockLink();

        try {
            $mock->fromURI("wakka.com");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "URL is not valid", $err->getMessage() );
        }

        $this->assertSame( $mock, $mock->fromURI("db://example.com/dbnm") );
        $this->assertSame("example.com", $mock->getHost());
        $this->assertSame("dbnm", $mock->getDatabase());

        $this->assertSame( $mock, $mock->fromURI("db://unm:pwd@localhost/otherDB?persistent=on") );
        $this->assertSame("localhost", $mock->getHost());
        $this->assertSame("unm", $mock->getUserName());
        $this->assertSame("pwd", $mock->getPassword());
        $this->assertSame("otherDB", $mock->getDatabase());
        $this->assertTrue( $mock->getPersistent() );

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
        catch ( \cPHP\Exception\DB\Link $err ) {
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
                "\cPHP\DB\Link",
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
                "\cPHP\DB\Link",
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
        catch ( \cPHP\Exception\Argument  $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            $mock->query("    ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument  $err ) {
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
        catch ( \cPHP\Exception\DB\Query  $err ) {
            $this->assertSame( "Query did not return a \cPHP\DB\Result object", $err->getMessage() );
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
                            new \cPHP\Exception\DB\Query(
                                    "SELECT * FROM table",
                                    "Example Exception"
                                )
                        )
                );

        try {
            $mock->query("SELECT * FROM table");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Query  $err ) {
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