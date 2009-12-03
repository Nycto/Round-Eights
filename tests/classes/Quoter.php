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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * Unit Tests
 */
class classes_quoter extends PHPUnit_Framework_TestCase
{

    public function testInitial ()
    {
        $quoter = new \r8\Quoter;

        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()
            );

        $this->assertSame( '\\', $quoter->getEscape() );
    }

    public function testClearQuotes ()
    {
        $quoter = new \r8\Quoter;

        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()
            );

        $this->assertSame( $quoter, $quoter->clearQuotes() );

        $this->assertSame(
                array(),
                $quoter->getQuotes()
            );
    }

    public function testSetQuote ()
    {
        $quoter = new \r8\Quoter;
        $quoter->clearQuotes();

        $this->assertSame(
                array(),
                $quoter->getQuotes()
            );

        $this->assertSame( $quoter, $quoter->setQuote( "`" ) );

        $this->assertSame(
                array( "`" => array("`") ),
                $quoter->getQuotes()
            );


        $this->assertSame( $quoter, $quoter->setQuote( "(", ")" ) );

        $this->assertSame(
                array( "`" => array("`"), "(" => array( ")" ) ),
                $quoter->getQuotes()
            );
    }

    public function testGetAllQuotes ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );

        $this->assertEquals(
                array( "'", '"', '`', '@', '#', '*', "!" ),
                $quoter->getAllQuotes()
            );
    }

    public function testGetOpenQuotes ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );

        $this->assertEquals(
                array( "'", '"', '!', '(', '`' ),
                $quoter->getOpenQuotes()
            );
    }

    public function testIsOpenQuote ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );

        $this->assertTrue( $quoter->isOpenQuote("(") );
        $this->assertTrue( $quoter->isOpenQuote("`") );
        $this->assertFalse( $quoter->isOpenQuote(")") );
    }

    public function testGetCloseQuotesFor ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );

        $this->assertEquals(
                array( ')' ),
                $quoter->getCloseQuotesFor( "(" )
            );

        $this->assertEquals(
                array( '@', '#', '*' ),
                $quoter->getCloseQuotesFor( "!" )
            );
    }

    public function testSetEscape ()
    {
        $quoter = new \r8\Quoter;
        $this->assertSame( $quoter, $quoter->setEscape( "new" ) );
        $this->assertSame( "new", $quoter->getEscape() );

        try {
            $quoter->setEscape("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearEscape ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setEscape( '\\' );

        $this->assertSame( $quoter, $quoter->clearEscape() );
        $this->assertNull( $quoter->getEscape() );
    }

    public function testEscapeExists ()
    {
        $quoter = new \r8\Quoter;
        $quoter->setEscape( '\\' );

        $this->assertTrue( $quoter->escapeExists() );

        $quoter->clearEscape();

        $this->assertFalse( $quoter->escapeExists() );
    }

    public function testIsEscaped ()
    {
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\'t it escaped?", 90) );

        $this->assertFalse( \r8\Quoter::isEscaped("isn\\'t it escaped?", 0) );
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\'t it escaped?", 8) );

        $this->assertTrue( \r8\Quoter::isEscaped("isn\\'t it escaped?", 4) );
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\\\'t it escaped?", 5) );
        $this->assertTrue( \r8\Quoter::isEscaped("isn\\\\\\'t it escaped?", 6) );
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\\\\\\\'t it escaped?", 7) );
        $this->assertTrue( \r8\Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 8) );
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 9) );
        $this->assertTrue( \r8\Quoter::isEscaped("isn\\\\\\\\\\\\\\'t it escaped?", 10) );

        $this->assertFalse( \r8\Quoter::isEscaped("\\\\isn't it escaped?", 2) );


        $this->assertFalse( \r8\Quoter::isEscaped("isn't it escaped?", 2, '/esc/') );
        $this->assertTrue( \r8\Quoter::isEscaped("isn/esc/'t it escaped?", 8, '/esc/') );
        $this->assertTrue( \r8\Quoter::isEscaped("isn/EsC/'t it escaped?", 8, '/esc/') );
        $this->assertFalse( \r8\Quoter::isEscaped("isn/Esc//EsC/'t it escaped?", 13, '/esc/') );
        $this->assertTrue( \r8\Quoter::isEscaped("isn/EsC//Esc//EsC/'t it escaped?", 18, '/esc/') );

        $this->assertTrue( \r8\Quoter::isEscaped("/esc/isn't it escaped?", 5, '/esc/') );

        $this->assertFalse( \r8\Quoter::isEscaped("isn\\'t it escaped?", 4, null) );
        $this->assertFalse( \r8\Quoter::isEscaped("isn\\'t it escaped?", 8, null) );
    }

    public function testFindNext ()
    {
        try {
            \r8\Quoter::findNext( "string", array ('"', "") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame( "Needle must not be empty", $err->getMessage() );
        }

        $this->assertSame(
                array(false, false),
                \r8\Quoter::findNext( "string", array() )
            );

        $this->assertSame(
                array(8, "'"),
                \r8\Quoter::findNext(
                        "It\\'s a 'quoted' string",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \r8\Quoter::findNext(
                        "It\\'s a \\'quoted\\'  string",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(0, '"'),
                \r8\Quoter::findNext(
                        '"Its a \\"quoted\\"  string',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \r8\Quoter::findNext(
                        'String without quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \r8\Quoter::findNext(
                        'String \"without\" quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(6, "'"),
                \r8\Quoter::findNext(
                        'String\' "with" \'quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(6, '"'),
                \r8\Quoter::findNext(
                        "String\" 'with' \"quotes",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(19, "'"),
                \r8\Quoter::findNext(
                        "String with a quote'",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \r8\Quoter::findNext(
                        "String with QTa quote",
                        array ('QT'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(22, "QT"),
                \r8\Quoter::findNext(
                        "String with \\QTa quoteQT",
                        array ('QT'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \r8\Quoter::findNext(
                        "String with qtQT2 a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(14, "QT"),
                \r8\Quoter::findNext(
                        "String q with qt a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \r8\Quoter::findNext(
                        "String with QT2a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(7, "with"),
                \r8\Quoter::findNext(
                        "String with a quote",
                        array ('with', 'it'),
                        '\\'
                    )
            );

    }

    public function testParse_endWithUnquoted ()
    {
        $quoter = new \r8\Quoter;

        $result = $quoter->parse("string 'with' quotes");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );
        $this->assertType( 'array', $result->getSections() );

        $this->assertSame(
                array("string ", "'with'", " quotes"),
                array_map( 'r8\strval', $result->getSections() )
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( "string ", $offset->getContent() );


        $offset = $result->getSections();
        $offset = $offset[1];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "with", $offset->getContent() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );


        $offset = $result->getSections();
        $offset = $offset[2];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( " quotes", $offset->getContent() );

    }

    public function testParse_endWithQuoted ()
    {
        $quoter = new \r8\Quoter;

        $result = $quoter->parse("string 'with'");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );
        $this->assertType( 'array', $result->getSections() );

        $this->assertSame(
                array("string ", "'with'"),
                array_map( 'r8\strval', $result->getSections() )
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( "string ", $offset->getContent() );


        $offset = $result->getSections();
        $offset = $offset[1];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "with", $offset->getContent() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_onlyUnquoted()
    {
        $quoter = new \r8\Quoter;

        $result = $quoter->parse("This is a string");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );
        $this->assertType( 'array', $result->getSections() );

        $this->assertSame(
                array("This is a string"),
                array_map('r8\strval', $result->getSections() )
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( "This is a string", $offset->getContent() );
    }

    public function testParse_onlyQuoted()
    {
        $quoter = new \r8\Quoter;

        $result = $quoter->parse("'This is a string'");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );
        $this->assertType( 'array', $result->getSections() );

        $this->assertSame(
                array("'This is a string'"),
                array_map('r8\strval', $result->getSections() )
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is a string", $offset->getContent() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_touchingQuoted()
    {
        $quoter = new \r8\Quoter;

        $result = $quoter->parse("'This is''a string'");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );
        $this->assertType( 'array', $result->getSections() );

        $this->assertSame(
                array("'This is'", "'a string'"),
                array_map('r8\strval', $result->getSections() )
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is", $offset->getContent() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );


        $offset = $result->getSections();
        $offset = $offset[1];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "a string", $offset->getContent() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_oddQuotes ()
    {
        $quoter = new \r8\Quoter;
        $quoter->clearQuotes()
            ->setQuote("<({", array( "END OF QUOTE", "))" ) );

        $result = $quoter->parse("<({This isEND OF QUOTE a string <({with stuff)) in it");
        $this->assertThat( $result, $this->isInstanceOf("r8\Quoter\Parsed") );

        $this->assertSame(
                array("<({This isEND OF QUOTE", " a string ", "<({with stuff))", " in it"),
                array_map("r8\strval", $result->getSections())
            );


        $offset = $result->getSections();
        $offset = $offset[0];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is", $offset->getContent() );
        $this->assertSame( "<({", $offset->getOpenQuote() );
        $this->assertSame( "END OF QUOTE", $offset->getCloseQuote() );


        $offset = $result->getSections();
        $offset = $offset[1];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( " a string ", $offset->getContent() );


        $offset = $result->getSections();
        $offset = $offset[2];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Quoted")
            );
        $this->assertSame( "with stuff", $offset->getContent() );
        $this->assertSame( "<({", $offset->getOpenQuote() );
        $this->assertSame( "))", $offset->getCloseQuote() );


        $offset = $result->getSections();
        $offset = $offset[3];
        $this->assertThat(
                $offset,
                $this->isInstanceOf("r8\Quoter\Section\Unquoted")
            );
        $this->assertSame( " in it", $offset->getContent() );
    }

}

?>