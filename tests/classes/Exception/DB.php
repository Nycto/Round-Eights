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
class classes_exception_db extends PHPUnit_Framework_TestCase
{

    public function testConstruct_link ()
    {
        $link = $this->getMock(
                "\cPHP\DB\Link",
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected", "getIdentifier")
            );

        $link->expects( $this->once() )
            ->method("getIdentifier")
            ->will( $this->returnValue("db://ident") );


        $err = new \cPHP\Exception\DB(
                'Oops, an error was encountered',
                404,
                $link,
                0
            );

        $this->assertEquals( "Oops, an error was encountered", $err->getMessage() );
        $this->assertEquals( 404, $err->getCode() );
        $this->assertEquals( 0, $err->getFaultOffset() );

        $this->assertEquals(
                array("Link" => "db://ident"),
                $err->getData()
            );
    }

    public function testConstruct_linkwrap ()
    {
        $link = $this->getMock(
                "\cPHP\DB\Link",
                array("rawConnect", "rawDisconnect", "escapeString", "rawQuery", "rawIsConnected", "getIdentifier")
            );

        $link->expects( $this->once() )
            ->method("getIdentifier")
            ->will( $this->returnValue("db://ident") );


        $wrap = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );


        $err = new \cPHP\Exception\DB(
                'Oops, an error was encountered',
                404,
                $wrap,
                0
            );

        $this->assertEquals( "Oops, an error was encountered", $err->getMessage() );
        $this->assertEquals( 404, $err->getCode() );
        $this->assertEquals( 0, $err->getFaultOffset() );

        $this->assertEquals(
                array("Link" => "db://ident"),
                $err->getData()
            );
    }

    public function testConstruct_other ()
    {
        $err = new \cPHP\Exception\DB(
                'Oops, an error was encountered',
                404,
                "ident",
                0
            );

        $this->assertEquals( "Oops, an error was encountered", $err->getMessage() );
        $this->assertEquals( 404, $err->getCode() );
        $this->assertEquals( 0, $err->getFaultOffset() );

        $this->assertEquals(
                array("Link" => "string('ident')"),
                $err->getData()
            );
    }

}

?>