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
 * Groups the values in this iterator by a plucked value
 *
 * The inner iterator must already be sorted by the grouping field.
 */
class Group implements \OuterIterator
{

    /**
     * The inner field to group the results by
     *
     * @var String
     */
    private $field;

    /**
     * The inner iterator being wrapped
     *
     * @var Iterator
     */
    private $inner;

    /**
     * The current value being grouped by
     *
     * @var Mixed
     */
    private $key;

    /**
     * The result of the grouping
     *
     * @var Array
     */
    private $current;

    /**
     * Constructor...
     *
     * @param String $field The inner field to group the results by
     * @param \Iterator $iterator The inner iterator being wrapped
     */
    public function __construct ( $field, \Iterator $inner )
    {
        $this->field = \r8\indexVal( $field );
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
     * Internal method for creating the next group
     *
     * @return NULL
     */
    private function createGroup ()
    {
        $this->key = NULL;

        if ( !$this->inner->valid() ) {
            $this->current = NULL;
            return;
        }

        $this->current = array();

        do {

            $current = $this->inner->current();
            $key = NULL;

            // Extract the group by field from the current value
            if ( is_array($current) && isset($current[ $this->field ]) )
                $key = $current[ $this->field ];
            else if ( is_object($current) && isset($current->{$this->field}) )
                $key = $current->{$this->field};

            // If a key was found, add it to the current group until it changes
            if ( isset($key) ) {

                if ( !isset( $this->key ) )
                    $this->key = $key;

                if ( $key != $this->key )
                    break;

                $this->current[] = $current;
            }

            $this->inner->next();

        } while ( $this->inner->valid() );

        // We can get here if they gave us a grouping field that didn't exist
        // in any of the iterator values
        if ( empty($this->current) )
            $this->current = NULL;
        else
            $this->current = new \ArrayIterator( $this->current );
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        return $this->key;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        $this->createGroup();
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return !empty( $this->current );
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->inner->rewind();
        $this->createGroup();
    }

}

?>