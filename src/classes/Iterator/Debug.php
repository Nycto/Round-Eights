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
 * Wraps another iterator and provides debug info about the iteration process
 *
 * The hash that is displayed at the start of each line is the object hash of
 * the iterator. Outputting this allows for differentiation between multiple
 * debug iterators running at the same time
 */
class Debug extends \IteratorIterator
{

    /**
     * Dumps the starting call to a function
     *
     * @param String $method The method that was called
     * @return NULL
     */
    private function dumpStart ( $method )
    {
        printf(
            "\n%s::%-8s Start\n",
            spl_object_hash($this),
            $method
        );
    }

    /**
     * Dumps the ending call to a function
     *
     * @param String $method The method that was called
     * @param Mixed $result The result from the method call
     * @return NULL
     */
    private function dumpEnd ( $method, $result )
    {
        printf(
            "%s::%-8s End    %s\n",
            spl_object_hash($this),
            $method,
            \r8\getDump($result)
        );
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        $this->dumpStart( __FUNCTION__ );
        $result = parent::key();
        $this->dumpEnd( __FUNCTION__, $result );
        return $result;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        $this->dumpStart( __FUNCTION__ );
        $result = parent::current();
        $this->dumpEnd( __FUNCTION__, $result );
        return $result;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        $this->dumpStart( __FUNCTION__ );
        $result = parent::next();
        $this->dumpEnd( __FUNCTION__, $result );
        return $result;
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        $this->dumpStart( __FUNCTION__ );
        $result = parent::valid();
        $this->dumpEnd( __FUNCTION__, $result );
        return $result;
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->dumpStart( __FUNCTION__ );
        $result = parent::rewind();
        $this->dumpEnd( __FUNCTION__, $result );
        return $result;
    }

}

?>