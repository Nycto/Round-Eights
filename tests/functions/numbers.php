<?php
/**
 * Unit Test File
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package UnitTests
 */

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * numeric function unit tests
 */
class functions_numbers extends PHPUnit_Framework_TestCase
{

    function testPositive ()
    {
        $this->assertTrue( \h2o\num\positive(1) );
        $this->assertTrue( \h2o\num\positive(.1) );

        $this->assertFalse( \h2o\num\positive(-1) );
        $this->assertFalse( \h2o\num\positive(-.1) );

        $this->assertFalse( \h2o\num\positive(0) );
    }

    function testNegative ()
    {
        $this->assertFalse( \h2o\num\negative(1) );
        $this->assertFalse( \h2o\num\negative(.1) );

        $this->assertTrue( \h2o\num\negative(-1) );
        $this->assertTrue( \h2o\num\negative(-.1) );

        $this->assertFalse( \h2o\num\negative(0) );
    }

    function testNegate ()
    {
        $this->assertEquals( -1, \h2o\num\negate(1) );
        $this->assertEquals( -1.5, \h2o\num\negate(1.5) );
        $this->assertEquals( -10000000, \h2o\num\negate(10000000) );
        $this->assertEquals( -10000000.5, \h2o\num\negate(10000000.5) );

        $this->assertEquals( 1, \h2o\num\negate(-1) );
        $this->assertEquals( 1.5, \h2o\num\negate(-1.5) );
        $this->assertEquals( 10000000, \h2o\num\negate(-10000000) );
        $this->assertEquals( 10000000.5, \h2o\num\negate(-10000000.5) );

        $this->assertEquals( 0, \h2o\num\negate(0) );
    }

    function testBetween ()
    {
        $this->assertTrue( \h2o\num\between( 8, 4, 10 ) );
        $this->assertTrue( \h2o\num\between( 8, 4.5, 10.5 ) );
        $this->assertTrue( \h2o\num\between( 8.5, 4, 10.5 ) );

        $this->assertFalse( \h2o\num\between( 2, 4, 10 ) );
        $this->assertFalse( \h2o\num\between( 2, 4, 10.5 ) );
        $this->assertFalse( \h2o\num\between( 2.5, 4, 10 ) );

        $this->assertFalse( \h2o\num\between( 12, 4, 10 ) );
        $this->assertFalse( \h2o\num\between( 12.5, 4.5, 10 ) );
        $this->assertFalse( \h2o\num\between( 12, 4.5, 10 ) );

        $this->assertTrue( \h2o\num\between( 10, 4, 10 ) );
        $this->assertTrue( \h2o\num\between( 4, 4, 10 ) );
        $this->assertFalse( \h2o\num\between( 10, 4, 10, FALSE ) );
        $this->assertFalse( \h2o\num\between( 10, 4, 10, FALSE ) );

        $this->assertTrue( \h2o\num\between( 10.5, 4.5, 10.5 ) );
        $this->assertTrue( \h2o\num\between( 4.5, 4.5, 10.5 ) );
        $this->assertFalse( \h2o\num\between( 10.5, 4.5, 10.5, FALSE ) );
        $this->assertFalse( \h2o\num\between( 10.5, 4.5, 10.5, FALSE ) );
    }

    function testLimit ()
    {
        $this->assertEquals( 8, \h2o\num\limit(8, 4, 10) );
        $this->assertEquals( 4, \h2o\num\limit(2, 4, 10) );
        $this->assertEquals( 10, \h2o\num\limit(12, 4, 10) );

        $this->assertEquals( 8.5, \h2o\num\limit(8.5, 4.5, 10.5) );
        $this->assertEquals( 4.5, \h2o\num\limit(2, 4.5, 10.5) );
        $this->assertEquals( 10.5, \h2o\num\limit(12, 4.5, 10.5) );
    }

    function testIntWrap ()
    {
        $this->assertEquals( 15, \h2o\num\intWrap( 37, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\intWrap( 26, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\intWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\intWrap( 4, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\intWrap( -7, 10, 20 ) );

        $this->assertEquals( 10, \h2o\num\intWrap( -1, 10, 20 ) );
        $this->assertEquals( 10, \h2o\num\intWrap( 10, 10, 20 ) );
        $this->assertEquals( 20, \h2o\num\intWrap( 20, 10, 20 ) );
        $this->assertEquals( 20, \h2o\num\intWrap( 31, 10, 20 ) );
    }

    function testNumWrap ()
    {
        $this->assertEquals( 15, \h2o\num\numWrap( 35, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\numWrap( 25, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\numWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\numWrap( 5, 10, 20 ) );
        $this->assertEquals( 15, \h2o\num\numWrap( -5, 10, 20 ) );

        $this->assertEquals( 10, \h2o\num\numWrap( 10, 10, 20 ) );
        $this->assertEquals( 10, \h2o\num\numWrap( 20, 10, 20 ) );

        $this->assertEquals( 20, \h2o\num\numWrap( 10, 10, 20, FALSE ) );
        $this->assertEquals( 20, \h2o\num\numWrap( 20, 10, 20, FALSE ) );
    }

    function testOffsetWrap ()
    {
        try {
            \h2o\num\offsetWrap(5, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Argument $err ) {
            $this->assertSame( "Invalid offset wrap flag", $err->getMessage() );
        }

        try {
            \h2o\num\offsetWrap(0, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame( "List is empty", $err->getMessage() );
        }

        $this->assertEquals(0, \h2o\num\offsetWrap(5, -5, \h2o\num\OFFSET_NONE) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, -2, \h2o\num\OFFSET_NONE) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, -1, \h2o\num\OFFSET_NONE) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, 0, \h2o\num\OFFSET_NONE) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, 3, \h2o\num\OFFSET_NONE) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 4, \h2o\num\OFFSET_NONE) );

        try {
            \h2o\num\offsetWrap(1, 2, \h2o\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \h2o\num\offsetWrap(5, 5, \h2o\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \h2o\num\offsetWrap(5, -6, \h2o\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \h2o\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, \h2o\num\offsetWrap(5, -14, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(2, \h2o\num\offsetWrap(5, -8, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, -5, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, -2, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, -1, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, 0, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, 3, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 4, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, 8, \h2o\num\OFFSET_WRAP) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, 15, \h2o\num\OFFSET_WRAP) );

        $this->assertEquals(0, \h2o\num\offsetWrap(5, -14, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, -8, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, -5, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, -2, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, -1, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, 0, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, 3, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 4, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 8, \h2o\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 15, \h2o\num\OFFSET_RESTRICT) );

        $this->assertEquals(0, \h2o\num\offsetWrap(5, -2, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, -1, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \h2o\num\offsetWrap(5, 0, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(3, \h2o\num\offsetWrap(5, 3, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 4, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 8, \h2o\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \h2o\num\offsetWrap(5, 15, \h2o\num\OFFSET_LIMIT) );

    }

}

?>