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
        $mock = $this->getMock("r8\Quoter\Section", array("isQuoted", "__toString"), array(0, null));

        $list = new \r8\Quoter\Parsed;

        $this->assertSame( $list, $list->addSection($mock) );

        $sections = $list->getSections();

        $this->assertSame( array( $mock ), $sections );
    }

    public function testToString ()
    {
        $list = new \r8\Quoter\Parsed;

        $list->addSection( new \r8\Quoter\Section\Unquoted("snippet") );
        $list->addSection( new \r8\Quoter\Section\Quoted("inQuotes", '(', ')') );

        $this->assertSame( "snippet(inQuotes)", $list->__toString() );
        $this->assertSame( "snippet(inQuotes)", "$list" );
    }

    public function testSetIncludeQuoted ()
    {
        $list = new \r8\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( FALSE ) );

        $this->assertFalse( $list->getIncludeQuoted() );

        $this->assertSame( $list, $list->setIncludeQuoted( TRUE ) );

        $this->assertTrue( $list->getIncludeQuoted() );
    }

    public function testSetIncludeUnquoted ()
    {
        $list = new \r8\Quoter\Parsed;

        $this->assertTrue( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( FALSE ) );

        $this->assertFalse( $list->getIncludeUnquoted() );

        $this->assertSame( $list, $list->setIncludeUnquoted( TRUE ) );

        $this->assertTrue( $list->getIncludeUnquoted() );
    }

    public function testExplode_all ()
    {
        $list = new \r8\Quoter;

        $result = $list->parse( "String with gaps" )->explode(" ");

        $this->assertSame( array("String", "with", "gaps"), $result );

        $result = $list->parse( "String with gaps" )->explode("NotInString");

        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String 'with some' gaps" )->explode(" ");

        $this->assertSame( array("String", "'with", "some'", "gaps"), $result );

        $result = $list->parse( "gg" )->explode("g");

        $this->assertSame( array("", "", ""), $result );
    }

    public function testExplode_noQuoted ()
    {
        $list = new \r8\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("String", "with", "gaps"), $result );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode("NotInString");

        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("String", "'with some'", "gaps"), $result );

        $result = $list->parse( "gg" )
            ->setIncludeQuoted( FALSE )
            ->explode("g");

        $this->assertSame( array("", "", ""), $result );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("'with a few''quoted gaps'"), $result );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeQuoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("'with a few'", "'quoted gaps'"), $result );
    }

    public function testExplode_noUnquoted ()
    {
        $list = new \r8\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");
        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String with gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");

        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("String 'with", "some' gaps"), $result );

        $result = $list->parse( "gg" )
            ->setIncludeUnquoted( FALSE )
            ->explode("g");

        $this->assertSame( array("gg"), $result );

        $result = $list->parse( "'with a few''quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("'with", "a", "few''quoted", "gaps'"), $result );

        $result = $list->parse( "'with a few' 'quoted gaps'" )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("'with", "a", "few' 'quoted", "gaps'"), $result );
    }

    public function testExplode_none ()
    {
        $list = new \r8\Quoter;

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String with gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode("NotInString");

        $this->assertSame( array("String with gaps"), $result );

        $result = $list->parse( "String 'with some' gaps" )
            ->setIncludeQuoted( FALSE )
            ->setIncludeUnquoted( FALSE )
            ->explode(" ");

        $this->assertSame( array("String 'with some' gaps"), $result );
    }

    public function testFilter ()
    {
        $list = new \r8\Quoter;
        $parsed = $list->parse("string 'with' quotes")
            ->filter( new \r8\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'WITH' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeQuoted(FALSE)
            ->filter( new \r8\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "STRING 'with' QUOTES", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->filter( new \r8\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'WITH' quotes", $parsed );


        $parsed = $list->parse("string 'with' quotes")
            ->setIncludeUnquoted(FALSE)
            ->setIncludeQuoted(FALSE)
            ->filter( new \r8\Curry\Call("strtoupper") )
            ->__toString();
        $this->assertSame( "string 'with' quotes", $parsed );
    }
}

?>