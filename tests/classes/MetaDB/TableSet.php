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
class classes_metadb_tableset extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test table
     *
     * @return \cPHP\MetaDB\Table
     */
    public function getTestTable ( \cPHP\MetaDB\TableSet $set, $name = "tblName" )
    {
        return $this->getMock(
                'cPHP\MetaDB\Table',
                array( "_mock" ),
                array( $set, "dbName", $name )
            );
    }

    public function testAddTable ()
    {
        $set = new \cPHP\MetaDB\TableSet;

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
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "A table with that name already exists", $err->getMessage() );
        }

    }

    public function testFindTable ()
    {
        $set = new \cPHP\MetaDB\TableSet;

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