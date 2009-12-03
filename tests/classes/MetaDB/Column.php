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
class classes_metadb_column extends PHPUnit_Framework_TestCase
{

    /**
     * A common Set to use when generating test column
     *
     * @var \r8\MetaDB\Set
     */
    private $set;

    /**
     * A common Database to use when generating test column
     *
     * @var \r8\MetaDB\DB
     */
    private $db;

    /**
     * A common table to use when generating test columns
     *
     * @var \r8\MetaDB\Column
     */
    private $table;

    /**
     * Sets up the test space
     *
     * @return null
     */
    public function setUp ()
    {
        $this->set = new \r8\MetaDB\Set( new \r8\DB\BlackHole\Link );
        $this->db = new \r8\MetaDB\DB( $this->set, "dbName" );
        $this->table = new \r8\MetaDB\Table( $this->db, "tblName" );
    }

    /**
     * Returns a test column
     *
     * @param String $name The name of the column
     * @return \r8\MetaDB\Column
     */
    public function getTestColumn ( $name )
    {
        return $this->getMock(
                '\r8\MetaDB\Column',
                array( "filterSelected" ),
                array( $this->table, $name )
            );
    }

    public function testConstruction ()
    {
        $col = $this->getTestColumn( "col" );

        $this->assertSame( "col", $col->getName() );
        $this->assertSame( $this->table, $col->getTable() );
    }

    public function testToSelectSQL ()
    {
        $col = $this->getTestColumn( "col" );

        $link = new \r8\DB\BlackHole\Link;

        $this->assertSame(
        	"dbName.tblName.col",
            $col->toSelectSQL( $link )
        );
    }

}

?>