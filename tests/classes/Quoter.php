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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * Unit Tests
 */
class classes_quoter extends PHPUnit_Framework_TestCase
{

    public function testInitial ()
    {
        $quoter = new \cPHP\Quoter;

        $this->assertThat( $quoter->getQuotes(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()->get()
            );

        $this->assertSame( '\\', $quoter->getEscape() );
    }

    public function testClearQuotes ()
    {
        $quoter = new \cPHP\Quoter;

        $this->assertSame(
                array( "'" => array( "'" ), '"' => array( '"' ) ),
                $quoter->getQuotes()->get()
            );

        $this->assertSame( $quoter, $quoter->clearQuotes() );

        $this->assertSame(
                array(),
                $quoter->getQuotes()->get()
            );
    }

    public function testSetQuote ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->clearQuotes();

        $this->assertSame(
                array(),
                $quoter->getQuotes()->get()
            );

        $this->assertSame( $quoter, $quoter->setQuote( "`" ) );

        $this->assertSame(
                array( "`" => array("`") ),
                $quoter->getQuotes()->get()
            );


        $this->assertSame( $quoter, $quoter->setQuote( "(", ")" ) );

        $this->assertSame(
                array( "`" => array("`"), "(" => array( ")" ) ),
                $quoter->getQuotes()->get()
            );
    }

    public function testGetAllQuotes ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );

        $quotes = $quoter->getAllQuotes();
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array( "'", '"', '`', '@', '#', '*', "!" ),
                $quotes->get()
            );
    }

    public function testGetOpenQuotes ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setQuote( "'", array( "'", '"', '`' ) );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );

        $quotes = $quoter->getOpenQuotes();
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals(
                array( "'", '"', '!', '(', '`' ),
                $quotes->get()
            );
    }

    public function testIsOpenQuote ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );

        $this->assertTrue( $quoter->isOpenQuote("(") );
        $this->assertTrue( $quoter->isOpenQuote("`") );
        $this->assertFalse( $quoter->isOpenQuote(")") );
    }

    public function testGetCloseQuotesFor ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setQuote( "(", ")" );
        $quoter->setQuote( "`" );
        $quoter->setQuote( "!", array( '@', '#', '*' ) );

        $quotes = $quoter->getCloseQuotesFor( "(" );
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( array( ')' ), $quotes->get() );

        $quotes = $quoter->getCloseQuotesFor( "!" );
        $this->assertThat( $quotes, $this->isInstanceOf("cPHP\Ary") );
        $this->assertEquals( array( '@', '#', '*' ), $quotes->get() );
    }

    public function testSetEscape ()
    {
        $quoter = new \cPHP\Quoter;
        $this->assertSame( $quoter, $quoter->setEscape( "new" ) );
        $this->assertSame( "new", $quoter->getEscape() );

        try {
            $quoter->setEscape("");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Must not be empty", $err->getMessage() );
        }
    }

    public function testClearEscape ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setEscape( '\\' );

        $this->assertSame( $quoter, $quoter->clearEscape() );
        $this->assertNull( $quoter->getEscape() );
    }

    public function testEscapeExists ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->setEscape( '\\' );

        $this->assertTrue( $quoter->escapeExists() );

        $quoter->clearEscape();

        $this->assertFalse( $quoter->escapeExists() );
    }

    public function testIsEscaped ()
    {
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 90) );

        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 0) );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 8) );

        $this->assertTrue( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 4) );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\\\'t it escaped?", 5) );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn\\\\\\'t it escaped?", 6) );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\\\\\\\'t it escaped?", 7) );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 8) );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\\\\\\\\\'t it escaped?", 9) );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn\\\\\\\\\\\\\\'t it escaped?", 10) );

        $this->assertFalse( \cPHP\Quoter::isEscaped("\\\\isn't it escaped?", 2) );


        $this->assertFalse( \cPHP\Quoter::isEscaped("isn't it escaped?", 2, '/esc/') );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn/esc/'t it escaped?", 8, '/esc/') );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn/EsC/'t it escaped?", 8, '/esc/') );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn/Esc//EsC/'t it escaped?", 13, '/esc/') );
        $this->assertTrue( \cPHP\Quoter::isEscaped("isn/EsC//Esc//EsC/'t it escaped?", 18, '/esc/') );

        $this->assertTrue( \cPHP\Quoter::isEscaped("/esc/isn't it escaped?", 5, '/esc/') );

        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 4, null) );
        $this->assertFalse( \cPHP\Quoter::isEscaped("isn\\'t it escaped?", 8, null) );
    }

    public function testFindNext ()
    {
        try {
            \cPHP\Quoter::findNext( "string", array ('"', "") );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame( "Needle must not be empty", $err->getMessage() );
        }

        $this->assertSame(
                array(false, false),
                \cPHP\Quoter::findNext( "string", array() )
            );

        $this->assertSame(
                array(8, "'"),
                \cPHP\Quoter::findNext(
                        "It\\'s a 'quoted' string",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \cPHP\Quoter::findNext(
                        "It\\'s a \\'quoted\\'  string",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(0, '"'),
                \cPHP\Quoter::findNext(
                        '"Its a \\"quoted\\"  string',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \cPHP\Quoter::findNext(
                        'String without quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(false, false),
                \cPHP\Quoter::findNext(
                        'String \"without\" quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(6, "'"),
                \cPHP\Quoter::findNext(
                        'String\' "with" \'quotes',
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(6, '"'),
                \cPHP\Quoter::findNext(
                        "String\" 'with' \"quotes",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(19, "'"),
                \cPHP\Quoter::findNext(
                        "String with a quote'",
                        array ('"', "'"),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \cPHP\Quoter::findNext(
                        "String with QTa quote",
                        array ('QT'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(22, "QT"),
                \cPHP\Quoter::findNext(
                        "String with \\QTa quoteQT",
                        array ('QT'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \cPHP\Quoter::findNext(
                        "String with qtQT2 a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(14, "QT"),
                \cPHP\Quoter::findNext(
                        "String q with qt a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(12, "QT"),
                \cPHP\Quoter::findNext(
                        "String with QT2a quote",
                        array ('QT', 'QT2'),
                        '\\'
                    )
            );

        $this->assertSame(
                array(7, "with"),
                \cPHP\Quoter::findNext(
                        "String with a quote",
                        array ('with', 'it'),
                        '\\'
                    )
            );

    }

    public function testParse_endWithUnquoted ()
    {
        $quoter = new \cPHP\Quoter;

        $result = $quoter->parse("string 'with' quotes");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("string ", "'with'", " quotes"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( "string ", $offset->getContent() );
        $this->assertSame( 0, $offset->getOffset() );


        $offset = $result->getSections()->OffsetGet(1);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "with", $offset->getContent() );
        $this->assertSame( 8, $offset->getOffset() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );


        $offset = $result->getSections()->OffsetGet(2);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( " quotes", $offset->getContent() );
        $this->assertSame( 13, $offset->getOffset() );

    }

    public function testParse_endWithQuoted ()
    {
        $quoter = new \cPHP\Quoter;

        $result = $quoter->parse("string 'with'");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("string ", "'with'"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( "string ", $offset->getContent() );
        $this->assertSame( 0, $offset->getOffset() );


        $offset = $result->getSections()->OffsetGet(1);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "with", $offset->getContent() );
        $this->assertSame( 8, $offset->getOffset() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_onlyUnquoted()
    {
        $quoter = new \cPHP\Quoter;

        $result = $quoter->parse("This is a string");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("This is a string"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( "This is a string", $offset->getContent() );
        $this->assertSame( 0, $offset->getOffset() );
    }

    public function testParse_onlyQuoted()
    {
        $quoter = new \cPHP\Quoter;

        $result = $quoter->parse("'This is a string'");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("'This is a string'"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is a string", $offset->getContent() );
        $this->assertSame( 1, $offset->getOffset() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_touchingQuoted()
    {
        $quoter = new \cPHP\Quoter;

        $result = $quoter->parse("'This is''a string'");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("'This is'", "'a string'"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is", $offset->getContent() );
        $this->assertSame( 1, $offset->getOffset() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );


        $offset = $result->getSections()->OffsetGet(1);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "a string", $offset->getContent() );
        $this->assertSame( 10, $offset->getOffset() );
        $this->assertSame( "'", $offset->getOpenQuote() );
        $this->assertSame( "'", $offset->getCloseQuote() );
    }

    public function testParse_oddQuotes ()
    {
        $quoter = new \cPHP\Quoter;
        $quoter->clearQuotes()
            ->setQuote("<({", array( "END OF QUOTE", "))" ) );

        $result = $quoter->parse("<({This isEND OF QUOTE a string <({with stuff)) in it");
        $this->assertThat( $result, $this->isInstanceOf("cPHP\Quoter\Parsed") );
        $this->assertThat( $result->getSections(), $this->isInstanceOf("cPHP\Ary") );

        $this->assertSame(
                array("<({This isEND OF QUOTE", " a string ", "<({with stuff))", " in it"),
                $result->getSections()->collect('cPHP\strval')->get()
            );


        $offset = $result->getSections()->OffsetGet(0);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "This is", $offset->getContent() );
        $this->assertSame( 3, $offset->getOffset() );
        $this->assertSame( "<({", $offset->getOpenQuote() );
        $this->assertSame( "END OF QUOTE", $offset->getCloseQuote() );


        $offset = $result->getSections()->OffsetGet(1);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( " a string ", $offset->getContent() );
        $this->assertSame( 22, $offset->getOffset() );


        $offset = $result->getSections()->OffsetGet(2);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Quoted")
            );
        $this->assertSame( "with stuff", $offset->getContent() );
        $this->assertSame( 35, $offset->getOffset() );
        $this->assertSame( "<({", $offset->getOpenQuote() );
        $this->assertSame( "))", $offset->getCloseQuote() );


        $offset = $result->getSections()->OffsetGet(3);
        $this->assertThat(
                $offset,
                $this->isInstanceOf("cPHP\Quoter\Section\Unquoted")
            );
        $this->assertSame( " in it", $offset->getContent() );
        $this->assertSame( 47, $offset->getOffset() );
    }

}

?>