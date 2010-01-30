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
class classes_DB_SQLite_Link extends PHPUnit_Framework_TestCase
{

    /**
     * Ensures that all the prerequisites exist for connecting to a SQLite db
     */
    public function setUp ()
    {
        if ( !extension_loaded("sqlite") )
            $this->markTestSkipped("SQLite extension is not loaded");

        // Ensure the proper configuration exists
        $config = new r8_Test_Config( "SQLITE", array("FILE", "TABLE") );
        $config->test();

        // Test the connection
        $error = null;
        $db = @sqlite_open( SQLITE_FILE, 0666, $error );
        if ( !$db )
            $this->markTestSkipped("SQLite Connection Error: ". $error);
    }

    /**
     * Returns a test database connection
     *
     * @return \r8\DB\SQLite\Link
     */
    public function getTestLink ()
    {
        return new \r8\DB\SQLite\Link(
            SQLITE_FILE,
            new \r8\DB\Config
        );
    }

    public function testConnection_error ()
    {
        $link = new \r8\DB\SQLite\Link(
            "/tmp",
            new \r8\DB\Config
        );

        try {
            $link->connect();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertContains(
                    "unable to open database: /tmp",
                    $err->getMessage()
                );
        }
    }

    public function testConnection ()
    {
        $link = $this->getTestLink();
        $this->assertFalse( $link->isConnected() );
        $this->assertNull( $link->connect() );
        $this->assertTrue( $link->isConnected() );
    }

    public function testEscapeString ()
    {
        $link = $this->getTestLink();
        $this->assertSame(
            "This ''is'' a string",
            $link->escape("This 'is' a string")
        );
    }

    public function testQuoteName ()
    {
        $link = $this->getTestLink();
        $this->assertSame( '"I"', $link->quoteName("I") );
        $this->assertSame( '"JF"', $link->quoteName("JF") );
        $this->assertSame( '"Ident"', $link->quoteName("Ident") );
    }

    public function testQuery_read ()
    {
        $link = $this->getTestLink();

        $result = $link->query("SELECT 50 + 10 AS result");

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Read') );

        PHPUnit_Framework_Constraint_SQL::assert(
            "SELECT 50 + 10 AS result",
            $result->getQuery()
        );

        $this->assertSame( 1, $result->count() );
        $this->assertSame( array("result"), $result->getFields() );
        $this->assertEquals( array("result" => 60), $result->current() );
    }

    public function testQuery_write ()
    {
        $link = $this->getTestLink();

        $result = $link->query(
            "CREATE TEMPORARY TABLE ". SQLITE_TABLE ."(id INT, str TEXT)"
        );

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 0, $result->getAffected() );
        $this->assertNull( $result->getInsertID() );


        $result = $link->query("INSERT INTO ". SQLITE_TABLE ." (str) VALUES ('alpha')");

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 1, $result->getAffected() );
        $this->assertSame( 1, $result->getInsertID() );

        PHPUnit_Framework_Constraint_SQL::assert(
            "INSERT INTO ". SQLITE_TABLE ." (str) VALUES ('alpha')",
            $result->getQuery()
        );
    }

    public function testQuery_Error ()
    {
        $link = $this->getTestLink();

        try {
            $link->query("Not a valid query");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Query $err ) {
            $this->assertContains( "SQL logic error or missing database", $err->getMessage() );
        }
    }

    public function testDisconnect ()
    {
        $link = $this->getTestLink();

        $link->connect();
        $this->assertTrue( $link->isConnected() );

        $this->assertNull( $link->disconnect() );
        $this->assertFalse( $link->isConnected() );
    }

    public function testGetIdentifier ()
    {
        $link = new \r8\DB\SQLite\Link(
            "/path/to/database.sqlite",
            new \r8\DB\Config
        );

        $this->assertSame(
                "sqlite:///path/to/database.sqlite",
                $link->getIdentifier()
            );
    }

    public function testGetExtension ()
    {
        $link = $this->getTestLink();
        $this->assertSame( "sqlite", $link->getExtension() );
    }

    public function testSerialize ()
    {
        $link = $this->getTestLink();
        $link->connect();

        $serialized = serialize( $link );

        $this->assertEquals(
            $this->getTestLink(),
            unserialize($serialized)
        );
    }

}

?>