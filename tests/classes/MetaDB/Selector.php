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
class classes_metadb_selector extends PHPUnit_Framework_TestCase
{

    public function testToSQL_simple ()
    {
        $from = $this->getMock( "cPHP\iface\MetaDB\Selectable" );

        $from->expects( $this->once() )
            ->method( "getSQLFields" )
            ->will( $this->returnValue(array()) );

        $from->expects( $this->once() )
            ->method( "getFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \cPHP\MetaDB\Selector( $from );

        $this->assertSame(
        		"SELECT *\n"
                ."FROM `table`",
                $select->toSQL()
            );
    }

    public function testToSQL_withFieldList ()
    {
        $from = $this->getMock( "cPHP\iface\MetaDB\Selectable" );

        $from->expects( $this->once() )
            ->method( "getSQLFields" )
            ->will( $this->returnValue(array(
                    "field1", null, "  ", "fld2"
                )) );

        $from->expects( $this->once() )
            ->method( "getFromSQL" )
            ->will( $this->returnValue("`table`") );

        $select = new \cPHP\MetaDB\Selector( $from );

        $this->assertSame(
        		"SELECT field1, fld2\n"
                ."FROM `table`",
                $select->toSQL()
            );
    }

}

?>