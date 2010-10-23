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
 * Provides a buffer between a wrapped iterator and other code further
 * down the stream that will cache the results of the "next", "current"
 * and "valid" methods to prevent them from being called multiple
 * times between "rewind" and "next" calls.
 */
class Debounce implements \OuterIterator
{

    /**
     * The inner iterator being wrapped
     *
     * @var Iterator
     */
    private $inner;

    /**
     * Returns whether the current value has been fetched
     *
     * @var Boolean
     */
    private $hasCurrent = FALSE;

    /**
     * The current value of the iterator
     *
     * @var Mixed
     */
    private $current;

    /**
     * Returns whether the current key has been fetched
     *
     * @var Boolean
     */
    private $hasKey = FALSE;

    /**
     * The current value of the key
     *
     * @var Mixed
     */
    private $key;

    /**
     * Whether the current value is considered valid
     *
     * @var Boolean
     */
    private $valid;

    /**
     * Constructor...
     *
     * @param \Iterator $iterator The inner iterator being wrapped
     */
    public function __construct ( \Iterator $inner )
    {
        $this->inner = $inner;
    }

    /**
     * Returns the internal iterator
     *
     * @return Iterator
     */
    public function getInnerIterator ()
    {
        return $this->inner;
    }

    /**
     * Clears all the cached values
     *
     * @return NULL
     */
    private function clear ()
    {
        $this->current = NULL;
        $this->key = NULL;
        $this->valid = NULL;
        $this->hasCurrent = FALSE;
        $this->hasKey = FALSE;
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        if ( !$this->hasKey )
        {
            $this->key = $this->inner->key();
            $this->hasKey = TRUE;
        }
        return $this->key;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        if ( !$this->hasCurrent )
        {
            $this->current = $this->inner->current();
            $this->hasCurrent = TRUE;
        }
        return $this->current;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        $this->inner->next();
        $this->clear();
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        if ( $this->valid === NULL )
            $this->valid = (bool) $this->inner->valid();

        return $this->valid;
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->inner->rewind();
        $this->clear();
    }

    /**
     * Provides a list of inner values that should be serialized
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array("inner");
    }

}

