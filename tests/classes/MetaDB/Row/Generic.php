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
class classes_metadb_row_generic extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a test column
     *
     * @return \h2o\iface\MetaDB\Column
     */
    public function getTestColumn ( $name )
    {
        $col = $this->getMock('\h2o\iface\MetaDB\Column');
        $col->expects( $this->any() )
            ->method( "getName" )
            ->will( $this->returnValue($name) );
        return $col;
    }

    public function testConstruct ()
    {
        $col1 = $this->getTestColumn( "uname" );
        $col1->expects( $this->once() )
            ->method( "filterSelected" )
            ->with( $this->equalTo("jack") )
            ->will( $this->returnValue("JACK") );

        $col2 = $this->getTestColumn( "pword" );
        $col2->expects( $this->once() )
            ->method( "filterSelected" )
            ->with( $this->equalTo("abc123") )
            ->will( $this->returnValue("ABC123") );

        $row = new \h2o\MetaDB\Row\Generic(
            array( "uname" => "jack", "noise", "pword" => "abc123" ),
            array( $col1, $col2, "snow" )
        );

        $this->assertSame(
                array("uname" => $col1, "pword" => $col2),
                $row->getColumns()
            );

        $this->assertSame(
                array( "uname" => "JACK", "pword" => "ABC123" ),
                $row->getValues()
            );
    }

}

?>