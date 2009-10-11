<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Iterator
 */

namespace h2o\Iterator;

/**
 * Converts a DOMNodeList into an iterator
 */
class DOMNodeList implements \Iterator, \Countable
{

    /**
     * The Node List being iterated over
     *
     * @var DOMNodeList
     */
    private $nodelist;

    /**
     * The current offset of the iteration
     *
     * @var Integer
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param \DOMNodeList $nodelist The NodeList being wrapped
     */
    public function __construct ( \DOMNodeList $nodelist )
    {
        $this->nodelist = $nodelist;
    }

    /**
     * Returns the number of nodes in this list
     *
     * @return Integer
     */
    public function count ()
    {
        return $this->nodelist->length;
    }

    /**
     * Returns a specific item from the list
     *
     * @param Integer $offset The offset of the item to return
     * @return DOMNode
     */
    public function item ( $offset )
    {
        return $this->nodelist->item( $offset );
    }

    /**
     * Returns the current value of the iterator
     *
     * @return DOMNode|NULL Returns NULL if there is no current value
     */
    public function current()
    {
        if ( $this->offset === NULL )
            return NULL;
        else
            return $this->nodelist->item( $this->offset );
    }

    /**
     * Returns the key of the current value
     *
     * @return Integer
     */
    public function key ()
    {
        return $this->offset;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return \h2o\Iterator\DOMNodeList Returns a self reference
     */
    public function next ()
    {
        if ( $this->offset + 1 < $this->nodelist->length )
            $this->offset++;
        else
            $this->offset = NULL;

        return $this;
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return $this->offset !== NULL;
    }

    /**
     * Restarts the iterator
     *
     * @return \h2o\Iterator\Stream\Tokenize Returns a self reference
     */
    public function rewind ()
    {
        if ( $this->nodelist->length > 0 )
            $this->offset = 0;
        return $this;
    }

}

?>