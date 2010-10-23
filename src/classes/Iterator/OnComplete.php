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
 * Invokes a callback when the iterator completes a full iteration
 */
class OnComplete implements \OuterIterator
{

    /**
     * The inner iterator being wrapped
     *
     * @var Iterator
     */
    private $inner;

    /**
     * The callback to invoke
     *
     * @var \r8\Curry\Unbound
     */
    private $callback;

    /**
     * Whether to invoke the callback on the first iteration only
     *
     * @var Boolean
     */
    private $once;

    /**
     * Whether the callback has been invoked
     *
     * @var Boolean
     */
    private $called = FALSE;

    /**
     * Constructor...
     *
     * @param \Iterator $iterator The inner iterator being wrapped
     * @param \r8\Curry\Unbound $callback The callback to invoke when a full
     *       iteration completes
     */
    public function __construct (
        \Iterator $inner,
        \r8\Curry\Unbound $callback,
        $once = TRUE
    ) {
        $this->inner = $inner;
        $this->callback = $callback;
        $this->once = (bool) $once;
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
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        return $this->inner->key();
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        return $this->inner->current();
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        $this->inner->next();
        if ( (!$this->called || !$this->once) && !$this->inner->valid() ) {
            $this->called = TRUE;
            $this->callback->exec();
        }
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return $this->inner->valid();
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->inner->rewind();
        if ( (!$this->called || !$this->once) && !$this->inner->valid() ) {
            $this->called = TRUE;
            $this->callback->exec();
        }
    }

    /**
     * Handle serialization
     *
     * @return array Returns the fields that should be serialized
     */
    public function __sleep ()
    {
        return array('inner', 'callback', 'once');
    }

}

