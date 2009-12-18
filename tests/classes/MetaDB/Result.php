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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_metadb_result extends PHPUnit_Framework_TestCase
{

    public function testGetRowBuilder ()
    {
        $read = $this->getMock('\r8\iface\DB\Result\Read');
        $builder = $this->getMock('\r8\iface\MetaDB\RowBuilder');

        $result = new \r8\MetaDB\Result( $read, $builder );

        $this->assertSame( $builder, $result->getRowBuilder() );
    }

    public function testCurrent ()
    {
        // Create a database result object which returns a row as an array
        $read = $this->getMock('\r8\iface\DB\Result\Read');
        $read->expects( $this->once() )
            ->method('current')
            ->will( $this->returnValue( array("Result") ) );

        // Mock a database row which will be returned
        $row = $this->getMock('\r8\iface\MetaDB\Row');

        // Set up the builder to expect the array and return the row
        $builder = $this->getMock('\r8\iface\MetaDB\RowBuilder');
        $builder->expects( $this->once() )
            ->method( "fromArray" )
            ->with( array("Result") )
            ->will( $this->returnValue($row) );

        // Throw it all into to MetaDB result object
        $result = new \r8\MetaDB\Result( $read, $builder );

        $this->assertSame( $row, $result->current() );
    }

}

?>