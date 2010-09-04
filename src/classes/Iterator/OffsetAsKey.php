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
 * Forces the keys of an iterator to be it's offset relative to the start
 * of the iteration
 */
class OffsetAsKey implements \OuterIterator
{

    /**
     * The iterator being wrapped
     *
     * @var \Iterator
     */
    private $inner;

    /**
     * The current offset
     *
     * @var Integer
     */
    private $offset = 0;

    /**
     * Constructor...
     *
     * @param \Iterator $inner The iterator being wrapped
     */
    public function __construct ( \Iterator $inner )
    {
        $this->inner = $inner;
    }

    /**
     * Returns the Inner Iterator this iterator wraps
     *
     * @return \Iterator
     */
    public function getInnerIterator ()
    {
        return $this->inner;
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->inner->rewind();
        $this->offset = 0;
    }

    /**
     * Returns whether the current iterator offset is valid
     *
     * @return Boolean
     */
    function valid()
    {
        return $this->inner->valid();
    }

    /**
     * Returns the current value from the iterator
     *
     * @return Mixed
     */
    function current()
    {
        return $this->inner->current();
    }

    /**
     * Returns the key of the current offset
     *
     * @return Mixed
     */
    function key()
    {
        return $this->offset;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    function next()
    {
        $this->inner->next();
        $this->offset++;
    }

}

?>