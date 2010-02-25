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