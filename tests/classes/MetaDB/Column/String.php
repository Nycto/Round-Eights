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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
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
     * @return \r8\MetaDB\Table
     */
    public function getTestTable ()
    {
        return new \r8\MetaDB\Table(
            new \r8\MetaDB\DB(
                new \r8\MetaDB\Set(
                    new \r8\DB\BlackHole\Link
                ),
                "dbName"
            ),
            "tblName"
        );
    }

    public function testFilterSelected ()
    {
        $col = new \r8\MetaDB\Column\String( $this->getTestTable(), "colName" );

        $this->assertSame( " input ", $col->filterSelected( " input " ) );
        $this->assertSame( "50", $col->filterSelected( 50 ) );
        $this->assertSame( "3.14", $col->filterSelected( 3.14 ) );
    }

}

?>