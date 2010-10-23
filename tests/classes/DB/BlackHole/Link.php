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
class classes_DB_BlackHole_Link extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame( array(), $link->getQueue() );

        $adapter1 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $adapter2 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $link = new \r8\DB\BlackHole\Link( $adapter1, $adapter2 );
        $this->assertSame( array($adapter1, $adapter2), $link->getQueue() );
    }

    public function testAddResult ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame( array(), $link->getQueue() );

        $adapter1 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $this->assertSame( $link, $link->addResult( $adapter1 ) );
        $this->assertSame( array($adapter1), $link->getQueue() );

        $adapter2 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $this->assertSame( $link, $link->addResult( $adapter2 ) );
        $this->assertSame( array($adapter1, $adapter2), $link->getQueue() );
    }

    public function testConnect ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertNull( $link->connect() );
    }

    public function testEscape ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame( "\\'escape\\'", $link->escape("'escape'") );
    }

    public function testQuoteName ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame( "`I`", $link->quoteName("I") );
        $this->assertSame( "`JF`", $link->quoteName("JF") );
        $this->assertSame( "`Ident`", $link->quoteName("Ident") );
    }

    public function testQuery_SelectNoQueue ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $result = $link->query("SELECT * FROM table");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Read') );
        $this->assertSame( 0, $result->count() );
    }

    public function testQuery_SelectFromQueue ()
    {
        $link = new \r8\DB\BlackHole\Link;

        $adapter1 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $link->addResult( $adapter1 );

        $adapter2 = $this->getMock('\r8\iface\DB\Adapter\Result');
        $link->addResult( $adapter2 );

        $result1 = $link->query("SELECT * FROM table");
        $this->assertThat( $result1, $this->isInstanceOf('\r8\DB\Result\Read') );
        $this->assertSame( $adapter1, $result1->getAdapter() );

        $result2 = $link->query("SELECT * FROM table");
        $this->assertThat( $result2, $this->isInstanceOf('\r8\DB\Result\Read') );
        $this->assertSame( $adapter2, $result2->getAdapter() );

        $result3 = $link->query("SELECT * FROM table");
        $this->assertThat( $result3, $this->isInstanceOf('\r8\DB\Result\Read') );
        $this->assertNotSame( $adapter1, $result3->getAdapter() );
        $this->assertNotSame( $adapter2, $result3->getAdapter() );
        $this->assertSame( 0, $result3->count() );
    }

    public function testQuery_Insert ()
    {
        $link = new \r8\DB\BlackHole\Link;

        $result = $link->query("INSERT INTO table VALUES (NULL)");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 1, $result->getAffected() );
        $this->assertSame( 1, $result->getInsertID() );

        $result = $link->query("INSERT INTO table VALUES (NULL)");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 1, $result->getAffected() );
        $this->assertSame( 2, $result->getInsertID() );

        $result = $link->query("INSERT INTO table VALUES (NULL)");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 1, $result->getAffected() );
        $this->assertSame( 3, $result->getInsertID() );
    }

    public function testQuery_Update ()
    {
        $link = new \r8\DB\BlackHole\Link;

        $result = $link->query("UPDATE table SET field = NULL");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 0, $result->getAffected() );
        $this->assertNull( $result->getInsertID() );

        $result = $link->query("UPDATE table SET field = NULL");
        $this->assertThat( $result, $this->isInstanceOf('\r8\DB\Result\Write') );
        $this->assertSame( 0, $result->getAffected() );
        $this->assertNull( $result->getInsertID() );
    }

    public function testDisconnect ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertNull( $link->disconnect() );
    }

    public function testIsConnected ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertTrue( $link->isConnected() );
    }

    public function testGetExtension ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertNull( $link->getExtension() );
    }

    public function testGetIdentifier ()
    {
        $link = new \r8\DB\BlackHole\Link;
        $this->assertSame( "blackhole", $link->getIdentifier() );
    }

}

