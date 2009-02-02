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
 * Suite for running both file test suites
 */
class classes_encode_mime
{

    public static function suite()
    {
        $suite = new cPHP_Base_TestSuite;
        $suite->addTestSuite( 'classes_encode_mime_common' );
        $suite->addTestSuite( 'classes_encode_mime_rawEncode' );
        $suite->addTestSuite( 'classes_encode_mime_qEncode' );
        $suite->addTestSuite( 'classes_encode_mime_bEncode' );
        return $suite;
    }

}

/**
 * unit tests
 */
class classes_encode_mime_common extends PHPUnit_Framework_TestCase
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

    public function testOutputEncodingAccessors ()
    {
        $mime = new \cPHP\Encode\MIME;
        $this->assertSame("ISO-8859-1", $mime->getOutputEncoding());

        $this->assertSame( $mime, $mime->setOutputEncoding("UTF-8") );
        $this->assertSame("UTF-8", $mime->getOutputEncoding());

        $this->assertSame( $mime, $mime->resetOutputEncoding() );
        $this->assertSame("ISO-8859-1", $mime->getOutputEncoding());

        // If the encoding has been reset, it should immediately react to a setting change
        iconv_set_encoding("internal_encoding", "UTF-8");
        $this->assertSame("UTF-8", $mime->getOutputEncoding());

        try {
            $mime->setOutputEncoding("   ");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testUseRaw ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame( $mime, $mime->useRaw() );

        $this->assertSame(
                "A samplestring",
                $mime->encode("A sample\tstring")
            );
    }

    public function testUseB ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame( $mime, $mime->useB() );

        $this->assertSame(
                "=?ISO-8859-1?B?QSBzYW1wbGUJc3RyaW5n?=",
                $mime->encode("A sample\tstring")
            );
    }

    public function testUseQ ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame( $mime, $mime->useQ() );

        $this->assertSame(
                "=?ISO-8859-1?Q?A_sample=09string?=",
                $mime->encode("A sample\tstring")
            );
    }

    public function testUseAuto ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame( $mime, $mime->useAuto() );

        $this->assertSame(
                "A sample string",
                $mime->encode("A sample string")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A=09sample=09string?=",
                $mime->encode("A\tsample\tstring")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?CUEJc3RyaW5nCQ==?=",
                $mime->encode("\tA\tstring\t")
            );
    }

    public function testDecode ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame(
                "a string",
                $mime->decode("a string")
            );

        $this->assertSame(
                "A Q Encoded String of data",
                $mime->decode("=?ISO-8859-1?Q?A_Q_Encoded?= String =?ISO-8859-1?Q?of_data?=")
            );

        $this->assertSame(
                "its A sample\tstring",
                $mime->decode("its =?ISO-8859-1?B?QSBzYW1wbGUJc3RyaW5n?=")
            );

        $this->assertSame(
                "test@example.com",
                $mime->decode("Return-Path: test@example.com  ")
            );

        $this->assertSame(
                "A Q Encoded String",
                $mime->decode("X-Header: =?ISO-8859-1?Q?A_Q_Encoded?= String")
            );

        $this->assertSame(
                "its A sample\tstring of stuff",
                $mime->decode("X-Details:its =?ISO-8859-1?B?QSBzYW1wbGUJc3RyaW5n?= of stuff")
            );
    }

}

/**
 * Tests for the raw encoding
 */
class classes_encode_mime_rawEncode extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function test_charStrip ()
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

    public function test_whitespace ()
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

    public function test_noWrap ()
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

    public function test_wrap_noHeader ()
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
                "aaaaaaaaaaaaaaaaaaa",
                $mime->rawEncode( str_repeat("a", 19) )
            );

        $this->assertSame(
                "aaaaaaaaaaaaaaaaaaa\n"
                ."\ta",
                $mime->rawEncode( str_repeat("a", 20) )
            );

    }

    public function test_wrap_withHeader ()
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
                ."\texceeds the seventy eight character limit two times so it should be wrapped\r\n"
                ."\tto fit within the limit",
                $mime->rawEncode(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two "
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setEOL("\n");

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just twice because it\n"
                ."\texceeds the seventy eight character limit two times so it should be wrapped\n"
                ."\tto fit within the limit",
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

    public function test_longHeaderName ()
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

}

/**
 * Tests for the 'Q' encoding
 */
class classes_encode_mime_qEncode extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function test_longHeaderName ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-A-Really-Long-Header-Name");
        $mime->setLineLength(20);

        try {
            $mime->qEncode("A string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame(
                    "Header length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_longContent ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(15);

        try {
            $mime->bEncode("A short string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame(
                    "Required content length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_noWrap ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?This_is_a_string?=",
                $mime->qEncode( "This is a string" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: =?ISO-8859-1?Q?Chunk_of_data?=",
                $mime->qEncode( "Chunk of data" )
            );
    }

    public function test_noFirstLineContent ()
    {

        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\r\n"
                ."\t=?ISO-8859-1?Q?A_Short_Stri?=\r\n"
                ."\t=?ISO-8859-1?Q?ng?=",
                $mime->qEncode("A Short String")
            );

    }

    public function test_whitespace ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?Break=0D=0A=09String?=",
                $mime->qEncode( "Break\r\n\tString" )
            );
        $this->assertSame(
                "=?ISO-8859-1?Q?Break___String?=",
                $mime->qEncode( "Break   String" )
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?___String___?=",
                $mime->qEncode( "   String   " )
            );
    }

    public function test_wrap_noHeader ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame(
                "=?ISO-8859-1?Q?A_short_string?=",
                $mime->qEncode("A short string")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_of_its_?=\r\n"
                ."\t=?ISO-8859-1?Q?length._It_is_oh_so_long.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long.")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_of_its_?=\r\n"
                ."\t=?ISO-8859-1?Q?length._It_is_oh_so_long._It_is_so_long,_in_fact,_that_it_sh?=\r\n"
                ."\t=?ISO-8859-1?Q?ould_be_wrapped_a_few_times.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_wrap_withHeader ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-Test");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_short_string?=",
                $mime->qEncode("A short string")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\r\n"
                ."\t=?ISO-8859-1?Q?of_its_length.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length.")

            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\r\n"
                ."\t=?ISO-8859-1?Q?of_its_length._It_is_oh_so_long._It_is_so_long,_in_fact,_tha?=\r\n"
                ."\t=?ISO-8859-1?Q?t_it_should_be_wrapped_a_few_times.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_changedEOL ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-Test");
        $mime->setEOL("\n");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\n"
                ."\t=?ISO-8859-1?Q?of_its_length.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length.")

            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\n"
                ."\t=?ISO-8859-1?Q?of_its_length._It_is_oh_so_long._It_is_so_long,_in_fact,_tha?=\n"
                ."\t=?ISO-8859-1?Q?t_it_should_be_wrapped_a_few_times.?=",
                $mime->qEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );

        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\n"
                ."\t=?ISO-8859-1?Q?A_Short_Stri?=\n"
                ."\t=?ISO-8859-1?Q?ng?=",
                $mime->qEncode("A Short String")
            );
    }

    public function test_encoding ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $mime->setOutputEncoding("UTF-8");

        $this->assertSame(
                "=?UTF-8?Q?Pr=C3=83=C2=BCfung_Pr=C3=83=C2=BCfung?=",
                $mime->qEncode( "Prüfung Prüfung" )
            );
    }

    public function test_characters ()
    {

        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?_=5F=3F=3D=3A?=",
                $mime->qEncode( " _?=:" )
            );

        $chars = implode("", array_map("chr", range(0, 32) ) );
        $this->assertSame(
                "=?ISO-8859-1?Q?=00=01=02=03=04=05=06=07=08=09=0A=0B=0C=0D=0E=0F"
                ."=10=11=12=13=14=15=16=17=18=19=1A=1B=1C=1D=1E=1F_?=",
                $mime->qEncode( $chars )
            );

        $chars = implode("", array_map("chr", range(33, 126) ) );
        $this->assertSame(
                "=?ISO-8859-1?Q?!\"#$%&'()*+,-./0123456789=3A;<=3D>=3F@ABCDEFGHIJK"
                ."LMNOPQRSTUVWXYZ[\]^=5F`abcdefghijklmnopqrstuvwxyz{|}~?=",
                $mime->qEncode( $chars )
            );

        $chars = implode("", array_map("chr", range(127, 255) ) );
        $this->assertSame(
                "=?ISO-8859-1?Q?=7F=80=81=82=83=84=85=86=87=88=89=8A=8B=8C=8D=8E"
                ."=8F=90=91=92=93=94=95=96=97=98=99=9A=9B=9C=9D=9E=9F=A0=A1=A2=A3"
                ."=A4=A5=A6=A7=A8=A9=AA=AB=AC=AD=AE=AF=B0=B1=B2=B3=B4=B5=B6=B7=B8"
                ."=B9=BA=BB=BC=BD=BE=BF=C0=C1=C2=C3=C4=C5=C6=C7=C8=C9=CA=CB=CC=CD"
                ."=CE=CF=D0=D1=D2=D3=D4=D5=D6=D7=D8=D9=DA=DB=DC=DD=DE=DF=E0=E1=E2"
                ."=E3=E4=E5=E6=E7=E8=E9=EA=EB=EC=ED=EE=EF=F0=F1=F2=F3=F4=F5=F6=F7"
                ."=F8=F9=FA=FB=FC=FD=FE=FF?=",
                $mime->qEncode( $chars )
            );
    }

}

/**
 * Tests for the 'B' encoding
 */
class classes_encode_mime_bEncode extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function test_noWrap ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?B?VGhpcyBpcyBhIHN0cmluZw==?=",
                $mime->bEncode( "This is a string" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: =?ISO-8859-1?B?Q2h1bmsgb2YgZGF0YQ==?=",
                $mime->bEncode( "Chunk of data" )
            );
    }

    public function test_charSet ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(0);

        $mime->setOutputEncoding("UTF-8");

        $this->assertSame(
                "=?UTF-8?B?UHLDg8K8ZnVuZyBQcsODwrxmdW5n?=",
                $mime->bEncode( "Prüfung Prüfung" )
            );
    }

    public function test_wrap_noHeader ()
    {
        $mime = new \cPHP\Encode\MIME;

        $this->assertSame(
                "=?ISO-8859-1?B?QSBzaG9ydCBzdHJpbmc=?=",
                $mime->bEncode("A short string")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdyYXBwZWQg?=\r\n"
                ."\t=?ISO-8859-1?B?YmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25nLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long.")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdyYXBwZWQg?=\r\n"
                ."\t=?ISO-8859-1?B?YmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25nLiBJdCBp?=\r\n"
                ."\t=?ISO-8859-1?B?cyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3cmFwcGVk?=\r\n"
                ."\t=?ISO-8859-1?B?IGEgZmV3IHRpbWVzLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_wrap_withHeader ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-Test");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBzaG9ydCBzdHJpbmc=?=",
                $mime->bEncode("A short string")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\r\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length.")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\r\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25n?=\r\n"
                ."\t=?ISO-8859-1?B?LiBJdCBpcyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3?=\r\n"
                ."\t=?ISO-8859-1?B?cmFwcGVkIGEgZmV3IHRpbWVzLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_changedEOL ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-Test");
        $mime->setEOL("\n");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length.")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25n?=\n"
                ."\t=?ISO-8859-1?B?LiBJdCBpcyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3?=\n"
                ."\t=?ISO-8859-1?B?cmFwcGVkIGEgZmV3IHRpbWVzLg==?=",
                $mime->bEncode("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_longHeaderName ()
    {
        $mime = new \cPHP\Encode\MIME;
        $mime->setHeader("X-A-Really-Long-Header-Name");
        $mime->setLineLength(20);

        try {
            $mime->bEncode("A string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame(
                    "Header length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_longContent ()
    {

        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(15);

        try {
            $mime->bEncode("A short string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \cPHP\Exception\Data $err ) {
            $this->assertSame(
                    "Required content length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_noFirstLineContent ()
    {

        $mime = new \cPHP\Encode\MIME;
        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\r\n"
                ."\t=?ISO-8859-1?B?QSBTaG9ydCBT?=\r\n"
                ."\t=?ISO-8859-1?B?dHJpbmc=?=",
                $mime->bEncode("A Short String")
            );

    }

}

?>