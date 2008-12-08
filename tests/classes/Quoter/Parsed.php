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
class classes_quoter_parsed extends PHPUnit_Framework_TestCase
{

    public function testAddSection ()
    {
        $mock = $this->getMock("cPHP\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $list = new \cPHP\Quoter\Parsed;

        $this->assertSame( $list, $list->addSection($mock) );

        $sections = $list->getSections();

        $this->assertThat( $sections, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array( $mock ), $sections->get() );
    }

    public function testToString ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $list->addSection( new \cPHP\Quoter\Section\Unquoted("snippet") );
        $list->addSection( new \cPHP\Quoter\Section\Quoted("inQuotes", '(', ')') );

        $this->assertSame( "snippet(inQuotes)", $list->__toString() );
        $this->assertSame( "snippet(inQuotes)", "$list" );
    }

    public function testSetIncludeQuoted ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( FALSE ) );

        $this->assertFalse( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( TRUE ) );

        $this->assertTrue( $list->getIncludeQuoted() );
    }

    public function testSetIncludeUnquoted ()
    {
        $list = new \cPHP\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( FALSE ) );

        $this->assertFalse( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( TRUE ) );

        $this->assertTrue( $list->getIncludeUnquoted() );
    }

    public function testExplode_all ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "with", "gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "'with", "some'", "gaps"), $result->get() );

        $result = $list->parse( "gg" )->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("", "", ""), $result->get() );
    }

    public function testExplode_noQuoted ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "with", "gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String", "'with some'", "gaps"), $result->get() );

        $result = $list->parse( "gg" )
            ->setIncludeQuoted( FALSE )
            ->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("", "", ""), $result->get() );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with a few''quoted gaps'"), $result->get() );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with a few'", "'quoted gaps'"), $result->get() );
    }

    public function testExplode_noUnquoted ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String 'with", "some' gaps"), $result->get() );

        $result = $list->parse( "gg" )
            ->setIncludeUnquoted( FALSE )
            ->explode("g");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("gg"), $result->get() );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with", "a", "few''quoted", "gaps'"), $result->get() );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("'with", "a", "few' 'quoted", "gaps'"), $result->get() );
    }

    public function testExplode_none ()
    {
        $list = new \cPHP\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String with gaps"), $result->get() );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Ary") );
        $this->assertSame( array("String 'with some' gaps"), $result->get() );
    }

    public function testFilter ()
    {
        $list = new \cPHP\Quoter;
        $parsed = $list->parse("string 'with' quotes")
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'WITH' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeQuoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'with' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'WITH' quotes", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->setIncludeQuoted(FALSE)
            ->filter( new \cPHP\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'with' quotes", $parsed );
    }
}

?>