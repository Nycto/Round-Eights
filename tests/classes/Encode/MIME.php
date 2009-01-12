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

    public function testStripHeaderName ()
    {
        $chars = implode("", array_map( 'chr', range(1, 255) ));
        $this->assertSame(
                '!"#$%&\'()*+,-./0123456789;<=>?@ABCDEFGHIJKLMNOP'
                .'QRSTUVWXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~',
                \cPHP\Encode\MIME::stripHeaderName( $chars )
            );
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