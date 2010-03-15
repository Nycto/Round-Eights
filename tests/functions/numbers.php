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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * numeric function unit tests
 */
class functions_numbers extends PHPUnit_Framework_TestCase
{

    function testPositive ()
    {
        $this->assertTrue( \r8\num\positive(1) );
        $this->assertTrue( \r8\num\positive(.1) );

        $this->assertFalse( \r8\num\positive(-1) );
        $this->assertFalse( \r8\num\positive(-.1) );

        $this->assertFalse( \r8\num\positive(0) );
    }

    function testNegative ()
    {
        $this->assertFalse( \r8\num\negative(1) );
        $this->assertFalse( \r8\num\negative(.1) );

        $this->assertTrue( \r8\num\negative(-1) );
        $this->assertTrue( \r8\num\negative(-.1) );

        $this->assertFalse( \r8\num\negative(0) );
    }

    function testNegate ()
    {
        $this->assertEquals( -1, \r8\num\negate(1) );
        $this->assertEquals( -1.5, \r8\num\negate(1.5) );
        $this->assertEquals( -10000000, \r8\num\negate(10000000) );
        $this->assertEquals( -10000000.5, \r8\num\negate(10000000.5) );

        $this->assertEquals( 1, \r8\num\negate(-1) );
        $this->assertEquals( 1.5, \r8\num\negate(-1.5) );
        $this->assertEquals( 10000000, \r8\num\negate(-10000000) );
        $this->assertEquals( 10000000.5, \r8\num\negate(-10000000.5) );

        $this->assertEquals( 0, \r8\num\negate(0) );
    }

    function testBetween ()
    {
        $this->assertTrue( \r8\num\between( 8, 4, 10 ) );
        $this->assertTrue( \r8\num\between( 8, 4.5, 10.5 ) );
        $this->assertTrue( \r8\num\between( 8.5, 4, 10.5 ) );

        $this->assertFalse( \r8\num\between( 2, 4, 10 ) );
        $this->assertFalse( \r8\num\between( 2, 4, 10.5 ) );
        $this->assertFalse( \r8\num\between( 2.5, 4, 10 ) );

        $this->assertFalse( \r8\num\between( 12, 4, 10 ) );
        $this->assertFalse( \r8\num\between( 12.5, 4.5, 10 ) );
        $this->assertFalse( \r8\num\between( 12, 4.5, 10 ) );

        $this->assertTrue( \r8\num\between( 10, 4, 10 ) );
        $this->assertTrue( \r8\num\between( 4, 4, 10 ) );
        $this->assertFalse( \r8\num\between( 10, 4, 10, FALSE ) );
        $this->assertFalse( \r8\num\between( 10, 4, 10, FALSE ) );

        $this->assertTrue( \r8\num\between( 10.5, 4.5, 10.5 ) );
        $this->assertTrue( \r8\num\between( 4.5, 4.5, 10.5 ) );
        $this->assertFalse( \r8\num\between( 10.5, 4.5, 10.5, FALSE ) );
        $this->assertFalse( \r8\num\between( 10.5, 4.5, 10.5, FALSE ) );
    }

    function testLimit ()
    {
        $this->assertEquals( 8, \r8\num\limit(8, 4, 10) );
        $this->assertEquals( 4, \r8\num\limit(2, 4, 10) );
        $this->assertEquals( 10, \r8\num\limit(12, 4, 10) );

        $this->assertEquals( 8.5, \r8\num\limit(8.5, 4.5, 10.5) );
        $this->assertEquals( 4.5, \r8\num\limit(2, 4.5, 10.5) );
        $this->assertEquals( 10.5, \r8\num\limit(12, 4.5, 10.5) );
    }

    function testIntWrap ()
    {
        $this->assertEquals( 15, \r8\num\intWrap( 37, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\intWrap( 26, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\intWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\intWrap( 4, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\intWrap( -7, 10, 20 ) );

        $this->assertEquals( 10, \r8\num\intWrap( -1, 10, 20 ) );
        $this->assertEquals( 10, \r8\num\intWrap( 10, 10, 20 ) );
        $this->assertEquals( 20, \r8\num\intWrap( 20, 10, 20 ) );
        $this->assertEquals( 20, \r8\num\intWrap( 31, 10, 20 ) );
    }

    function testNumWrap ()
    {
        $this->assertEquals( 15, \r8\num\numWrap( 35, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\numWrap( 25, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\numWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\numWrap( 5, 10, 20 ) );
        $this->assertEquals( 15, \r8\num\numWrap( -5, 10, 20 ) );

        $this->assertEquals( 10, \r8\num\numWrap( 10, 10, 20 ) );
        $this->assertEquals( 10, \r8\num\numWrap( 20, 10, 20 ) );

        $this->assertEquals( 20, \r8\num\numWrap( 10, 10, 20, FALSE ) );
        $this->assertEquals( 20, \r8\num\numWrap( 20, 10, 20, FALSE ) );
    }

    function testOffsetWrap ()
    {
        try {
            \r8\num\offsetWrap(5, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Argument $err ) {
            $this->assertSame( "Invalid offset wrap flag", $err->getMessage() );
        }

        try {
            \r8\num\offsetWrap(0, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Index $err ) {
            $this->assertSame( "List is empty", $err->getMessage() );
        }

        $this->assertEquals(0, \r8\num\offsetWrap(5, -5, \r8\num\OFFSET_NONE) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, -2, \r8\num\OFFSET_NONE) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, -1, \r8\num\OFFSET_NONE) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, 0, \r8\num\OFFSET_NONE) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, 3, \r8\num\OFFSET_NONE) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 4, \r8\num\OFFSET_NONE) );

        try {
            \r8\num\offsetWrap(1, 2, \r8\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \r8\num\offsetWrap(5, 5, \r8\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \r8\num\offsetWrap(5, -6, \r8\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \r8\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, \r8\num\offsetWrap(5, -14, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(2, \r8\num\offsetWrap(5, -8, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, -5, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, -2, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, -1, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, 0, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, 3, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 4, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, 8, \r8\num\OFFSET_WRAP) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, 15, \r8\num\OFFSET_WRAP) );

        $this->assertEquals(0, \r8\num\offsetWrap(5, -14, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, -8, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, -5, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, -2, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, -1, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, 0, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, 3, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 4, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 8, \r8\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 15, \r8\num\OFFSET_RESTRICT) );

        $this->assertEquals(0, \r8\num\offsetWrap(5, -2, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, -1, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \r8\num\offsetWrap(5, 0, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(3, \r8\num\offsetWrap(5, 3, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 4, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 8, \r8\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \r8\num\offsetWrap(5, 15, \r8\num\OFFSET_LIMIT) );
    }

    public function testIntHash ()
    {
        $this->assertSame( 1945669750, \r8\num\intHash("Data") );
        $this->assertSame( 1595238312, \r8\num\intHash(3.1415) );
        $this->assertSame( 821412000, \r8\num\intHash(11235813) );
    }

}

?>