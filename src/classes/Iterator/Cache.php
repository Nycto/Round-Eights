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
 * Wraps another iterator and guarentees that it will only be iterated over once.
 * The results are internally cached and any further iterations will read from
 * the values returned by the first iteration
 */
class Cache implements \Iterator
{

    /**
     * The internal iterator
     *
     * @var Iterator
     */
    private $iterator;

    /**
     * The offset of the internal iterator
     *
     * This is used when iteration only partially completes before the iterator
     * is rewound.
     *
     * @var Integer
     */
    private $internalOffset;

    /**
     * The values cached from the first iteration
     *
     * @var Array
     */
    private $cache = array();

    /**
     * The current offset of the external facing iteration
     *
     * @var Integer
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param \Iterator $iterator The iterator being wrapped
     */
    public function __construct ( \Iterator $iterator )
    {
        $this->iterator = $iterator;
    }

    /**
     * Internally increments the iterator and saves the value
     *
     * @return Boolean Returns whether the iterator returned a valid value
     */
    private function storeNext ()
    {
        if ( !isset($this->iterator) ) {
            return FALSE;
        }

        else if ( $this->iterator->valid() ) {
            $this->cache[ $this->internalOffset ] = array(
                $this->iterator->key(),
                $this->iterator->current()
            );
            return TRUE;
        }

        // Once the internal iterator is invalid, we no longer need it
        else {
            unset( $this->iterator );
            return FALSE;
        }
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        return $this->valid() ? $this->cache[ $this->offset ][ 0 ] : NULL;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        return $this->valid() ? $this->cache[ $this->offset ][ 1 ] : NULL;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        if ( isset($this->iterator) && $this->offset == $this->internalOffset ) {
            $this->internalOffset++;
            $this->iterator->next();
            $this->storeNext();
        }

        $this->offset++;
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return isset($this->offset) && isset($this->cache[ $this->offset ]);
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->offset = 0;

        // Only rewind the internal iterator if this is the first rewind
        if ( isset($this->iterator) && !isset($this->internalOffset) ) {
            $this->internalOffset = 0;
            $this->iterator->rewind();
            $this->storeNext();
        }
    }

    /**
     * Pulls all the remaining values from the internal iterator and stores them
     * in the cache
     *
     * @return \r8\Iterator\Cache
     */
    public function fillCache ()
    {
        if ( !isset($this->internalOffset) ) {
            $this->internalOffset = 0;
            $this->iterator->rewind();
        }

        while ( $this->storeNext() ) {
            $this->iterator->next();
            $this->internalOffset++;
        }

        return $this;
    }

    /**
     * Handle serialization
     *
     * @return array Returns the fields that should be serialized
     */
    public function __sleep ()
    {
        $this->fillCache();
        return array('offset', 'cache');
    }

}

?>