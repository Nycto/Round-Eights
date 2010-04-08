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
class classes_Iterator_Debounce extends PHPUnit_Framework_TestCase
{

    /**
     * Adds the expectations for a single iteration to a mock iterator
     *
     * @return NULL
     */
    public function addIteration ( $mock, $offset, $key, $value )
    {
        $mock->expects( $this->at( $offset + 0 ) )
            ->method( 'valid' )
            ->will( $this->returnValue(TRUE) );
        $mock->expects( $this->at( $offset + 1 ) )
            ->method( 'current' )
            ->will( $this->returnValue( $value ) );
        $mock->expects( $this->at( $offset + 2 ) )
            ->method( 'key' )
            ->will( $this->returnValue( $key ) );
    }

    public function testGetInnerIterator ()
    {
        $inner = new \EmptyIterator;
        $iterator = new \r8\Iterator\Debounce( $inner );

        $this->assertSame( $inner, $iterator->getInnerIterator() );
    }

    public function testIterate ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Debounce( new ArrayIterator($data) );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testNext ()
    {
        $inner = $this->getMock('Iterator');

        // Set up the first value of the iterator
        $inner->expects( $this->once() )->method( 'rewind' );
        $this->addIteration( $inner, 1, "key1", "value1" );

        // And now the second value of the iterator
        $inner->expects( $this->once() )->method( 'next' );
        $this->addIteration( $inner, 5, "key2", "value2" );


        $iterator = new \r8\Iterator\Debounce( $inner );

        $this->assertNull( $iterator->rewind() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'key1', $iterator->key() );
        $this->assertSame( 'key1', $iterator->key() );
        $this->assertSame( 'key1', $iterator->key() );

        $this->assertNull( $iterator->next() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'key2', $iterator->key() );
        $this->assertSame( 'key2', $iterator->key() );
        $this->assertSame( 'key2', $iterator->key() );
    }

    public function testRewind ()
    {
        $inner = $this->getMock('Iterator');

        $inner->expects( $this->exactly(2) )->method( 'rewind' );
        $this->addIteration( $inner, 1, "key1", "value1" );
        $this->addIteration( $inner, 5, "key2", "value2" );


        $iterator = new \r8\Iterator\Debounce( $inner );

        $this->assertNull( $iterator->rewind() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'value1', $iterator->current() );
        $this->assertSame( 'key1', $iterator->key() );
        $this->assertSame( 'key1', $iterator->key() );
        $this->assertSame( 'key1', $iterator->key() );

        $this->assertNull( $iterator->rewind() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertTrue( $iterator->valid() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'value2', $iterator->current() );
        $this->assertSame( 'key2', $iterator->key() );
        $this->assertSame( 'key2', $iterator->key() );
        $this->assertSame( 'key2', $iterator->key() );
    }

    public function testSerialize ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );
        $iterator = new \r8\Iterator\Debounce( new ArrayIterator($data) );

        $serialized = serialize( $iterator );

        // The serialized string should NOT contain these object properties
        $this->assertNotContains("hasCurrent", $serialized);
        $this->assertNotContains("current", $serialized);
        $this->assertNotContains("hasKey", $serialized);
        $this->assertNotContains("key", $serialized);
        $this->assertNotContains("valid", $serialized);

        $iterator = unserialize( $serialized );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

}

?>