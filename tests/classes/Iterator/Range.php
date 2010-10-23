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

require_once rtrim( __DIR__, "/" ) ."/../../general.php";

/**
 * unit tests
 */
class classes_Iterator_Range extends PHPUnit_Framework_TestCase
{

    public function testNum2Alpha ()
    {
        $this->assertSame( "A", \r8\Iterator\Range::num2alpha(0) );
        $this->assertSame( "B", \r8\Iterator\Range::num2alpha(1) );
        $this->assertSame( "Y", \r8\Iterator\Range::num2alpha(24) );
        $this->assertSame( "Z", \r8\Iterator\Range::num2alpha(25) );
        $this->assertSame( "AA", \r8\Iterator\Range::num2alpha(26) );
        $this->assertSame( "FZPI", \r8\Iterator\Range::num2alpha(123456) );
        $this->assertSame( "FZPJ", \r8\Iterator\Range::num2alpha(123457) );
    }

    public function testAlpha2Num ()
    {
        $this->assertEquals( 0, \r8\Iterator\Range::alpha2num("A") );
        $this->assertEquals( 1, \r8\Iterator\Range::alpha2num("B") );
        $this->assertEquals( 24, \r8\Iterator\Range::alpha2num("Y") );
        $this->assertEquals( 25, \r8\Iterator\Range::alpha2num("Z") );
        $this->assertEquals( 26, \r8\Iterator\Range::alpha2num("AA") );
        $this->assertEquals( 123456, \r8\Iterator\Range::alpha2num("FZPI") );
        $this->assertEquals( 123457, \r8\Iterator\Range::alpha2num("FZPJ") );

        $this->assertEquals( 0, \r8\Iterator\Range::alpha2num("a") );
        $this->assertEquals( 1, \r8\Iterator\Range::alpha2num("b") );
        $this->assertEquals( 24, \r8\Iterator\Range::alpha2num("y") );
        $this->assertEquals( 25, \r8\Iterator\Range::alpha2num("z") );
        $this->assertEquals( 26, \r8\Iterator\Range::alpha2num("aa") );
    }

    public function testIteration_Integers ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            new \r8\Iterator\Range(1, 10)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1),
            new \r8\Iterator\Range(10, 1)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(1, 4, 7, 10),
            new \r8\Iterator\Range(1, 10, 3)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(10, 7, 4, 1),
            new \r8\Iterator\Range(10, 1, 3)
        );
    }

    public function testIteration_Floats ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array(1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5),
            new \r8\Iterator\Range(1.5, 10)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(10.5, 9.5, 8.5, 7.5, 6.5, 5.5, 4.5, 3.5, 2.5, 1.5),
            new \r8\Iterator\Range(10.5, 1)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(1.5, 4.5, 7.5),
            new \r8\Iterator\Range(1.5, 10, 3)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(10.5, 7.5, 4.5, 1.5),
            new \r8\Iterator\Range(10.5, 1, 3)
        );
    }

    public function testIteration_lowercase ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("a", "b", "c", "d", "e", "f", "g"),
            new \r8\Iterator\Range("a", "g")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("g", "f", "e", "d", "c", "b", "a"),
            new \r8\Iterator\Range("g", "a")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("a", "d", "g"),
            new \r8\Iterator\Range("a", "g", 3)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("g", "d", "a"),
            new \r8\Iterator\Range("g", "a", 3)
        );
    }

    public function testIteration_uppercase ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("A", "B", "C", "D", "E", "F", "G"),
            new \r8\Iterator\Range("A", "G")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("G", "F", "E", "D", "C", "B", "A"),
            new \r8\Iterator\Range("G", "A")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("A", "D", "G"),
            new \r8\Iterator\Range("A", "G", 3)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("G", "D", "A"),
            new \r8\Iterator\Range("G", "A", 3)
        );
    }

    public function testIteration_ZeroStep ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array(1, 2, 3, 4, 5),
            new \r8\Iterator\Range(1, 5, 0)
        );
    }

    public function testIteration_lowerLong ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("ax", "ay", "az", "ba", "bb", "bc"),
            new \r8\Iterator\Range("ax", "bc")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("bc", "bb", "ba", "az", "ay", "ax"),
            new \r8\Iterator\Range("bc", "ax")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("ax", "az", "bb", "bd"),
            new \r8\Iterator\Range("ax", "bd", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("bd", "bb", "az", "ax"),
            new \r8\Iterator\Range("bd", "ax", 2)
        );
    }

    public function testIteration_upperLong ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("AX", "AY", "AZ", "BA", "BB", "BC"),
            new \r8\Iterator\Range("AX", "BC")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("BC", "BB", "BA", "AZ", "AY", "AX"),
            new \r8\Iterator\Range("BC", "AX")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("AX", "AZ", "BB", "BD"),
            new \r8\Iterator\Range("AX", "BD", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("BD", "BB", "AZ", "AX"),
            new \r8\Iterator\Range("BD", "AX", 2)
        );
    }

    public function testIteration_lowerShortToLong ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("x", "y", "z", "aa", "ab", "ac"),
            new \r8\Iterator\Range("x", "ac")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("ac", "ab", "aa", "z", "y", "x"),
            new \r8\Iterator\Range("ac", "x")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("x", "z", "ab", "ad"),
            new \r8\Iterator\Range("x", "ad", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("ad", "ab", "z", "x"),
            new \r8\Iterator\Range("ad", "x", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("zx", "zy", "zz", "aaa", "aab", "aac"),
            new \r8\Iterator\Range("zx", "aac")
        );
    }

    public function testIteration_upperShortToLong ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("X", "Y", "Z", "AA", "AB", "AC"),
            new \r8\Iterator\Range("X", "AC")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("AC", "AB", "AA", "Z", "Y", "X"),
            new \r8\Iterator\Range("AC", "X")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("X", "Z", "AB", "AD"),
            new \r8\Iterator\Range("X", "AD", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("AD", "AB", "Z", "X"),
            new \r8\Iterator\Range("AD", "X", 2)
        );

        \r8\Test\Constraint\Iterator::assert(
            array("ZX", "ZY", "ZZ", "AAA", "AAB", "AAC"),
            new \r8\Iterator\Range("ZX", "AAC")
        );
    }

    public function testIteration_mixedCase ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array("c", "d", "e"),
            new \r8\Iterator\Range("c", "E")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("C", "D", "E"),
            new \r8\Iterator\Range("C", "e")
        );

        \r8\Test\Constraint\Iterator::assert(
            array("ca", "cb", "cc", "cd"),
            new \r8\Iterator\Range("cA", "CD")
        );
    }

    public function testIteration_mixedMode ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array(0, 1, 2, 3, 4),
            new \r8\Iterator\Range("c", 4)
        );

        \r8\Test\Constraint\Iterator::assert(
            array(4, 3, 2, 1, 0),
            new \r8\Iterator\Range(4, "c")
        );
    }

    public function testSleep ()
    {
        $iterator = new \r8\Iterator\Range(1, 5, 2);

        \r8\Test\Constraint\Iterator::assert(
            array(1, 3, 5),
            unserialize( serialize( $iterator ) )
        );
    }

}

