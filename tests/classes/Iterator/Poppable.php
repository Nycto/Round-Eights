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
class classes_Iterator_Poppable extends PHPUnit_Framework_TestCase
{

    public function testPop ()
    {
        $pop = new \r8\Iterator\Poppable(
            new \ArrayIterator(array( "one", "two", "three" ))
        );

        $this->assertSame( "one", $pop->pop() );
        $this->assertSame( "two", $pop->pop() );
        $this->assertSame( "three", $pop->pop() );
        $this->assertNull( $pop->pop() );
        $this->assertNull( $pop->pop() );

        $this->assertSame( $pop, $pop->rewind() );
        $this->assertSame( "one", $pop->pop() );
        $this->assertSame( "two", $pop->pop() );
        $this->assertSame( "three", $pop->pop() );
        $this->assertNull( $pop->pop() );
        $this->assertNull( $pop->pop() );
    }

    public function testSerialize ()
    {
        $pop = new \r8\Iterator\Poppable(
            new \ArrayIterator(array( 1, 2, 4, 8 ))
        );

        $this->assertSame( 1, $pop->pop() );
        $this->assertSame( 2, $pop->pop() );
        $this->assertSame( 4, $pop->pop() );

        $unser = unserialize( serialize($pop) );
        $this->assertSame( 1, $unser->pop() );
        $this->assertSame( 2, $unser->pop() );
        $this->assertSame( 4, $unser->pop() );
        $this->assertSame( 8, $unser->pop() );
        $this->assertNull( $unser->pop() );
    }

    public function testIterator ()
    {
        \r8\Test\Constraint\Iterator::assert(
            array( 1, 2, 4, 8 ),
            new \r8\Iterator\Poppable(
                new \ArrayIterator(array( 1, 2, 4, 8 ))
            )
        );
    }

}

?>