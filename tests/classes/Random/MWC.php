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
class classes_random_mwc extends PHPUnit_Framework_TestCase
{

    public function setUp ()
    {
        if ( !extension_loaded('bcmath') )
            $this->markTestSkipped("BC Math extension is not loaded");
    }

    public function testNextInteger ()
    {
        $seed = $this->getMock('h2o\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \h2o\Random\MWC( $seed );

        $this->assertSame( 1990708657, $random->nextInteger() );
        $this->assertSame( 1965874365, $random->nextInteger() );
        $this->assertSame( 2089735730, $random->nextInteger() );
        $this->assertSame( 72218845, $random->nextInteger() );
        $this->assertSame( 612520310, $random->nextInteger() );
        $this->assertSame( 431925995, $random->nextInteger() );
        $this->assertSame( 1092442608, $random->nextInteger() );
        $this->assertSame( 979484250, $random->nextInteger() );
        $this->assertSame( 1119315088, $random->nextInteger() );
        $this->assertSame( 1980790387, $random->nextInteger() );
    }

    public function testNextFloat ()
    {
        $seed = $this->getMock('h2o\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \h2o\Random\MWC( $seed );

        $this->assertSame( 0.92699595630495, $random->nextFloat() );
        $this->assertSame( 0.91543158791747, $random->nextFloat() );
        $this->assertSame( 0.97310903061792, $random->nextFloat() );
        $this->assertSame( 0.03362952034624, $random->nextFloat() );
        $this->assertSame( 0.28522699618956, $random->nextFloat() );
        $this->assertSame( 0.20113121494704, $random->nextFloat() );
        $this->assertSame( 0.50870823138799, $random->nextFloat() );
        $this->assertSame( 0.45610789696505, $random->nextFloat() );
        $this->assertSame( 0.52122170502377, $random->nextFloat() );
        $this->assertSame( 0.92237740192673, $random->nextFloat() );
    }

    public function testNextString ()
    {
        $seed = $this->getMock('h2o\Random\Seed', array('getInteger'), array(1234));
        $seed->expects( $this->once() )
            ->method('getInteger')
            ->will( $this->returnValue(1975807251) );

        $random = new \h2o\Random\MWC( $seed );

        $this->assertSame( "518bda762d1c91d4008162dbd65c9b22e5f14fc3", $random->nextString() );
        $this->assertSame( "21c4278ba2f3bbff94e6cf5ce58380b7880679dc", $random->nextString() );
        $this->assertSame( "bd870e5571632e0bcac488f31f105121bd7fa65b", $random->nextString() );
        $this->assertSame( "9d50bd7de6d4724876a56fcc2a731d3365649386", $random->nextString() );
        $this->assertSame( "9721c1e6fe1b63e92ae1c6f79fa988ac5351a549", $random->nextString() );
        $this->assertSame( "0e76b16362e44ee2ea01d4b48671bd0328d550b3", $random->nextString() );
        $this->assertSame( "2505cee2c1347bf8ea2425899bb76de8f93a0982", $random->nextString() );
        $this->assertSame( "0b49a03cfb006a4e6da1dc62298c0dd12c4183fb", $random->nextString() );
        $this->assertSame( "3cd0ea82971b8f0375489fdf37e612d6ba224680", $random->nextString() );
        $this->assertSame( "2865b7c0fd44c79a75f2d5dfacc52b2c2ccbb295", $random->nextString() );
    }

}

?>