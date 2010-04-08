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

require_once rtrim( __DIR__, "/" ) ."/../../../general.php";

/**
 * unit tests
 */
class classes_iterator_stream_tokenize extends PHPUnit_Framework_TestCase
{

    public function testIterate ()
    {
        $stream = new \r8\Stream\In\String("String\nTo\nSplit");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "\n" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );
    }

    public function testIterate_underRead ()
    {
        $stream = new \r8\Stream\In\String("String\nTo\nSplit", 3);

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "\n" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

    }

    public function testLongDelim ()
    {
        $stream = new \r8\Stream\In\String("StringBREAKToBREAKSplit");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "BREAK" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );
    }

    public function testCaseSensitivity ()
    {
        $stream = new \r8\Stream\In\String("StringBREAKbreakBREAKSplit");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "BREAK" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "break", "Split"),
                $iter
            );
    }

    public function testTrailingDelim ()
    {
        $stream = new \r8\Stream\In\String("String\nTo\nSplit\n\n");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "\n" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );


        $stream = new \r8\Stream\In\String("StringBRBR");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "BR" );

        \r8\Test\Constraint\Iterator::assert(
                array("String"),
                $iter
            );
    }

    public function testLeadingDelim ()
    {
        $stream = new \r8\Stream\In\String("\n\nString\nTo\nSplit");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "\n" );

        \r8\Test\Constraint\Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );


        $stream = new \r8\Stream\In\String("BRBRString");

        $iter = new \r8\Iterator\Stream\Tokenize( $stream, "BR" );

        \r8\Test\Constraint\Iterator::assert(
                array("String"),
                $iter
            );
    }

}

?>