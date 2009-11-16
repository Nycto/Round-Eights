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
     * @return \r8\MetaDB\Set
     */
    public function getTestSet ()
    {
        return new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );
    }

    /**
     * Returns a Mock database table object
     *
     * @return \r8\MetaDB\Table
     */
    public function getTestTable ( \r8\MetaDB\DB $db, $name )
    {
        $table = $this->getMock(
        	'r8\MetaDB\Table',
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
            new \r8\MetaDB\DB( $this->getTestSet(), "" );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }

        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );

        $this->assertSame( "dbName", $db->getName() );
    }

    public function testAddTable ()
    {
        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );
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
        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );

        $tbl1 = $this->getTestTable($db, "name");
        $db->addTable( $tbl1 );


        $tbl2 = $this->getTestTable($db, "name");

        try {
            $db->addTable( $tbl2 );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "A table with that name already exists", $err->getMessage() );
        }
    }

    public function testGetTable ()
    {
        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );

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
        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );

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
        catch ( \r8\Exception\Variable $err ) {
            $this->assertSame( "Table does not exist", $err->getMessage() );
        }
    }

    public function testIsset ()
    {
        $db = new \r8\MetaDB\DB( $this->getTestSet(), "dbName" );

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
        $query = new \r8\Query\Select;
        $builder = $this->getMock('\r8\iface\MetaDB\RowBuilder');

        $result = new \r8\MetaDB\Result(
            $this->getMock('r8\iface\DB\Result\Read'),
            $builder
        );

        $set = $this->getMock(
        	'r8\MetaDB\Set',
            array(),
            array( new \r8\DB\BlackHole\Link )
        );

        $set->expects( $this->once() )
            ->method( "select" )
            ->with( $builder, $query )
            ->will( $this->returnValue($result) );

        $db = new \r8\MetaDB\DB( $set, "dbName" );

        $this->assertSame( $result, $db->select( $builder, $query ) );
    }

}

?>