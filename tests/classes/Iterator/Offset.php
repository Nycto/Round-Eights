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
class classes_Iterator_Offset extends PHPUnit_Framework_TestCase
{

    public function testConstruct_UncountableNegative ()
    {
        $iterator = $this->getMock('Iterator');
        try {
            new \r8\Iterator\Offset( -1, $iterator );
            $this->fail("An expected exception was not thrown");
        }
        catch ( \r8\Exception\Index $err ) {
            $this->assertSame(
                "Negative offsets are only supported if Iterator implements the Countable interface",
                $err->getMessage()
            );
        }
    }

    public function testSeekable_Empty ()
    {
        $offset = new \r8\Iterator\Offset( 10, new \ArrayIterator( array() ) );

        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
    }

    public function testIterate_Seekable_FromZero ()
    {
        $offset = new \r8\Iterator\Offset( 0, new \ArrayIterator( array(1, 2, 3) ) );

        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
    }

    public function testIterate_Seekable_WithOffset ()
    {
        $offset = new \r8\Iterator\Offset(
            2,
            new \ArrayIterator( range("a", "e") )
        );

        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
    }

    public function testIterate_Seekable_OutOfBounds ()
    {
        $offset = new \r8\Iterator\Offset(
            50,
            new \ArrayIterator( range("a", "e") )
        );

        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
    }

    public function testIterate_Seekable_Negative ()
    {
        $offset = new \r8\Iterator\Offset(
            -2,
            new \ArrayIterator( range("a", "e") )
        );

        \r8\Test\Constraint\Iterator::assert( array(3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(3 => 'd', 4 => 'e'), $offset );
    }

    public function testIterate_Unseekable_Empty ()
    {
        $offset = new \r8\Iterator\Offset(
            10,
            new IteratorIterator( new \ArrayIterator( array() ) )
        );

        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
    }

    public function testIterate_Unseekable_FromZero ()
    {
        $offset = new \r8\Iterator\Offset( 0, new \ArrayIterator( array(1, 2, 3) ) );

        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
        \r8\Test\Constraint\Iterator::assert( array(1, 2, 3), $offset );
    }

    public function testIterate_Unseekable_WithOffset ()
    {
        $offset = new \r8\Iterator\Offset(
            2,
            new IteratorIterator( new \ArrayIterator( range("a", "e") ) )
        );

        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
        \r8\Test\Constraint\Iterator::assert( array(2 => 'c', 3 => 'd', 4 => 'e'), $offset );
    }

    public function testIterate_Unseekable_OutOfBounds ()
    {
        $offset = new \r8\Iterator\Offset(
            50,
            new IteratorIterator( new \ArrayIterator( range("a", "e") ) )
        );

        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
        \r8\Test\Constraint\Iterator::assert( array(), $offset );
    }

}

?>