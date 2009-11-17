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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Transform_Ascii85 extends PHPUnit_Framework_TestCase
{

    public function testTo_Basic ()
    {
        $encode = new \r8\Transform\Ascii85;

        $this->assertSame( "<~0`~>", $encode->to( "1" ) );
        $this->assertSame( "<~0er~>", $encode->to( "12" ) );
        $this->assertSame( "<~0etN~>", $encode->to( "123" ) );
        $this->assertSame( "<~0etOA~>", $encode->to( "1234" ) );
        $this->assertSame( "<~0etOA2#~>", $encode->to( "12345" ) );
        $this->assertSame( "<~0etOA2)Y~>", $encode->to( "123456" ) );
        $this->assertSame( "<~0etOA2)[A~>", $encode->to( "1234567" ) );
        $this->assertSame( "<~0etOA2)[BQ~>", $encode->to( "12345678" ) );

        $this->assertSame(
            "<~<+oue+DGm>@3BZ'F*&OCAftM)Ci=3(ATAo7FE2)5B-;;7+D#G#De*D~>",
            $encode->to( "This is a test of a longer string to encode" )
        );
    }

    public function testTo_UpperEdge ()
    {
        $encode = new \r8\Transform\Ascii85;

        $this->assertSame(
            "<~s8W-!~>",
            $encode->to(
                chr( pow(2, 32) - 1 )
                .chr( pow(2, 32) - 1 )
                .chr( pow(2, 32) - 1 )
                .chr( pow(2, 32) - 1 )
            )
        );
    }

    public function testTo_ZCompress ()
    {
        $encode = new \r8\Transform\Ascii85;

        $this->assertSame(
            "<~z~>",
            $encode->to( chr( 0 ).chr( 0 ).chr( 0 ).chr( 0 ) )
        );

        $this->assertSame(
            '<~87c4?z<+0KW~>',
            $encode->to(
                "Head"
                .chr( 0 ).chr( 0 ).chr( 0 ).chr( 0 )
                ."Tail"
            )
        );
    }

    public function testTo_YCompress ()
    {
        $encode = new \r8\Transform\Ascii85;

        $this->assertSame( "<~y~>", $encode->to( "    " ) );

        $this->assertSame(
            "<~87c4?y<+0KW~>",
            $encode->to( "Head    Tail" )
        );
    }

    public function testTo_DisableCompression ()
    {
        $encode = new \r8\Transform\Ascii85( FALSE );

        $this->assertSame( "<~+<VdL~>", $encode->to( "    " ) );

        $this->assertSame(
            "<~!!!!!~>",
            $encode->to( chr( 0 ).chr( 0 ).chr( 0 ).chr( 0 ) )
        );

        $this->assertSame(
            "<~87c4?!!!!!@VfId+<VdL<+0KW~>",
            $encode->to(
            	"Head"
            	.chr( 0 ).chr( 0 ).chr( 0 ).chr( 0 )
            	."blah"
            	."    "
                ."Tail"
            )
        );
    }

    public function testTo_DisableWrap ()
    {
        $encode = new \r8\Transform\Ascii85( TRUE, FALSE );

        $this->assertSame(
            "<+oue+DGm>@3BZ'F*&OCAftM)Ci=3(ATAo7FE2)5B-;;7+D#G#De*D",
            $encode->to( "This is a test of a longer string to encode" )
        );
    }

    public function testFrom ()
    {
        $this->markTestIncomplete("To be written");
    }

}

?>