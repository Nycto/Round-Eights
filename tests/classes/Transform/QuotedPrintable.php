<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_quotedprintable extends PHPUnit_Framework_TestCase
{

    public function testEncode ()
    {
        $encode = new \h2o\Transform\QuotedPrintable;

        $this->assertSame(
                "This is a string",
                $encode->encode("This is a string")
            );

        $this->assertSame(
                "=01=02=03=04=05=06=07=08=09=0A=0B=0C=0D=0E=0F=10=11=12=13=14=15=16=17=18=19=\r\n"
                ."=1A=1B=1C=1D=1E=1F !\"#$%&'()*+,-./0123456789:;<=3D>?@ABCDEFGHIJKLMNOPQRSTUV=\r\n"
                ."WXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~=7F=80=81=82=83=84=85=86=87=88=89=\r\n"
                ."=8A=8B=8C=8D=8E=8F=90=91=92=93=94=95=96=97=98=99=9A=9B=9C=9D=9E=9F=A0=A1=A2=\r\n"
                ."=A3=A4=A5=A6=A7=A8=A9=AA=AB=AC=AD=AE=AF=B0=B1=B2=B3=B4=B5=B6=B7=B8=B9=BA=BB=\r\n"
                ."=BC=BD=BE=BF=C0=C1=C2=C3=C4=C5=C6=C7=C8=C9=CA=CB=CC=CD=CE=CF=D0=D1=D2=D3=D4=\r\n"
                ."=D5=D6=D7=D8=D9=DA=DB=DC=DD=DE=DF=E0=E1=E2=E3=E4=E5=E6=E7=E8=E9=EA=EB=EC=ED=\r\n"
                ."=EE=EF=F0=F1=F2=F3=F4=F5=F6=F7=F8=F9=FA=FB=FC=FD=FE=FF",
                $encode->encode( implode("", array_map('chr', range(1,255))) )
            );

        $this->assertSame(
                "Testing  =20\r\nThis",
                $encode->encode("Testing   \r\nThis")
            );

    }

    public function testDecode ()
    {
        $encode = new \h2o\Transform\QuotedPrintable;

        $this->assertSame(
                "This is a string",
                $encode->decode("This is a string")
            );

        $this->assertSame(
                implode("", array_map('chr', range(1,255))),
                $encode->decode(
                        "=01=02=03=04=05=06=07=08=09=0A=0B=0C=0D=0E=0F=10=11=12=13=14=15=16=17=18=19=\r\n"
                        ."=1A=1B=1C=1D=1E=1F !\"#$%&'()*+,-./0123456789:;<=3D>?@ABCDEFGHIJKLMNOPQRSTUV=\r\n"
                        ."WXYZ[\]^_`abcdefghijklmnopqrstuvwxyz{|}~=7F=80=81=82=83=84=85=86=87=88=89=\r\n"
                        ."=8A=8B=8C=8D=8E=8F=90=91=92=93=94=95=96=97=98=99=9A=9B=9C=9D=9E=9F=A0=A1=A2=\r\n"
                        ."=A3=A4=A5=A6=A7=A8=A9=AA=AB=AC=AD=AE=AF=B0=B1=B2=B3=B4=B5=B6=B7=B8=B9=BA=BB=\r\n"
                        ."=BC=BD=BE=BF=C0=C1=C2=C3=C4=C5=C6=C7=C8=C9=CA=CB=CC=CD=CE=CF=D0=D1=D2=D3=D4=\r\n"
                        ."=D5=D6=D7=D8=D9=DA=DB=DC=DD=DE=DF=E0=E1=E2=E3=E4=E5=E6=E7=E8=E9=EA=EB=EC=ED=\r\n"
                        ."=EE=EF=F0=F1=F2=F3=F4=F5=F6=F7=F8=F9=FA=FB=FC=FD=FE=FF"
                    )
            );

        $this->assertSame(
                "Testing   \r\nThis",
                $encode->decode("Testing  =20\r\nThis")
            );
    }

}

?>