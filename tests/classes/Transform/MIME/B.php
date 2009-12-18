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
 * Tests for the 'B' encoding
 */
class classes_Transform_MIME_B extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function test_noWrap ()
    {
        $mime = new \r8\Transform\MIME\B;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?B?VGhpcyBpcyBhIHN0cmluZw==?=",
                $mime->to( "This is a string" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: =?ISO-8859-1?B?Q2h1bmsgb2YgZGF0YQ==?=",
                $mime->to( "Chunk of data" )
            );
    }

    public function test_charSet ()
    {
        $mime = new \r8\Transform\MIME\B;
        $mime->setLineLength(0);

        $mime->setOutputEncoding("UTF-8");

        $this->assertSame(
                "=?UTF-8?B?UHLDg8K8ZnVuZyBQcsODwrxmdW5n?=",
                $mime->to( "Prüfung Prüfung" )
            );
    }

    public function test_wrap_noHeader ()
    {
        $mime = new \r8\Transform\MIME\B;

        $this->assertSame(
                "=?ISO-8859-1?B?QSBzaG9ydCBzdHJpbmc=?=",
                $mime->to("A short string")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdyYXBwZWQg?=\r\n"
                ."\t=?ISO-8859-1?B?YmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25nLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long.")
            );

        $this->assertSame(
                "=?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdyYXBwZWQg?=\r\n"
                ."\t=?ISO-8859-1?B?YmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25nLiBJdCBp?=\r\n"
                ."\t=?ISO-8859-1?B?cyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3cmFwcGVk?=\r\n"
                ."\t=?ISO-8859-1?B?IGEgZmV3IHRpbWVzLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_wrap_withHeader ()
    {
        $mime = new \r8\Transform\MIME\B;
        $mime->setHeader("X-Test");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBzaG9ydCBzdHJpbmc=?=",
                $mime->to("A short string")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\r\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length.")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\r\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25n?=\r\n"
                ."\t=?ISO-8859-1?B?LiBJdCBpcyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3?=\r\n"
                ."\t=?ISO-8859-1?B?cmFwcGVkIGEgZmV3IHRpbWVzLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_changedEOL ()
    {
        $mime = new \r8\Transform\MIME\B;
        $mime->setHeader("X-Test");
        $mime->setEOL("\n");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length.")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?B?QSBsb25nZXIgc3RyaW5nIHRoYXQgd2lsbCBuZWVkIHRvIGJlIHdy?=\n"
                ."\t=?ISO-8859-1?B?YXBwZWQgYmVjYXVzZSBvZiBpdHMgbGVuZ3RoLiBJdCBpcyBvaCBzbyBsb25n?=\n"
                ."\t=?ISO-8859-1?B?LiBJdCBpcyBzbyBsb25nLCBpbiBmYWN0LCB0aGF0IGl0IHNob3VsZCBiZSB3?=\n"
                ."\t=?ISO-8859-1?B?cmFwcGVkIGEgZmV3IHRpbWVzLg==?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_longHeaderName ()
    {
        $mime = new \r8\Transform\MIME\B;
        $mime->setHeader("X-A-Really-Long-Header-Name");
        $mime->setLineLength(20);

        try {
            $mime->to("A string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame(
                    "Header length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_longContent ()
    {

        $mime = new \r8\Transform\MIME\B;
        $mime->setLineLength(15);

        try {
            $mime->to("A short string");
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Data $err ) {
            $this->assertSame(
                    "Required content length exceeds the maximum line length",
                    $err->getMessage()
                );
        }
    }

    public function test_noFirstLineContent ()
    {

        $mime = new \r8\Transform\MIME\B;
        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\r\n"
                ."\t=?ISO-8859-1?B?QSBTaG9ydCBT?=\r\n"
                ."\t=?ISO-8859-1?B?dHJpbmc=?=",
                $mime->to("A Short String")
            );

    }

}

?>