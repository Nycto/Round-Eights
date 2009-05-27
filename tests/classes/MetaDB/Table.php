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
class classes_metadb_table extends PHPUnit_Framework_TestCase
{

    public function testConstruct ()
    {
        try {
            new \cPHP\MetaDB\Table("", "name");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        try {
            new \cPHP\MetaDB\Table("name", "");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        $tbl = new \cPHP\MetaDB\Table("dbName", "tblName");

        $this->assertSame( "dbName", $tbl->getDB() );
        $this->assertSame( "tblName", $tbl->getTable() );
    }

    public function testAddColumn ()
    {
        $tbl = new \cPHP\MetaDB\Table("dbName", "tblName");

        $this->assertSame( array(), $tbl->getColumns() );


        $fld1 = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->addColumn( $fld1 ) );

        $fld2 = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->addColumn( $fld2 ) );

        $this->assertSame( array( $fld1, $fld2 ), $tbl->getColumns() );


        $this->assertSame( $tbl, $tbl->addColumn( $fld1 ) );
        $this->assertSame( array( $fld1, $fld2 ), $tbl->getColumns() );
    }

    public function testPrimary_preRegistered ()
    {
        $tbl = new \cPHP\MetaDB\Table("dbName", "tblName");
        $this->assertNull( $tbl->getPrimary() );

        $primary = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->addColumn( $primary ) );
        $this->assertSame( array( $primary ), $tbl->getColumns() );

        $this->assertSame( $tbl, $tbl->setPrimary( $primary ) );
        $this->assertSame( $primary, $tbl->getPrimary() );
        $this->assertSame( array( $primary ), $tbl->getColumns() );
    }

    public function testPrimary_register ()
    {
        $tbl = new \cPHP\MetaDB\Table("dbName", "tblName");
        $this->assertNull( $tbl->getPrimary() );


        // Add other fields to the table
        $fld1 = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->addColumn( $fld1 ) );

        $fld2 = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->addColumn( $fld2 ) );

        $this->assertSame( array( $fld1, $fld2 ), $tbl->getColumns() );


        // Now add the primary key
        $primary = $this->getMock('cPHP\iface\MetaDB\Column');
        $this->assertSame( $tbl, $tbl->setPrimary( $primary ) );
        $this->assertSame( $primary, $tbl->getPrimary() );
        $this->assertSame( array( $primary, $fld1, $fld2 ), $tbl->getColumns() );
    }

}

?>