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

    public function testIteration_Integers ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10),
            new \r8\Iterator\Range(1, 10)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(10, 9, 8, 7, 6, 5, 4, 3, 2, 1),
            new \r8\Iterator\Range(10, 1)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1, 4, 7, 10),
            new \r8\Iterator\Range(1, 10, 3)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(10, 7, 4, 1),
            new \r8\Iterator\Range(10, 1, 3)
        );
    }

    public function testIteration_Floats ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1.5, 2.5, 3.5, 4.5, 5.5, 6.5, 7.5, 8.5, 9.5),
            new \r8\Iterator\Range(1.5, 10)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(10.5, 9.5, 8.5, 7.5, 6.5, 5.5, 4.5, 3.5, 2.5, 1.5),
            new \r8\Iterator\Range(10.5, 1)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1.5, 4.5, 7.5),
            new \r8\Iterator\Range(1.5, 10, 3)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(10.5, 7.5, 4.5, 1.5),
            new \r8\Iterator\Range(10.5, 1, 3)
        );
    }

    public function testIteration_lowercase ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("a", "b", "c", "d", "e", "f", "g"),
            new \r8\Iterator\Range("a", "g")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("g", "f", "e", "d", "c", "b", "a"),
            new \r8\Iterator\Range("g", "a")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("a", "d", "g"),
            new \r8\Iterator\Range("a", "g", 3)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("g", "d", "a"),
            new \r8\Iterator\Range("g", "a", 3)
        );
    }

    public function testIteration_uppercase ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("A", "B", "C", "D", "E", "F", "G"),
            new \r8\Iterator\Range("A", "G")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("G", "F", "E", "D", "C", "B", "A"),
            new \r8\Iterator\Range("G", "A")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("A", "D", "G"),
            new \r8\Iterator\Range("A", "G", 3)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("G", "D", "A"),
            new \r8\Iterator\Range("G", "A", 3)
        );
    }

    public function testIteration_ZeroStep ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1, 2, 3, 4, 5),
            new \r8\Iterator\Range(1, 5, 0)
        );
    }

    public function testIteration_lowerLong ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("ax", "ay", "az", "ba", "bb", "bc"),
            new \r8\Iterator\Range("ax", "bc")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("bc", "bb", "ba", "az", "ay", "ax"),
            new \r8\Iterator\Range("bc", "ax")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("ax", "az", "bb", "bd"),
            new \r8\Iterator\Range("ax", "bd", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("bd", "bb", "az", "ax"),
            new \r8\Iterator\Range("bd", "ax", 2)
        );
    }

    public function testIteration_upperLong ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("AX", "AY", "AZ", "BA", "BB", "BC"),
            new \r8\Iterator\Range("AX", "BC")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("BC", "BB", "BA", "AZ", "AY", "AX"),
            new \r8\Iterator\Range("BC", "AX")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("AX", "AZ", "BB", "BD"),
            new \r8\Iterator\Range("AX", "BD", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("BD", "BB", "AZ", "AX"),
            new \r8\Iterator\Range("BD", "AX", 2)
        );
    }

    public function testIteration_lowerShortToLong ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("x", "y", "z", "aa", "ab", "ac"),
            new \r8\Iterator\Range("x", "ac")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("ac", "ab", "aa", "z", "y", "x"),
            new \r8\Iterator\Range("ac", "x")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("x", "z", "ab", "ad"),
            new \r8\Iterator\Range("x", "ad", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("ad", "ab", "z", "x"),
            new \r8\Iterator\Range("ad", "x", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("zx", "zy", "zz", "aaa", "aab", "aac"),
            new \r8\Iterator\Range("xx", "aac")
        );
    }

    public function testIteration_upperShortToLong ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("X", "Y", "Z", "AA", "AB", "AC"),
            new \r8\Iterator\Range("X", "AC")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("AC", "AB", "AA", "Z", "Y", "X"),
            new \r8\Iterator\Range("AC", "X")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("X", "Z", "AB", "AD"),
            new \r8\Iterator\Range("X", "AD", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("AD", "AB", "Z", "X"),
            new \r8\Iterator\Range("AD", "X", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("ZX", "ZY", "ZZ", "AAA", "AAB", "AAC"),
            new \r8\Iterator\Range("XX", "AAC")
        );
    }

    public function testIteration_mixedCase ()
    {
        PHPUnit_Framework_Constraint_Iterator::assert(
            array("x", "y", "z", "A", "B", "C"),
            new \r8\Iterator\Range("x", "C")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("C", "B", "A", "z", "y", "x"),
            new \r8\Iterator\Range("C", "x")
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("x", "z","B", "D"),
            new \r8\Iterator\Range("x", "D", 2)
        );

        PHPUnit_Framework_Constraint_Iterator::assert(
            array("D", "B", "z", "x"),
            new \r8\Iterator\Range("D", "x", 2)
        );
    }

    public function testSleep ()
    {
        $iterator = new \r8\Iterator\Range(1, 5, 2);

        PHPUnit_Framework_Constraint_Iterator::assert(
            array(1, 3, 5),
            unserialize( serialize( $iterator ) )
        );
    }

}

?>