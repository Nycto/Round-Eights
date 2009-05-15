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
class classes_random_cmwc extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        if ( !extension_loaded('bcmath') )
            $this->markTestSkipped("BC Math extension is not loaded");
    }

    public function testNextInteger ()
    {
        $seed = $this->getMock('cPHP\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \cPHP\Random\CMWC( $seed );

        $this->assertSame( 186686213, $random->nextInteger() );
        $this->assertSame( 654266915, $random->nextInteger() );
        $this->assertSame( 713522459, $random->nextInteger() );
        $this->assertSame( 1776606471, $random->nextInteger() );
        $this->assertSame( 780261361, $random->nextInteger() );
        $this->assertSame( 1499779575, $random->nextInteger() );
        $this->assertSame( 1254026890, $random->nextInteger() );
        $this->assertSame( 1663567669, $random->nextInteger() );
        $this->assertSame( 1117038192, $random->nextInteger() );
        $this->assertSame( 2015626809, $random->nextInteger() );
    }

    public function testNextFloat ()
    {
        $seed = $this->getMock('cPHP\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \cPHP\Random\CMWC( $seed );

        $this->assertSame( 0.08693254230867, $random->nextFloat() );
        $this->assertSame( 0.30466677402364, $random->nextFloat() );
        $this->assertSame( 0.33225978693564, $random->nextFloat() );
        $this->assertSame( 0.82729685671036, $random->nextFloat() );
        $this->assertSame( 0.36333750996894, $random->nextFloat() );
        $this->assertSame( 0.69838928789757, $random->nextFloat() );
        $this->assertSame( 0.58395177618785, $random->nextFloat() );
        $this->assertSame( 0.7746590626308, $random->nextFloat() );
        $this->assertSame( 0.52016144270085, $random->nextFloat() );
        $this->assertSame( 0.938599375048, $random->nextFloat() );
    }

    public function testNextString ()
    {
        $seed = $this->getMock('cPHP\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \cPHP\Random\CMWC( $seed );

        $this->assertSame( "04dd7aa1d307ff02ee6498eba252e959b3936c29", $random->nextString() );
        $this->assertSame( "0e007079b76d2b526f9774746fb845d0ced6919c", $random->nextString() );
        $this->assertSame( "19fa78329d9de26ce17a653e0e26483759dba0fb", $random->nextString() );
        $this->assertSame( "fe61b51907b890aa9e07f46bfe0a0f20b2dd057d", $random->nextString() );
        $this->assertSame( "2d96cb5646b5a8a369de7568d088558bedfd678e", $random->nextString() );
        $this->assertSame( "e1149fefaa4e5ca2ad03d73d12e1dc5fd39b47ae", $random->nextString() );
        $this->assertSame( "a22fad0102bff19eebd53a28ef977474244f067c", $random->nextString() );
        $this->assertSame( "5b23e29ae71199a8ca66ff3849c9fa01ea64f194", $random->nextString() );
        $this->assertSame( "fdaeb56bf19e8e8c4c2a1d5264e7427b4f761e8c", $random->nextString() );
        $this->assertSame( "7074797ac31e7d28f43ed1d12b1f969c255e2873", $random->nextString() );
    }

}

?>