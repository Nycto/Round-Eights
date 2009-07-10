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
class classes_metadb_table extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test column with the given name
     *
     * @return \h2o\iface\MetaDB\Column
     */
    public function getTestColumn ( $name )
    {
        $fld = $this->getMock("h2o\iface\MetaDB\Column");
        $fld->expects( $this->any() )
            ->method('getName')
            ->will( $this->returnValue( $name ) );

        return $fld;
    }

    /**
     * Returns a test table set
     *
     * @return \h2o\MetaDB\Set
     */
    public function getTestDB ()
    {
        return new \h2o\MetaDB\DB( new \h2o\MetaDB\Set, "dbName" );
    }

    /**
     * Returns a test table
     *
     * @return \h2o\MetaDB\Table
     */
    public function getTestTable ()
    {
        return new \h2o\MetaDB\Table(
                $this->getTestDB(),
                "tblName"
            );
    }

    public function testConstruct ()
    {
        try {
            new \h2o\MetaDB\Table( $this->getTestDB(), "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        $tbl = new \h2o\MetaDB\Table(
                $this->getTestDB(),
                "tblName"
            );

        $this->assertSame( "tblName", $tbl->getName() );
    }

    public function testAddColumn ()
    {
        $tbl = $this->getTestTable();
        $this->assertSame( array(), $tbl->getColumns() );


        $fld1 = $this->getTestColumn("fld1");
        $this->assertSame( $tbl, $tbl->addColumn( $fld1 ) );

        $fld2 = $this->getTestColumn("fld2");
        $this->assertSame( $tbl, $tbl->addColumn( $fld2 ) );

        $this->assertSame(
                array( "fld1" => $fld1, "fld2" => $fld2 ),
                $tbl->getColumns()
            );


        // Add a column that already exists
        $this->assertSame( $tbl, $tbl->addColumn( $fld1 ) );
        $this->assertSame(
                array( "fld1" => $fld1, "fld2" => $fld2 ),
                $tbl->getColumns()
            );
    }

    public function testAddColumn_conflict ()
    {
        $tbl = $this->getTestTable();

        $fld1 = $this->getTestColumn("name");
        $tbl->addColumn( $fld1 );

        $fld2 = $this->getTestColumn("name");

        try {
            $tbl->addColumn( $fld2 );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "A column with that name already exists", $err->getMessage() );
        }
    }

    public function testGetColumn ()
    {
        $tbl = $this->getTestTable();

        $fld1 = $this->getTestColumn("userID");
        $fld2 = $this->getTestColumn("email");

        $tbl->addColumn( $fld1 );
        $tbl->addColumn( $fld2 );


        $this->assertSame( $fld1, $tbl->getColumn( "userID" ) );
        $this->assertSame( $fld2, $tbl->getColumn( "email" ) );
        $this->assertNull( $tbl->getColumn( "not a field" ) );
        $this->assertNull( $tbl->getColumn( "EMAIL" ) );
    }

    public function testPrimary_preRegistered ()
    {
        $tbl = $this->getTestTable();

        $this->assertNull( $tbl->getPrimary() );

        $primary = $this->getTestColumn("primary");
        $this->assertSame( $tbl, $tbl->addColumn( $primary ) );
        $this->assertSame( array( "primary" => $primary ), $tbl->getColumns() );

        $this->assertSame( $tbl, $tbl->setPrimary( $primary ) );
        $this->assertSame( $primary, $tbl->getPrimary() );
        $this->assertSame( array( "primary" => $primary ), $tbl->getColumns() );
    }

    public function testPrimary_register ()
    {
        $tbl = $this->getTestTable();

        $this->assertNull( $tbl->getPrimary() );


        // Add other fields to the table
        $fld1 = $this->getTestColumn("fld1");
        $tbl->addColumn( $fld1 );

        $fld2 = $this->getTestColumn("fld2");
        $tbl->addColumn( $fld2 );

        $this->assertSame(
            array( "fld1" => $fld1, "fld2" => $fld2 ),
            $tbl->getColumns()
        );


        // Now add the primary key
        $primary = $this->getTestColumn("primary");
        $this->assertSame( $tbl, $tbl->setPrimary( $primary ) );
        $this->assertSame( $primary, $tbl->getPrimary() );
        $this->assertSame(
                array( "primary" => $primary, "fld1" => $fld1, "fld2" => $fld2 ),
                $tbl->getColumns()
            );
    }

}

?>