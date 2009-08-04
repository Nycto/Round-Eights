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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_metadb_column_string extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test table
     *
     * @return \h2o\MetaDB\Table
     */
    public function getTestTable ()
    {
        return new \h2o\MetaDB\Table(
            new \h2o\MetaDB\DB(
                new \h2o\MetaDB\Set(
                    new \h2o\DB\BlackHole\Link
                ),
                "dbName"
            ),
            "tblName"
        );
    }

    public function testFilterSelected ()
    {
        $col = new \h2o\MetaDB\Column\String( $this->getTestTable(), "colName" );

        $this->assertSame( " input ", $col->filterSelected( " input " ) );
        $this->assertSame( "50", $col->filterSelected( 50 ) );
        $this->assertSame( "3.14", $col->filterSelected( 3.14 ) );
    }

}

?>