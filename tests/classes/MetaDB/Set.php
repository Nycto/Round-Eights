<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
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
     * @return \h2o\MetaDB\DB
     */
    public function getTestDB ( \h2o\MetaDB\Set $set, $name )
    {
        return $this->getMock(
                'h2o\MetaDB\DB',
                array( "_mock" ),
                array( $set, $name )
            );
    }

    public function testAddDB ()
    {
        $set = new \h2o\MetaDB\Set;

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
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "A database with that name already exists", $err->getMessage() );
        }

    }

    public function testGetDB ()
    {
        $set = new \h2o\MetaDB\Set;

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

}

?>