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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
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
        $stream = new \h2o\Stream\In\String("String\nTo\nSplit");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "\n" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );
    }

    public function testIterate_underRead ()
    {
        $stream = new \h2o\Stream\In\String("String\nTo\nSplit", 3);

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "\n" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );

    }

    public function testLongDelim ()
    {
        $stream = new \h2o\Stream\In\String("StringBREAKToBREAKSplit");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "BREAK" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );
    }

    public function testCaseSensitivity ()
    {
        $stream = new \h2o\Stream\In\String("StringBREAKbreakBREAKSplit");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "BREAK" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "break", "Split"),
                $iter
            );
    }

    public function testTrailingDelim ()
    {
        $stream = new \h2o\Stream\In\String("String\nTo\nSplit\n\n");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "\n" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );


        $stream = new \h2o\Stream\In\String("StringBRBR");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "BR" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String"),
                $iter
            );
    }

    public function testLeadingDelim ()
    {
        $stream = new \h2o\Stream\In\String("\n\nString\nTo\nSplit");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "\n" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String", "To", "Split"),
                $iter
            );


        $stream = new \h2o\Stream\In\String("BRBRString");

        $iter = new \h2o\Iterator\Stream\Tokenize( $stream, "BR" );

        PHPUnit_Framework_Constraint_Iterator::assert(
                array("String"),
                $iter
            );
    }

}

?>