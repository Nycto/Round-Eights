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
 * Tests for the 'Q' encoding
 */
class classes_Transform_MIME_Q extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        $this->iniSet("iconv.internal_encoding", "ISO-8859-1");
    }

    public function test_longHeaderName ()
    {
        $mime = new \r8\Transform\MIME\Q;
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
        $mime = new \r8\Transform\MIME\Q;
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

    public function test_noWrap ()
    {
        $mime = new \r8\Transform\MIME\Q;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?This_is_a_string?=",
                $mime->to( "This is a string" )
            );

        $mime->setHeader("X-Info");

        $this->assertSame(
                "X-Info: =?ISO-8859-1?Q?Chunk_of_data?=",
                $mime->to( "Chunk of data" )
            );
    }

    public function test_noFirstLineContent ()
    {

        $mime = new \r8\Transform\MIME\Q;
        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\r\n"
                ."\t=?ISO-8859-1?Q?A_Short_Stri?=\r\n"
                ."\t=?ISO-8859-1?Q?ng?=",
                $mime->to("A Short String")
            );

    }

    public function test_whitespace ()
    {
        $mime = new \r8\Transform\MIME\Q;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?Break=0D=0A=09String?=",
                $mime->to( "Break\r\n\tString" )
            );
        $this->assertSame(
                "=?ISO-8859-1?Q?Break___String?=",
                $mime->to( "Break   String" )
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?___String___?=",
                $mime->to( "   String   " )
            );
    }

    public function test_wrap_noHeader ()
    {
        $mime = new \r8\Transform\MIME\Q;

        $this->assertSame(
                "=?ISO-8859-1?Q?A_short_string?=",
                $mime->to("A short string")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_of_its_?=\r\n"
                ."\t=?ISO-8859-1?Q?length._It_is_oh_so_long.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long.")
            );

        $this->assertSame(
                "=?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_of_its_?=\r\n"
                ."\t=?ISO-8859-1?Q?length._It_is_oh_so_long._It_is_so_long,_in_fact,_that_it_sh?=\r\n"
                ."\t=?ISO-8859-1?Q?ould_be_wrapped_a_few_times.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_wrap_withHeader ()
    {
        $mime = new \r8\Transform\MIME\Q;
        $mime->setHeader("X-Test");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_short_string?=",
                $mime->to("A short string")
            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\r\n"
                ."\t=?ISO-8859-1?Q?of_its_length.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length.")

            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\r\n"
                ."\t=?ISO-8859-1?Q?of_its_length._It_is_oh_so_long._It_is_so_long,_in_fact,_tha?=\r\n"
                ."\t=?ISO-8859-1?Q?t_it_should_be_wrapped_a_few_times.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );
    }

    public function test_changedEOL ()
    {
        $mime = new \r8\Transform\MIME\Q;
        $mime->setHeader("X-Test");
        $mime->setEOL("\n");

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\n"
                ."\t=?ISO-8859-1?Q?of_its_length.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length.")

            );

        $this->assertSame(
                "X-Test: =?ISO-8859-1?Q?A_longer_string_that_will_need_to_be_wrapped_because_?=\n"
                ."\t=?ISO-8859-1?Q?of_its_length._It_is_oh_so_long._It_is_so_long,_in_fact,_tha?=\n"
                ."\t=?ISO-8859-1?Q?t_it_should_be_wrapped_a_few_times.?=",
                $mime->to("A longer string that will need to be wrapped "
                        ."because of its length. It is oh so long. It is so long, "
                        ."in fact, that it should be wrapped a few times.")
            );

        $mime->setLineLength(30);
        $mime->setHeader("X-A-Long-Header-Name");

        $this->assertSame(
                "X-A-Long-Header-Name:\n"
                ."\t=?ISO-8859-1?Q?A_Short_Stri?=\n"
                ."\t=?ISO-8859-1?Q?ng?=",
                $mime->to("A Short String")
            );
    }

    public function test_encoding ()
    {
        $mime = new \r8\Transform\MIME\Q;
        $mime->setLineLength(0);

        $mime->setOutputEncoding("UTF-8");

        $this->assertSame(
                "=?UTF-8?Q?Pr=C3=83=C2=BCfung_Pr=C3=83=C2=BCfung?=",
                $mime->to( "Prüfung Prüfung" )
            );
    }

    public function test_characters ()
    {

        $mime = new \r8\Transform\MIME\Q;
        $mime->setLineLength(0);

        $this->assertSame(
                "=?ISO-8859-1?Q?_=5F=3F=3D=3A?=",
                $mime->to( " _?=:" )
            );

        $chars = implode("", array_map("chr", range(0, 32) ) );
        $this->assertSame(
                "=?ISO-8859-1?Q?=00=01=02=03=04=05=06=07=08=09=0A=0B=0C=0D=0E=0F"
                ."=10=11=12=13=14=15=16=17=18=19=1A=1B=1C=1D=1E=1F_?=",
                $mime->to( $chars )
            );

        $chars = implode("", array_map("chr", range(33, 126) ) );
        $this->assertSame(
                "=?ISO-8859-1?Q?!\"#$%&'()*+,-./0123456789=3A;<=3D>=3F@ABCDEFGHIJK"
                ."LMNOPQRSTUVWXYZ[\]^=5F`abcdefghijklmnopqrstuvwxyz{|}~?=",
                $mime->to( $chars )
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
                $mime->to( $chars )
            );
    }

}

?>