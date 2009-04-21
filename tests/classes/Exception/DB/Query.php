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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_exception_db_query extends PHPUnit_Framework_TestCase
{

    public function testConstruct_link ()
    {
        $link = $this->getMock(
                "\cPHP\DB\Link",
                array("rawConnect", "rawDisconnect", "rawEscape", "rawQuery", "rawIsConnected", "getIdentifier")
            );

        $link->expects( $this->once() )
            ->method("getIdentifier")
            ->will( $this->returnValue("db://ident") );


        $err = new \cPHP\Exception\DB\Query(
                'SELECT * FROM table FOR UPDATE',
                'Table does not exist',
                550,
                $link,
                0
            );

        $this->assertEquals( "Table does not exist", $err->getMessage() );
        $this->assertEquals( 550, $err->getCode() );
        $this->assertEquals( 0, $err->getFaultOffset() );

        $this->assertEquals(
                array(
                        "Link" => "db://ident",
                        "Query" => "SELECT * FROM table FOR UPDATE"
                    ),
                $err->getData()
            );
    }

}

?>