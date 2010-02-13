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
 * Holds a callback that supplies an iterator and defers calling it until a value
 * is actually requested
 */
class Defer implements \Iterator
{

    /**
     * The callback that will return an iterator
     *
     * @var \r8\Curry\Unbound
     */
    private $callback;

    /**
     * The internal iterator
     *
     * @var \Iterator
     */
    private $iterator;

    /**
     * Constructor...
     *
     * @param \r8\Curry\Unbound $callback The callback that will return an iterator
     */
    public function __construct ( \r8\Curry\Unbound $callback )
    {
        $this->callback = $callback;
    }

    /**
     * Resolves the value of the Iterator from the callback
     *
     * @return \Iterator
     */
    public function getInnerIterator ()
    {
        if ( !isset($this->iterator) ) {

            $value = $this->callback->exec();
            unset( $this->callback );

            if ( !($value instanceof \Iterator) )
                $value = new \ArrayIterator( (array) $value );

            $this->iterator = $value;
        }

        return $this->iterator;
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        return $this->iterator->key();
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        return $this->iterator->current();
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        return $this->iterator->next();
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        return $this->iterator->valid();
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        $this->iterator->rewind();
    }

    /**
     * Handle serialization
     *
     * @return array Returns the fields that should be serialized
     */
    public function __sleep ()
    {
        if ( !isset($this->iterator) )
            $this->getInnerIterator();

        return array('iterator');
    }

}

?>