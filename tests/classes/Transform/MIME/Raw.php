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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * Tests for the raw encoding
 */
class classes_Transform_MIME_Raw extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function testCanEncode ()
    {
        $chars = implode("", array_map( 'chr', range(32, 126) ));
        $this->assertTrue( \r8\Transform\MIME\Raw::canEncode($chars) );

        $chars = array_merge( range(1, 31), range(127, 255) );
        foreach ( $chars AS $char ) {
            if ( \r8\Transform\MIME\Raw::canEncode( chr($char) ) ) {
                $this->fail(
                        "failed asserting that character with the ascii "
                        ."code ". $char ." can't be raw encoded"
                    );
            }
        }
    }

    public function test_charStrip ()
    {
        $mime = new \r8\Transform\MIME\Raw;
        $mime->setLineLength(0);

        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789:;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                $mime->to( $chars )
            );

    }

    public function test_whitespace ()
    {
        $mime = new \r8\Transform\MIME\Raw;
        $mime->setLineLength(0);

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\nString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\rString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\r\nString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\n\tString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\n  \tString" )
            );

        $this->assertSame(
                "Break\r\n\tString",
                $mime->to( "Break\n\n\n\r\n\r\r\rString" )
            );

        $this->assertSame(
                "String",
                $mime->to( "   String   " )
            );

        $this->assertSame(
                "String",
                $mime->to( "\nString\n\r" )
            );

        $mime->setEOL("\n");

        $this->assertSame(
                "Break\n\tString",
                $mime->to( "Break\nString" )
            );

        $mime->setEOL("RETURN");

        $this->assertSame(
                "BreakRETURN\tString",
                $mime->to( "Break\rString" )
            );

    }

    public function test_noWrap ()
    {
        $mime = new \r8\Transform\MIME\Raw;
        $mime->setLineLength(0);

        $this->assertSame(
                "This is a string that needs to be returned as it but it is rather long",
                $mime->to( "This is a string that needs to be returned as it but it is rather long" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: Chunk of data",
                $mime->to( "Chunk of data" )
            );
    }

    public function test_wrap_noHeader ()
    {
        $mime = new \r8\Transform\MIME\Raw;

        $this->assertSame(
                "A short string",
                $mime->to("A short string")
            );

        $this->assertSame(
                "Testing a longer line that needs to be wrapped just once because it exceeds\r\n"
                ."\tthe seventy eight character limit",
                $mime->to(
                        "Testing a longer line that needs to be wrapped just once "
                        ."because it exceeds the seventy eight character limit"
                    )
            );

        $this->assertSame(
                "Testing a longer line that needs to be wrapped just twice because it exceeds\r\n"
                ."\tthe seventy eight character limit twotimes so it should be wrapped to fit\r\n"
                ."\twithin the limit",
                $mime->to(
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
                $mime->to(
                        "Testing a longer line that needs to be wrapped just twice "
                        ."because it exceeds the seventy eight character limit two"
                        ."times so it should be wrapped to fit within the limit"
                    )
            );

        $mime->setLineLength(20);

        $this->assertSame(
                "aaaaaaaaaaaaaaaaaaa",
                $mime->to( str_repeat("a", 19) )
            );

        $this->assertSame(
                "aaaaaaaaaaaaaaaaaaa\n"
                ."\ta",
                $mime->to( str_repeat("a", 20) )
            );

    }

    public function test_wrap_withHeader ()
    {
        $mime = new \r8\Transform\MIME\Raw;
        $mime->setHeader("X-Head");

        $this->assertSame(
                "X-Head: A short string",
                $mime->to("A short string")
            );

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just once because it\r\n"
                ."\texceeds the seventy eight character limit",
                $mime->to(
                        "Testing a longer line that needs to be wrapped just once "
                        ."because it exceeds the seventy eight character limit"
                    )
            );

        $this->assertSame(
                "X-Head: Testing a longer line that needs to be wrapped just twice because it\r\n"
                ."\texceeds the seventy eight character limit two times so it should be wrapped\r\n"
                ."\tto fit within the limit",
                $mime->to(
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
                $mime->to(
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
                $mime->to(
                        "Testing a line that needs to be wrapped twice"
                    )
            );
    }

    public function test_longHeaderName ()
    {
        $mime = new \r8\Transform\MIME\Raw;
        $mime->setHeader("X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped");
        $mime->setLineLength(20);

        $this->assertSame(
                "X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped:\r\n"
                ."\tA short string",
                $mime->to("A short string")
            );
        $this->assertSame(
                "X-A-Really-Long-Header-Name-That-Shouldnt-Be-Wrapped:\r\n"
                ."\tTesting a line that\r\n"
                ."\tneeds to be wrapped\r\n"
                ."\ttwice",
                $mime->to(
                        "Testing a line that needs to be wrapped twice"
                    )
            );
    }

}

