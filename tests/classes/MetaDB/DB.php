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
class classes_metadb_db extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a MetaDB\Set object for testing
     *
     * @return \h2o\MetaDB\Set
     */
    public function getTestSet ()
    {
        return new \h2o\MetaDB\Set( new \h2o\DB\BlackHole\Link );
    }

    /**
     * Returns a Mock database table object
     *
     * @return \h2o\MetaDB\Table
     */
    public function getTestTable ( \h2o\MetaDB\DB $db, $name )
    {
        $table = $this->getMock(
        	'h2o\MetaDB\Table',
            array(),
            array($db, $name),
            '',
            FALSE
        );

        $table->expects( $this->any() )
            ->method('getName')
            ->will( $this->returnValue($name) );

        return $table;
    }

    public function testConstruct ()
    {
        try {
            new \h2o\MetaDB\DB( $this->getTestSet(), "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );

        $this->assertSame( "dbName", $db->getName() );
    }

    public function testAddTable ()
    {
        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );
        $this->assertSame( array(), $db->getTables() );


        $tbl1 = $this->getTestTable($db, "tbl1");
        $this->assertSame( $db, $db->addTable( $tbl1 ) );

        $tbl2 = $this->getTestTable($db, "tbl2");
        $this->assertSame( $db, $db->addTable( $tbl2 ) );

        $this->assertSame(
                array( "tbl1" => $tbl1, "tbl2" => $tbl2 ),
                $db->getTables()
            );


        $this->assertSame( $db, $db->addTable( $tbl1 ) );
        $this->assertSame(
                array( "tbl1" => $tbl1, "tbl2" => $tbl2 ),
                $db->getTables()
            );
    }

    public function testAddTable_conflict ()
    {
        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );

        $tbl1 = $this->getTestTable($db, "name");
        $db->addTable( $tbl1 );


        $tbl2 = $this->getTestTable($db, "name");

        try {
            $db->addTable( $tbl2 );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "A table with that name already exists", $err->getMessage() );
        }
    }

    public function testGetTable ()
    {
        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );

        $tbl1 = $this->getTestTable($db, "users");
        $tbl2 = $this->getTestTable($db, "articles");

        $db->addTable( $tbl1 );
        $db->addTable( $tbl2 );


        $this->assertSame( $tbl1, $db->getTable( "users" ) );
        $this->assertSame( $tbl2, $db->getTable( "articles" ) );
        $this->assertNull( $db->getTable( "not a table" ) );
        $this->assertNull( $db->getTable( "USERS" ) );
    }

    public function testGet ()
    {
        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );

        // Add two Tables
        $tbl1 = $this->getTestTable($db, "users");
        $tbl2 = $this->getTestTable($db, "articles");
        $db->addTable( $tbl1 );
        $db->addTable( $tbl2 );


        $this->assertSame( $tbl1, $db->users );
        $this->assertSame( $tbl2, $db->articles );

        try {
            $db->NotATable;
            $this->fail("An expected exception was not thrown");
        }
        catch ( \h2o\Exception\Variable $err ) {
            $this->assertSame( "Table does not exist", $err->getMessage() );
        }
    }

    public function testIsset ()
    {
        $db = new \h2o\MetaDB\DB( $this->getTestSet(), "dbName" );

        // Add two Tables
        $tbl1 = $this->getTestTable($db, "users");
        $tbl2 = $this->getTestTable($db, "articles");
        $db->addTable( $tbl1 );
        $db->addTable( $tbl2 );

        $this->assertTrue( isset($db->users) );
        $this->assertTrue( isset($db->articles) );
        $this->assertFalse( isset($db->notATable) );
    }

    public function testSelect ()
    {
        $query = new \h2o\Query\Select;
        $builder = $this->getMock('\h2o\iface\MetaDB\RowBuilder');

        $result = new \h2o\MetaDB\Result(
            $this->getMock('h2o\iface\DB\Result\Read'),
            $builder
        );

        $set = $this->getMock(
        	'h2o\MetaDB\Set',
            array(),
            array( new \h2o\DB\BlackHole\Link )
        );

        $set->expects( $this->once() )
            ->method( "select" )
            ->with( $builder, $query )
            ->will( $this->returnValue($result) );

        $db = new \h2o\MetaDB\DB( $set, "dbName" );

        $this->assertSame( $result, $db->select( $builder, $query ) );
    }

}

?>