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
class classes_metadb_column extends PHPUnit_Framework_TestCase
{

    /**
     * A common TableSet to use when generating test column
     *
     * @var \cPHP\MetaDB\TableSet
     */
    private $tableSet;

    /**
     * A common table to use when generating test columns
     *
     * @var \cPHP\MetaDB\Column
     */
    private $table;

    /**
     * Sets up the test space
     *
     * @return null
     */
    public function setUp ()
    {
        $this->tableSet = $this->getMock(
        		"cPHP\MetaDB\TableSet",
                array("_mock")
            );

        $this->table = $this->getMock(
                'cPHP\MetaDB\Table',
                array( "_mock" ),
                array( $this->tableSet, "dbName", "tblName" )
            );
    }

    /**
     * Returns a test column
     *
     * @param String $name The name of the column
     * @return \cPHP\MetaDB\Column
     */
    public function getTestColumn ( $name )
    {
        return $this->getMock(
                "\cPHP\MetaDB\Column",
                array( "getSelectSQL", "getInsertSQL", "getUpdateSQL" ),
                array( $this->table, $name )
            );
    }

    public function testConstruction ()
    {
        $col = $this->getTestColumn( "col" );

        $this->assertSame( "col", $col->getName() );
        $this->assertSame( $this->table, $col->getTable() );
    }

}

?>