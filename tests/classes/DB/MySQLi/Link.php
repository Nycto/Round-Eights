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
class classes_db_mysqli_link extends PHPUnit_MySQLi_Framework_TestCase
{

    public function testConnection_error ()
    {
        $link = new \r8\DB\MySQLi\Link(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            );

        try {
            $link->getLink();
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
        $link = new \r8\DB\MySQLi\Link( $this->getURI() );
        $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
        $this->assertTrue( $link->isConnected() );
    }

    public function testEscapeString ()
    {
        $link = $this->getLink();

        // Escape without a connection
        $this->assertSame(
        		"This \\'is\\' a string",
                $link->escapeString("This 'is' a string")
            );

        $link->getLink();

        // Escape WITH a connection
        $this->assertSame(
        		"This \\'is\\' a string",
                $link->escapeString("This 'is' a string")
            );


        // Escape an array
        $this->assertSame(
        		array( "This \\'is\\' a string" ),
                $link->escapeString( array("This 'is' a string") )
            );
    }

    public function testQuery_read ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT 50 + 10");

        $this->assertThat( $result, $this->isInstanceOf("r8\DB\MySQLi\Read") );

        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
    }

    public function testQuery_write ()
    {
        $link = $this->getLink();

        $result = $link->query("UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1");

        $this->assertThat( $result, $this->isInstanceOf("r8\DB\Result\Write") );

        $this->assertSame(
                "UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1",
                $result->getQuery()
            );
    }

    public function testDisconnect ()
    {
        $link = new \r8\DB\MySQLi\Link( $this->getURI() );
        $link->getLink();

        $this->assertTrue( $link->isConnected() );

        $this->assertSame( $link, $link->disconnect() );

        $this->assertFalse( $link->isConnected() );
    }

    public function testGetIdentifier ()
    {
        $link = new \r8\DB\MySQLi\Link( $this->getURI() );

        $this->assertSame(
                "MySQLi://root@localhost:3306",
                $link->getIdentifier()
            );

        $link = new \r8\DB\MySQLi\Link;
        $link->clearHost();

        $this->assertRegExp(
                "/MySQLi:\/\/[0-9a-zA-Z]+/",
                $link->getIdentifier()
            );
    }

}

?>