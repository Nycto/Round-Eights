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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_MIME extends PHPUnit_Framework_TestCase
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
                \r8\Transform\MIME::stripHeaderName( $chars )
            );
    }

    public function testLineLengthAccessors ()
    {
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));
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
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));

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
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));
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
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));
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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testOutputEncodingAccessors ()
    {
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));
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
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame("Must not be empty", $err->getMessage());
        }
    }

    public function testDecode ()
    {
        $mime = $this->getMock('\r8\Transform\MIME', array('to'));

        $this->assertSame(
                "a string",
                $mime->from("a string")
            );

        $this->assertSame(
                "A Q Encoded String of data",
                $mime->from("=?ISO-8859-1?Q?A_Q_Encoded?= String =?ISO-8859-1?Q?of_data?=")
            );

        $this->assertSame(
                "its A sample\tstring",
                $mime->from("its =?ISO-8859-1?B?QSBzYW1wbGUJc3RyaW5n?=")
            );

        $this->assertSame(
                "test@example.com",
                $mime->from("Return-Path: test@example.com  ")
            );

        $this->assertSame(
                "A Q Encoded String",
                $mime->from("X-Header: =?ISO-8859-1?Q?A_Q_Encoded?= String")
            );

        $this->assertSame(
                "its A sample\tstring of stuff",
                $mime->from("X-Details:its =?ISO-8859-1?B?QSBzYW1wbGUJc3RyaW5n?= of stuff")
            );
    }

}

