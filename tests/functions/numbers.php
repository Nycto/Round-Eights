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

require_once rtrim( __DIR__, "/" ) ."/../general.php";

/**
 * numeric function unit tests
 */
class functions_numbers extends PHPUnit_Framework_TestCase
{

    function testPositive ()
    {
        $this->assertTrue( \cPHP\num\positive(1) );
        $this->assertTrue( \cPHP\num\positive(.1) );

        $this->assertFalse( \cPHP\num\positive(-1) );
        $this->assertFalse( \cPHP\num\positive(-.1) );

        $this->assertFalse( \cPHP\num\positive(0) );
    }

    function testNegative ()
    {
        $this->assertFalse( \cPHP\num\negative(1) );
        $this->assertFalse( \cPHP\num\negative(.1) );

        $this->assertTrue( \cPHP\num\negative(-1) );
        $this->assertTrue( \cPHP\num\negative(-.1) );

        $this->assertFalse( \cPHP\num\negative(0) );
    }

    function testNegate ()
    {
        $this->assertEquals( -1, \cPHP\num\negate(1) );
        $this->assertEquals( -1.5, \cPHP\num\negate(1.5) );
        $this->assertEquals( -10000000, \cPHP\num\negate(10000000) );
        $this->assertEquals( -10000000.5, \cPHP\num\negate(10000000.5) );

        $this->assertEquals( 1, \cPHP\num\negate(-1) );
        $this->assertEquals( 1.5, \cPHP\num\negate(-1.5) );
        $this->assertEquals( 10000000, \cPHP\num\negate(-10000000) );
        $this->assertEquals( 10000000.5, \cPHP\num\negate(-10000000.5) );

        $this->assertEquals( 0, \cPHP\num\negate(0) );
    }

    function testBetween ()
    {
        $this->assertTrue( \cPHP\num\between( 8, 4, 10 ) );
        $this->assertTrue( \cPHP\num\between( 8, 4.5, 10.5 ) );
        $this->assertTrue( \cPHP\num\between( 8.5, 4, 10.5 ) );

        $this->assertFalse( \cPHP\num\between( 2, 4, 10 ) );
        $this->assertFalse( \cPHP\num\between( 2, 4, 10.5 ) );
        $this->assertFalse( \cPHP\num\between( 2.5, 4, 10 ) );

        $this->assertFalse( \cPHP\num\between( 12, 4, 10 ) );
        $this->assertFalse( \cPHP\num\between( 12.5, 4.5, 10 ) );
        $this->assertFalse( \cPHP\num\between( 12, 4.5, 10 ) );

        $this->assertTrue( \cPHP\num\between( 10, 4, 10 ) );
        $this->assertTrue( \cPHP\num\between( 4, 4, 10 ) );
        $this->assertFalse( \cPHP\num\between( 10, 4, 10, FALSE ) );
        $this->assertFalse( \cPHP\num\between( 10, 4, 10, FALSE ) );

        $this->assertTrue( \cPHP\num\between( 10.5, 4.5, 10.5 ) );
        $this->assertTrue( \cPHP\num\between( 4.5, 4.5, 10.5 ) );
        $this->assertFalse( \cPHP\num\between( 10.5, 4.5, 10.5, FALSE ) );
        $this->assertFalse( \cPHP\num\between( 10.5, 4.5, 10.5, FALSE ) );
    }

    function testLimit ()
    {
        $this->assertEquals( 8, \cPHP\num\limit(8, 4, 10) );
        $this->assertEquals( 4, \cPHP\num\limit(2, 4, 10) );
        $this->assertEquals( 10, \cPHP\num\limit(12, 4, 10) );

        $this->assertEquals( 8.5, \cPHP\num\limit(8.5, 4.5, 10.5) );
        $this->assertEquals( 4.5, \cPHP\num\limit(2, 4.5, 10.5) );
        $this->assertEquals( 10.5, \cPHP\num\limit(12, 4.5, 10.5) );
    }

    function testIntWrap ()
    {
        $this->assertEquals( 15, \cPHP\num\intWrap( 37, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\intWrap( 26, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\intWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\intWrap( 4, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\intWrap( -7, 10, 20 ) );

        $this->assertEquals( 10, \cPHP\num\intWrap( -1, 10, 20 ) );
        $this->assertEquals( 10, \cPHP\num\intWrap( 10, 10, 20 ) );
        $this->assertEquals( 20, \cPHP\num\intWrap( 20, 10, 20 ) );
        $this->assertEquals( 20, \cPHP\num\intWrap( 31, 10, 20 ) );
    }

    function testNumWrap ()
    {
        $this->assertEquals( 15, \cPHP\num\numWrap( 35, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\numWrap( 25, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\numWrap( 15, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\numWrap( 5, 10, 20 ) );
        $this->assertEquals( 15, \cPHP\num\numWrap( -5, 10, 20 ) );

        $this->assertEquals( 10, \cPHP\num\numWrap( 10, 10, 20 ) );
        $this->assertEquals( 10, \cPHP\num\numWrap( 20, 10, 20 ) );

        $this->assertEquals( 20, \cPHP\num\numWrap( 10, 10, 20, FALSE ) );
        $this->assertEquals( 20, \cPHP\num\numWrap( 20, 10, 20, FALSE ) );
    }

    function testOffsetWrap ()
    {
        try {
            \cPHP\num\offsetWrap(5, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Argument $err ) {
            $this->assertSame( "Invalid offset wrap flag", $err->getMessage() );
        }

        try {
            \cPHP\num\offsetWrap(0, 2, "invalid offset value");
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "List is empty", $err->getMessage() );
        }

        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -5, \cPHP\num\OFFSET_NONE) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, -2, \cPHP\num\OFFSET_NONE) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, -1, \cPHP\num\OFFSET_NONE) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, 0, \cPHP\num\OFFSET_NONE) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, 3, \cPHP\num\OFFSET_NONE) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 4, \cPHP\num\OFFSET_NONE) );

        try {
            \cPHP\num\offsetWrap(1, 2, \cPHP\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \cPHP\num\offsetWrap(5, 5, \cPHP\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }

        try {
            \cPHP\num\offsetWrap(5, -6, \cPHP\num\OFFSET_NONE);
            $this->fail('An expected exception has not been raised.');
        }
        catch ( \cPHP\Exception\Index $err ) {
            $this->assertSame( "Offset is out of bounds", $err->getMessage() );
        }


        $this->assertEquals(1, \cPHP\num\offsetWrap(5, -14, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(2, \cPHP\num\offsetWrap(5, -8, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -5, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, -2, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, -1, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, 0, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, 3, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 4, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, 8, \cPHP\num\OFFSET_WRAP) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, 15, \cPHP\num\OFFSET_WRAP) );

        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -14, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -8, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -5, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, -2, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, -1, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, 0, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, 3, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 4, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 8, \cPHP\num\OFFSET_RESTRICT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 15, \cPHP\num\OFFSET_RESTRICT) );

        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -2, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, -1, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(0, \cPHP\num\offsetWrap(5, 0, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(3, \cPHP\num\offsetWrap(5, 3, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 4, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 8, \cPHP\num\OFFSET_LIMIT) );
        $this->assertEquals(4, \cPHP\num\offsetWrap(5, 15, \cPHP\num\OFFSET_LIMIT) );

    }

}

?>