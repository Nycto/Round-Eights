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
class classes_metadb_set extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test database
     *
     * @return \r8\MetaDB\DB
     */
    public function getTestDB ( \r8\MetaDB\Set $set, $name )
    {
        return $this->getMock(
                'r8\MetaDB\DB',
                array( "_mock" ),
                array( $set, $name )
            );
    }

    public function testAddDB ()
    {
        $set = new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );

        $this->assertSame( array(), $set->getDBs() );

        // Add the first database
        $db1 = $this->getTestDB( $set, "dbName" );
        $this->assertSame( $set, $set->addDB( $db1 ) );
        $this->assertSame(
                array( "dbName" => $db1 ),
                $set->getDBs()
            );

        // Add another database
        $db2 = $this->getTestDB( $set, "other" );
        $this->assertSame( $set, $set->addDB( $db2 ) );
        $this->assertSame(
                array( "dbName" => $db1, "other" => $db2 ),
                $set->getDBs()
            );

        // Try re-adding the same database
        $this->assertSame( $set, $set->addDB( $db1 ) );
        $this->assertSame(
                array( "dbName" => $db1, "other" => $db2 ),
                $set->getDBs()
            );

        // Now try adding a conflicting database
        try {
            $set->addDB( $this->getTestDB( $set, "dbName" ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "A database with that name already exists", $err->getMessage() );
        }

    }

    public function testGetDB ()
    {
        $set = new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );

        // Add two databases
        $db1 = $this->getTestDB( $set, "dbName" );
        $this->assertSame( $set, $set->addDB( $db1 ) );

        $db2 = $this->getTestDB( $set, "other" );
        $this->assertSame( $set, $set->addDB( $db2 ) );

        // Now search for a non-existant database
        $this->assertNull( $set->getDB( "notADB" ) );

        // Search for existing databases
        $this->assertSame( $db1, $set->getDB( "dbName" ) );
        $this->assertSame( $db2, $set->getDB( "other" ) );
    }

    public function testGet ()
    {
        $set = new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );

        // Add two databases
        $db1 = $this->getTestDB( $set, "dbName" );
        $this->assertSame( $set, $set->addDB( $db1 ) );

        $db2 = $this->getTestDB( $set, "other" );
        $this->assertSame( $set, $set->addDB( $db2 ) );

        $this->assertSame( $db1, $set->dbName );
        $this->assertSame( $db2, $set->other );

        try {
            $set->NotADB;
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( "Database does not exist", $err->getMessage() );
        }
    }

    public function testIsset ()
    {
        $set = new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );

        // Add two databases
        $db1 = $this->getTestDB( $set, "dbName" );
        $this->assertSame( $set, $set->addDB( $db1 ) );

        $db2 = $this->getTestDB( $set, "other" );
        $this->assertSame( $set, $set->addDB( $db2 ) );

        $this->assertTrue( isset($set->dbName) );
        $this->assertTrue( isset($set->other) );
        $this->assertFalse( isset($set->notADB) );
    }

    public function testSelect ()
    {
        $query = new \r8\Query\Select;
        $builder = $this->getMock('\r8\iface\MetaDB\RowBuilder');
        $link = new \r8\DB\BlackHole\Link;

        $selector = new \r8\MetaDB\Set( $link );

        $result = $selector->select( $builder, $query );

        $this->assertThat( $result, $this->isInstanceOf("r8\MetaDB\Result") );

        $this->assertSame( $builder, $result->getRowBuilder() );

        $decorated = $result->getDecorated();
        $this->assertThat( $decorated, $this->isInstanceOf("r8\DB\BlackHole\Read") );
        $this->assertSame( "SELECT *", $decorated->getQuery() );
    }

}

?>