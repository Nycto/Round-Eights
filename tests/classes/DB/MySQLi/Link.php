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
class classes_DB_Mysqli_Link extends PHPUnit_Framework_TestCase
{

    /**
     * Ensures that all the prerequisites exist for connecting via mysqli
     */
    public function setUp ()
    {
        if ( !extension_loaded("mysqli") )
            $this->markTestSkipped("MySQLi extension is not loaded");

        // Ensure the proper configuration exists
        $config = new \r8\Test\Config(
                "MYSQLI",
                array( "HOST", "PORT", "DATABASE", "USERNAME", "PASSWORD", "TABLE" )
            );
        $config->test();

        // Test the connection
        $mysqli = @new mysqli(
                MYSQLI_HOST,
                MYSQLI_USERNAME,
                MYSQLI_PASSWORD,
                MYSQLI_DATABASE,
                MYSQLI_PORT
            );

        if ($mysqli->connect_error)
            $this->markTestSkipped("MySQLi Connection Error: ".  mysqli_connect_error());

        $mysqli->close();
    }

    /**
     * Returns a test database connection
     *
     * @return \r8\DB\MySQLi\Link
     */
    public function getTestLink ()
    {
        return new \r8\DB\MySQLi\Link(
            new \r8\DB\Config(
                "db://". MYSQLI_USERNAME .":". MYSQLI_PASSWORD
                ."@". MYSQLI_HOST .":". MYSQLI_PORT
                ."/". MYSQLI_DATABASE
            )
        );
    }

    public function testConnection_error ()
    {
        $link = new \r8\DB\MySQLi\Link(
            new \r8\DB\Config(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            )
        );

        try {
            $link->connect();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\DB\Link $err ) {
            $this->assertContains(
                    "Access denied for user",
                    $err->getMessage()
                );
        }
    }

    public function testConnection ()
    {
        $link = $this->getTestLink();
        $this->assertNull( $link->connect() );
        $this->assertTrue( $link->isConnected() );
    }

    public function testEscapeString ()
    {
        $link = $this->getTestLink();

        // Escape without a connection
        $this->assertSame(
            "This \\'is\\' a string",
            $link->escape("This 'is' a string")
        );

        $link->connect();

        // Escape WITH a connection
        $this->assertSame(
                "This \\'is\\' a string",
                $link->escape("This 'is' a string")
            );


        // Escape an array
        $this->assertSame(
                array( "This \\'is\\' a string" ),
                $link->escape( array("This 'is' a string") )
            );
    }

    public function testQuoteName ()
    {
        $link = $this->getTestLink();
        $this->assertSame( "`I`", $link->quoteName("I") );
        $this->assertSame( "`JF`", $link->quoteName("JF") );
        $this->assertSame( "`Ident`", $link->quoteName("Ident") );
    }

    public function testQuery_read ()
    {
        $link = $this->getTestLink();

        $result = $link->query("SELECT 50 + 10");

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Read') );

        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
    }

    public function testQuery_write ()
    {
        $link = $this->getTestLink();

        $result = $link->query(
            "CREATE TEMPORARY TABLE `". MYSQLI_TABLE ."` (
                `id` INT NOT NULL auto_increment,
                PRIMARY KEY ( `id` )
            )"
        );

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 0, $result->getAffected() );
        $this->assertNull( $result->getInsertID() );


        $result = $link->query("INSERT INTO ". MYSQLI_TABLE ." SET id = NULL");

        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 1, $result->getAffected() );
        $this->assertSame( 1, $result->getInsertID() );

        \r8\Test\Constraint\SQL::assert(
            "INSERT INTO ". MYSQLI_TABLE ." SET id = NULL",
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
            $this->assertContains( "You have an error in your SQL syntax", $err->getMessage() );
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
        $link = new \r8\DB\MySQLi\Link(
            new \r8\DB\Config("db://user@example.com:2020/db")
        );

        $this->assertSame(
                "MySQLi://user@example.com:2020",
                $link->getIdentifier()
            );
    }

    public function testGetExtension ()
    {
        $link = $this->getTestLink();
        $this->assertSame( "mysqli", $link->getExtension() );
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

