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
class classes_db_linkwrap extends PHPUnit_Framework_TestCase
{

    public function testGetLink ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $this->assertSame( $link, $mock->getLink() );
    }

    public function testGetTopLink_shallow ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $this->assertSame( $link, $mock->getTopLink() );
    }

    public function testGetTopLink_deep ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock1 = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $mock2 = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $mock1 )
            );

        $this->assertSame( $link, $mock2->getTopLink() );
    }

    public function testQuery ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $link->expects( $this->once() )
            ->method( "query" )
            ->with( "SELECT * FROM table" )
            ->will( $this->returnValue("result") );

        $this->assertSame( "result", $mock->query("SELECT * FROM table") );
    }

    public function testQuote ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("quoted") );

        $this->assertSame( "quoted", $mock->quote("raw value") );

        $link->expects( $this->at(0) )
            ->method( "quote" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("quoted") );

        $this->assertSame( "quoted", $mock->quote("raw value", FALSE) );
    }

    public function testEscape()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(TRUE) )
            ->will( $this->returnValue("escaped") );

        $this->assertSame( "escaped", $mock->escape("raw value") );

        $link->expects( $this->at(0) )
            ->method( "escape" )
            ->with( $this->equalTo("raw value"), $this->equalTo(FALSE) )
            ->will( $this->returnValue("escaped") );

        $this->assertSame( "escaped", $mock->escape("raw value", FALSE) );
    }

    public function testEscapeString ()
    {
        $link = $this->getMock( "\cPHP\iface\DB\Link" );

        $link->expects( $this->once() )
            ->method( "escapeString" )
            ->with( $this->equalTo("raw value") )
            ->will( $this->returnValue("escaped") );

        $mock = $this->getMock(
                "\cPHP\DB\LinkWrap",
                array("_mock"),
                array( $link )
            );

        $this->assertSame( "escaped", $mock->escapeString("raw value") );
    }

}

?>