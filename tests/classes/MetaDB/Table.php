<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
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
            ->method('getName', 'toSelectSQL')
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
        $set = new \h2o\MetaDB\Set( new \h2o\DB\BlackHole\Link );
        return new \h2o\MetaDB\DB( $set, "dbName" );
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

    public function testRowBuilder ()
    {
        $tbl = new \h2o\MetaDB\Table( $this->getTestDB(), "tblName" );

        $this->assertThat(
            $tbl->getRowBuilder(),
            $this->isInstanceOf("h2o\MetaDB\RowBuilder\Generic")
        );

        $builder = $this->getMock('h2o\iface\MetaDB\RowBuilder');
        $this->assertSame( $tbl, $tbl->setRowBuilder($builder) );
        $this->assertSame( $builder, $tbl->getRowBuilder() );
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

    public function testGet ()
    {
        $tbl = $this->getTestTable();

        // Add two columns
        $fld1 = $this->getTestColumn("userID");
        $fld2 = $this->getTestColumn("email");
        $tbl->addColumn( $fld1 );
        $tbl->addColumn( $fld2 );


        $this->assertSame( $fld1, $tbl->userID );
        $this->assertSame( $fld2, $tbl->email );

        try {
            $tbl->NotAColumn;
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Variable $err ) {
            $this->assertSame( "Column does not exist", $err->getMessage() );
        }
    }

    public function testIsset ()
    {
        $tbl = $this->getTestTable();

        // Add two columns
        $fld1 = $this->getTestColumn("userID");
        $fld2 = $this->getTestColumn("email");
        $tbl->addColumn( $fld1 );
        $tbl->addColumn( $fld2 );

        $this->assertTrue( isset($tbl->userID) );
        $this->assertTrue( isset($tbl->email) );
        $this->assertFalse( isset($tbl->notAColumn) );
    }

    public function testToFromSQL ()
    {
        $table = $this->getTestTable();
        $link = new \h2o\DB\BlackHole\Link;

        $this->assertSame( "dbName.tblName", $table->toFromSQL( $link ) );
    }

    public function testSelect ()
    {
        $query = new \h2o\Query\Select;
        $builder = $this->getMock('h2o\iface\MetaDB\RowBuilder');
        $response = $this->getMock('h2o\MetaDB\Result', array(), array(), '', FALSE );

        $db = $this->getMock( 'h2o\MetaDB\DB', array(), array(), '', FALSE );
        $db->expects( $this->once() )
            ->method('select')
            ->with( $builder, $query )
            ->will( $this->returnValue($response) );

        $table = new \h2o\MetaDB\Table( $db, "tblName" );
        $table->setRowBuilder( $builder );

        $this->assertSame( $response, $table->select( $query ) );
    }

    public function testWhere ()
    {
        $builder = $this->getMock('h2o\iface\MetaDB\RowBuilder');
        $response = $this->getMock('h2o\MetaDB\Result', array(), array(), '', FALSE );

        $db = $this->getMock( 'h2o\MetaDB\DB', array(), array(), '', FALSE );

        $table = new \h2o\MetaDB\Table( $db, "tblName" );
        $table->setRowBuilder( $builder );

        $where = new \h2o\Query\Where\Raw("");

        $db->expects( $this->once() )
            ->method('select')
            ->with(
                $this->equalTo( $builder ),
                $this->isInstanceOf("h2o\Query\Select")
            )
            ->will( $this->returnValue($response) );

        $this->assertSame( $response, $table->where( $where ) );
    }

}

?>