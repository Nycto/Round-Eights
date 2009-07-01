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
class classes_metadb_tableset extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test table
     *
     * @return \h2o\MetaDB\Table
     */
    public function getTestTable ( \h2o\MetaDB\TableSet $set, $name = "tblName" )
    {
        return $this->getMock(
                'h2o\MetaDB\Table',
                array( "_mock" ),
                array( $set, "dbName", $name )
            );
    }

    public function testAddTable ()
    {
        $set = new \h2o\MetaDB\TableSet;

        $this->assertSame( array(), $set->getTables() );

        // Add the first table
        $tbl1 = $this->getTestTable( $set );
        $this->assertSame( $set, $set->addTable( $tbl1 ) );
        $this->assertSame(
                array( "dbName" => array( "tblName" => $tbl1 ) ),
                $set->getTables()
            );

        // Add another table
        $tbl2 = $this->getTestTable( $set, "other" );
        $this->assertSame( $set, $set->addTable( $tbl2 ) );
        $this->assertSame(
                array( "dbName" => array( "tblName" => $tbl1, "other" => $tbl2 ) ),
                $set->getTables()
            );

        // Try re-adding the same table
        $this->assertSame( $set, $set->addTable( $tbl1 ) );
        $this->assertSame(
                array( "dbName" => array( "tblName" => $tbl1, "other" => $tbl2 ) ),
                $set->getTables()
            );

        // Now try adding a conflicting table
        try {
            $set->addTable( $this->getTestTable( $set ) );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "A table with that name already exists", $err->getMessage() );
        }

    }

    public function testFindTable ()
    {
        $set = new \h2o\MetaDB\TableSet;

        // Add two tables
        $tbl1 = $this->getTestTable( $set );
        $this->assertSame( $set, $set->addTable( $tbl1 ) );

        $tbl2 = $this->getTestTable( $set, "other" );
        $this->assertSame( $set, $set->addTable( $tbl2 ) );


        $this->assertNull( $set->findTable( "notADB", "tble" ) );
        $this->assertNull( $set->findTable( "dbName", "notATable" ) );

        $this->assertSame( $tbl1, $set->findTable( "dbName", "tblName" ) );
        $this->assertSame( $tbl2, $set->findTable( "dbName", "other" ) );
    }

}

?>