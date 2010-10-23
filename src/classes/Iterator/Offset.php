<?php
/**
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
 * @package Iterator
 */

namespace r8\Iterator;

/**
 * Starts the iteration of an internal iterator at the given offset
 */
class Offset implements \Iterator
{

    /**
     * The iterator being wrapped
     *
     * @var \Traversable
     */
    private $inner;

    /**
     * The offset at which to start iteration
     *
     * @var Integer
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param Integer $offset The offset at which to start iteration
     * @param \Traversable $inner The iterator being wrapped
     */
    public function __construct ( $offset, \Traversable $inner )
    {
        $this->inner = $inner;

        $offset = (int) $offset;

        // If we can, be intelligent about the offset
        if ( $inner instanceof \Countable ) {
            try {
                $offset = \r8\num\offsetWrap(
                    $inner->count(),
                    $offset,
                    \r8\num\OFFSET_NONE
                );
            }
            catch ( \r8\Exception\Index $err ) {
                $offset = NULL;
            }
        }

        // If they gave us an offset relative to the end, we absolutely need a count
        else if ( $offset < 0 ) {
            $err = new \r8\Exception\Index(
                $offset,
                "Iterator Offset",
                "Negative offsets are only supported if Iterator implements the Countable interface"
            );

            $err->addData( "Iterator", \r8\getDump($inner) );

            throw $err;
        }

        $this->offset = $offset;
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        if ( !isset($this->offset) )
            return NULL;

        $this->inner->rewind();

        if ( $this->offset == 0 )
            return NULL;

        // If they gave us a seekable iterator, make use of it
        if ( $this->inner instanceof \SeekableIterator ) {
            try {
                $this->inner->seek( $this->offset );
            }
            catch ( \OutOfBoundsException $err ) {}
        }

        // Otherwise, manually increment
        else {
            for ( $i = 0; $i < $this->offset && $this->valid(); $i++ ) {
                $this->next();
            }
        }
    }

    /**
     * Returns whether the current iterator offset is valid
     *
     * @return Boolean
     */
    function valid()
    {
        return isset($this->offset) && $this->inner->valid();
    }

    /**
     * Returns the current value from the iterator
     *
     * @return Mixed
     */
    function current()
    {
        return isset($this->offset) ? $this->inner->current() : NULL;
    }

    /**
     * Returns the key of the current offset
     *
     * @return Mixed
     */
    function key()
    {
        return isset($this->offset) ? $this->inner->key() : NULL;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    function next()
    {
        if ( isset($this->offset) )
            $this->inner->next();
    }

}

