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
     * Constructor...
     *
     * @param \Traversable $iterator The iterator being wrapped
     */
    public function __construct ( \Traversable $iterator )
    {
        parent::__construct( $iterator );
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        echo spl_object_hash($this) ."::". __FUNCTION__ ." Start\n";
        $result = parent::key();
        echo spl_object_hash($this) ."::". __FUNCTION__ ." End: ". \r8\getDump($result) ."\n";
        return $result;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        echo spl_object_hash($this) ."::". __FUNCTION__ ." Start\n";
        $result = parent::current();
        echo spl_object_hash($this) ."::". __FUNCTION__ ." End: ". \r8\getDump($result) ."\n";
        return $result;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        echo spl_object_hash($this) ."::". __FUNCTION__ ." Start\n";
        $result = parent::next();
        echo spl_object_hash($this) ."::". __FUNCTION__ ." End: ". \r8\getDump($result) ."\n";
        return $result;
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        echo spl_object_hash($this) ."::". __FUNCTION__ ." Start\n";
        $result = parent::valid();
        echo spl_object_hash($this) ."::". __FUNCTION__ ." End: ". \r8\getDump($result) ."\n";
        return $result;
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        echo spl_object_hash($this) ."::". __FUNCTION__ ." Start\n";
        $result = parent::rewind();
        echo spl_object_hash($this) ."::". __FUNCTION__ ." End: ". \r8\getDump($result) ."\n";
        return $result;
    }

}

?>