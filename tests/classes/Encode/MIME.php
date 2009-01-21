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
class classes_encode_mime extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function testStripHeaderName ()
    {
        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                \cPHP\Encode\MIME::stripHeaderName( $chars )
            );
    }

    public function testCanRawEncode ()
    {
        $chars = implode("", array_map( 'chr', range(32, 126) ));
        $this->assertTrue( \cPHP\Encode\MIME::canRawEncode($chars) );

        $chars = array_merge( range(1, 31), range(127, 255) );
        foreach ( $chars AS $char ) {
            if ( \cPHP\Encode\MIME::canRawEncode( chr($char) ) ) {
                $this->fail(
                        "failed asserting that character with the ascii "
                        ."code ". $char ." can't be raw encoded"
                    );
            }
        }
    }

    public function testLineLengthAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;
        $this->assertSame( 78, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(999) );
        $this->assertSame( 999, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength("50") );
        $this->assertSame( 50, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(500.5) );
        $this->assertSame( 500, $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(0) );
        $this->assertFalse( $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(-20) );
        $this->assertFalse( $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(null) );
        $this->assertFalse( $mime->getLineLength() );

        $this->assertSame( $mime, $mime->setLineLength(FALSE) );
        $this->assertFalse( $mime->getLineLength() );
    }

    public function testHeaderAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertFalse( $mime->headerExists() );
        $this->assertNull( $mime->getHeader() );

        $this->assertSame( $mime, $mime->setHeader("Return-Path") );
        $this->assertTrue( $mime->headerExists() );
        $this->assertSame( "Return-Path", $mime->getHeader() );

        $this->assertSame( $mime, $mime->clearHeader() );
        $this->assertFalse( $mime->headerExists() );
        $this->assertNull( $mime->getHeader() );

        $this->assertSame( $mime, $mime->setHeader( "  " ) );
        $this->assertFalse( $mime->headerExists() );
        $this->assertNull( $mime->getHeader() );

        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame( $mime, $mime->setHeader($chars) );
        $this->assertTrue( $mime->headerExists() );
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                $mime->getHeader()
            );
    }

    public function testEolAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;
        $this->assertSame( "\r\n", $mime->getEOL() );

        $this->assertSame( $mime, $mime->setEOL("BREAK") );
        $this->assertSame( "BREAK", $mime->getEOL() );

        $this->assertSame( $mime, $mime->resetEOL() );
        $this->assertSame( "\r\n", $mime->getEOL() );

        $this->assertSame( $mime, $mime->setEOL("") );
        $this->assertSame( "", $mime->getEOL() );

        $this->assertSame( $mime, $mime->setEOL( null ) );
        $this->assertSame( "", $mime->getEOL() );

        $this->assertSame( $mime, $mime->setEOL( "\n" ) );
        $this->assertSame( "\n", $mime->getEOL() );

    }

    public function testInputEncodingAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;
        $this->assertSame("ISO-8859-1", $mime->getInputEncoding());

        $this->assertSame( $mime, $mime->setInputEncoding("UTF-8") );
        $this->assertSame("UTF-8", $mime->getInputEncoding());

        $this->assertSame( $mime, $mime->resetInputEncoding() );
        $this->assertSame("ISO-8859-1", $mime->getInputEncoding());

        // If the encoding has been reset, it should immediately react to a setting change
        iconv_set_encoding("internal_encoding", "UTF-8");
        $this->assertSame("UTF-8", $mime->getInputEncoding());

        try {
            $mime->setInputEncoding("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testRawEncode_charStrip ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                $mime->rawEncode( $chars )
            );

    }

    public function testRawEncode_whitespace ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\nString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\rString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\r\nString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\n\tString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\n  \tString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->rawEncode( "Break\n\n\n\r\n\r\r\rString" )
            );

        $this->assertSame(
                "String",
                $mime->rawEncode( "   String   " )
            );

        $this->assertSame(
                "String",
                $mime->rawEncode( "\nString\n\r" )
            );

        $mime->setEOL("\n");

        $this->assertSame(
                "Break\n\tString",
                $mime->rawEncode( "Break\nString" )
            );

        $mime->setEOL("RETURN");

        $this->assertSame(
                "BreakRETURN\tString",
                $mime->rawEncode( "Break\rString" )
            );

    }

    public function testRawEncode_noWrap ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "This is a string that needs to be returned as it but it is rather long",
                $mime->rawEncode( "This is a string that needs to be returned as it but it is rather long" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: Chunk of data",
                $mime->rawEncode( "Chunk of data" )
            );
    }

    public function testRawEncode_wrap_noHeader ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame(
                "A short string",
                $mime->rawEncode("A short string")
            );

        $this->assertSame(
                "Testing a longer line that needs to be wrapped just once because it exceeds\r\n"
                ."\tthe seventy eight character limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just once "
                        ."because it exceeds the seventy eight character limit"
                    )
            );

        $this->assertSame(
                "Testing a longer line that needs to be wrapped just twice because it exceeds\r\n"
                ."\tthe seventy eight character limit twotimes so it should be wrapped to fit\r\n"
                ."\twithin the limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two"
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setEOL("\n");

        $this->assertSame(
                "Testing a longer line that needs to be wrapped just twice because it exceeds\n"
                ."\tthe seventy eight character limit twotimes so it should be wrapped to fit\n"
                ."\twithin the limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two"
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setLineLength(20);

        $this->assertSame(
                "aaaaaaaaaaaaaaaaaaaa",
                $mime->rawEncode( str_repeat("a", 20) )
            );

        $this->assertSame(
                "aaaaaaaaaaaaaaaaaaaa\n"
                ."\ta",
                $mime->rawEncode( str_repeat("a", 21) )
            );

    }

    public function testRawEncode_wrap_withHeader ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-Head");

        $this->assertSame(
                "X-Head: A short string",
                $mime->rawEncode("A short string")
            );

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just once because it\r\n"
                ."\texceeds the seventy eight character limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just once "
                        ."because it exceeds the seventy eight character limit"
                    )
            );

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just twice because it\r\n"
                ."\texceeds the seventy eight character limit two times so it should be wrapped to\r\n"
                ."\tfit within the limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two "
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setEOL("\n");

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just twice because it\n"
                ."\texceeds the seventy eight character limit two times so it should be wrapped to\n"
                ."\tfit within the limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two "
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setLineLength(20);

        $this->assertSame(
                "X-Head: Testing a\n"
                ."\tline that needs to\n"
                ."\tbe wrapped twice",
                $mime->rawEncode(
                        "Testing a line that needs to be wrapped twice"
                    )
            );
    }

    public function testRawEncode_longHeaderName ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped");
        $mime->setLineLength(20);

        $this->assertSame(
                "X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped:\r\n"
                ."\tA short string",
                $mime->rawEncode("A short string")
            );
        $this->assertSame(
                "X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped:\r\n"
                ."\tTesting a line that\r\n"
                ."\tneeds to be wrapped\r\n"
                ."\ttwice",
                $mime->rawEncode(
                        "Testing a line that needs to be wrapped twice"
                    )
            );
    }

    public function testEncode ()
    {
        $this->markTestIncomplete("To be written");
    }

    public function testDecode ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>