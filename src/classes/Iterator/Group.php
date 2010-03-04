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
     * Constructor...
     *
     * @param String $field The inner field to group the results by
     * @param \Iterator $iterator The inner iterator being wrapped
     */
    public function __construct ( $field, \Iterator $inner )
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
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {

    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {

    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {

    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {

    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {

    }

}

?>