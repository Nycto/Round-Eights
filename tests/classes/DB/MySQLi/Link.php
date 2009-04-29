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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_db_mysqli_link extends PHPUnit_MySQLi_Framework_TestCase
{

    public function testConnection_error ()
    {
        $link = new \cPHP\DB\MySQLi\Link(
                "db://notMyUsername:SonOfA@". MYSQLI_HOST ."/databasethatisntreal"
            );

        try {
            $link->getLink();
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\DB\Link $err ) {
            $this->assertContains(
                    "Access denied for user",
                    $err->getMessage()
                );
        }
    }

    public function testConnection ()
    {
        $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );
        $this->assertThat( $link->getLink(), $this->isInstanceOf("mysqli") );
        $this->assertTrue( $link->isConnected() );
    }

    public function testEscape ()
    {
        $link = $this->getLink();

        // Escape without a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));

        $link->getLink();

        // Escape WITH a connection
        $this->assertSame("This \\'is\\' a string", $link->escape("This 'is' a string"));
    }

    public function testQuery_read ()
    {
        $link = $this->getLink();

        $result = $link->query("SELECT 50 + 10");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\MySQLi\Read") );

        $this->assertSame( "SELECT 50 + 10", $result->getQuery() );
    }

    public function testQuery_write ()
    {
        $link = $this->getLink();

        $result = $link->query("UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1");

        $this->assertThat( $result, $this->isInstanceOf("cPHP\DB\Result\Write") );

        $this->assertSame(
                "UPDATE ". MYSQLI_TABLE ." SET id = 1 WHERE id = 1",
                $result->getQuery()
            );
    }

    public function testDisconnect ()
    {
        $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );
        $link->getLink();

        $this->assertTrue( $link->isConnected() );

        $this->assertSame( $link, $link->disconnect() );

        $this->assertFalse( $link->isConnected() );
    }

    public function testGetIdentifier ()
    {
        $link = new \cPHP\DB\MySQLi\Link( $this->getURI() );

        $this->assertSame(
                "MySQLi://root@localhost:3306",
                $link->getIdentifier()
            );

        $link = new \cPHP\DB\MySQLi\Link;
        $link->clearHost();

        $this->assertRegExp(
                "/MySQLi:\/\/[0-9a-zA-Z]+/",
                $link->getIdentifier()
            );
    }

}

?>