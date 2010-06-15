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
 * Provides an interface for easily popping values off an iterator
 */
class Poppable implements \IteratorAggregate
{

    /**
     * The iterator being wrapped
     *
     * @var \Iterator
     */
    private $inner;

    /**
     * Returns whether this iterator has been rewound
     *
     * @var Boolean
     */
    private $rewound = FALSE;

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
     * Callback for when this object is serialized
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array( "inner" );
    }

    /**
     * Callback for when this object is unserialized
     *
     * @return Array
     */
    public function __wakeup ()
    {
        $this->rewound = FALSE;
    }

    /**
     * Rewinds the iterator within this instance
     *
     * @return \r8\Iterator\Poppable Returns a self reference
     */
    public function rewind ()
    {
        $this->rewound = FALSE;
        return $this;
    }

    /**
     * Pops the next value off of this iterator
     *
     * @return Mixed
     */
    public function pop ()
    {
        if ( !$this->rewound ) {
            $this->inner->rewind();
            $this->rewound = TRUE;
        }
        else {
            $this->inner->next();
        }

        return $this->inner->valid() ? $this->inner->current() : NULL;
    }

    /**
     * Provides Iterator functionality for this iterator
     *
     * @return \Iterator
     */
    public function getIterator ()
    {
        return $this->inner;
    }

}

?>