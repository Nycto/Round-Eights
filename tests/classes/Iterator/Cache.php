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
 * A stub iterator that only allows itself to be rewound once
 */
class stub_Iterator_Cache_NoRewind extends IteratorIterator
{

    /**
     * Whether the iterator has been rewound
     *
     * @var Boolean
     */
    private $rewound = FALSE;

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        if ( !$this->rewound ) {
            $this->rewound = TRUE;
            return parent::rewind();
        }
        else {
            throw new PHPUnit_Framework_ExpectationFailedException(
                "Iterator has already been rewound"
            );
        }
    }

}

/**
 * unit tests
 */
class classes_Iterator_Cache extends PHPUnit_Framework_TestCase
{

    /**
     * Returns a mock iterator that will throw an exception if it is rewound
     * more than once
     *
     * @return Iterator
     */
    public function getMockIterator ( $data )
    {
        if ( is_array($data) )
            $data = new ArrayIterator($data);

        $iterator = new stub_Iterator_Cache_NoRewind( $data );

        return $iterator;
    }

    public function testIterate ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testIterate_Restart ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        $iterator->rewind();
        $iterator->next();

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testIterate_Empty ()
    {
        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( array() ) );

        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
        \r8\Test\Constraint\Iterator::assert( array(), $iterator );
    }

    public function testIterate_KeyConflict ()
    {
        $data = new \AppendIterator;
        $data->append( new ArrayIterator( array( "key" => "first" ) ) );
        $data->append( new ArrayIterator( array( "key" => "second" ) ) );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        $iterator->rewind();
        $this->assertSame( 'key', $iterator->key() );
        $this->assertSame( 'first', $iterator->current() );
        $iterator->next();
        $this->assertSame( 'key', $iterator->key() );
        $this->assertSame( 'second', $iterator->current() );

        $iterator->rewind();
        $this->assertSame( 'key', $iterator->key() );
        $this->assertSame( 'first', $iterator->current() );
        $iterator->next();
        $this->assertSame( 'key', $iterator->key() );
        $this->assertSame( 'second', $iterator->current() );
    }

    public function testFillCache ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        $this->assertSame( $iterator, $iterator->fillCache() );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }


    public function testFillCache_Partial ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        $iterator->rewind();
        $iterator->next();

        $this->assertSame( $iterator, $iterator->fillCache() );

        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
        \r8\Test\Constraint\Iterator::assert( $data, $iterator );
    }

    public function testSleep ()
    {
        $data = array( 50 => "blah", 100 => "blip", 150 => "bloop" );

        $iterator = new \r8\Iterator\Cache( $this->getMockIterator( $data ) );

        $serialized = serialize( $iterator );

        // Ensure that the cache is what is serialized, not the internal iterator
        $this->assertSame(
            'O:17:"r8\\Iterator\\Cache":2:{s:25:"' . "\0" . 'r8\\Iterator\\Cache'
                ."\0" . 'offset";N;s:24:"' . "\0" . 'r8\\Iterator\\Cache' . "\0"
                .'cache";a:3:{i:0;a:2:{i:0;i:50;i:1;s:4:"blah";}i:1;a:2:{i:0;i:100;i:1;s:4:"blip";}i:2;a:2:{i:0;i:150;i:1;s:5:"bloop";}}}',
            $serialized
        );

        $unserialized = unserialize( $serialized );
        $this->assertThat( $unserialized, $this->isInstanceOf( '\r8\Iterator\Cache' ) );

        \r8\Test\Constraint\Iterator::assert( $data, $unserialized );
        \r8\Test\Constraint\Iterator::assert( $data, $unserialized );
        \r8\Test\Constraint\Iterator::assert( $data, $unserialized );
    }

}

?>