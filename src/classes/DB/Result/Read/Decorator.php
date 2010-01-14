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
 * @package Database
 */

namespace r8\DB\Result\Read;

/**
 * Helper base class for Read decorators. It provides a full set of transmogrifiers
 */
abstract class Decorator implements \r8\iface\DB\Result\Read
{

    /**
     * The Read result being decorated
     *
     * @var \r8\iface\DB\Result\Read
     */
    private $decorated;

    /**
     * Constructor...
     *
     * @param \r8\iface\DB\Result\Read $decorated The Read result being decorated
     */
    public function __construct ( \r8\iface\DB\Result\Read $decorated )
    {
        $this->decorated = $decorated;
    }

    /**
     * Returns the Decorated read result
     *
     * @return \r8\iface\DB\Result\Read
     */
    public function getDecorated ()
    {
        return $this->decorated;
    }

    /**
     * Returns whether this instance currently holds a valid resource
     *
     * @return Boolean
     */
    public function hasResult ()
    {
        return $this->decorated->hasResult();
    }

    /**
     * Returns the number of rows affected by a query
     *
     * This also provides the functionality to access the number of rows in this
     * result set via the "count" method
     *
     * @return Integer
     */
    public function count ()
    {
        return $this->decorated->count();
    }

    /**
     * Returns a list of field names returned by the query
     *
     * @return Array
     */
    public function getFields ()
    {
        return $this->decorated->getFields();
    }

    /**
     * Returns whether a field exists in the results
     *
     * @param String $field The case-sensitive field name
     * @return Boolean
     */
    public function isField ( $field )
    {
        return $this->decorated->isField( $field );
    }

    /**
     * Returns the number of fields in the result set
     *
     * @return Integer
     */
    public function fieldCount ()
    {
        return $this->decorated->fieldCount();
    }

    /**
     * Returns the value of the current row
     *
     * Iterator interface function
     *
     * @return mixed Returns the current Row, or FALSE if the iteration has
     *      reached the end of the row list
     */
    public function current ()
    {
        return $this->decorated->current();
    }

    /**
     * Returns the whether the current row is valid
     *
     * Iterator interface function
     *
     * @return Boolean
     */
    public function valid ()
    {
        return $this->decorated->valid();
    }

    /**
     * Increments to the next result row
     *
     * Iterator interface function
     *
     * @return \r8\DB\Result\Read\Decorator Returns a self reference
     */
    public function next ()
    {
        $this->decorated->next();
        return $this;
    }

    /**
     * Returns the offset of the current result row
     *
     * Iterator interface function
     *
     * @return Integer
     */
    public function key ()
    {
        return $this->decorated->key();
    }

    /**
     * Resets the result iterator to the beginning
     *
     * Iterator interface function
     *
     * @return \r8\DB\Result\Read\Decorator Returns a self reference
     */
    public function rewind ()
    {
        $this->decorated->rewind();
        return $this;
    }

    /**
     * Sets the internal result pointer to a given offset
     *
     * SeekableIterator interface function
     *
     * @param Integer $offset The offset to seek to
     * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
     * @return \r8\DB\Result\Read\Decorator Returns a self reference
     */
    public function seek ( $offset, $wrapFlag = \r8\num\OFFSET_RESTRICT )
    {
        $this->decorated->seek( $offset, $wrapFlag );
        return $this;
    }

    /**
     * Frees the resource in this instance
     *
     * @return \r8\DB\Result\Read\Decorator Returns a self reference
     */
    public function free ()
    {
        $this->decorated->free();
        return $this;
    }

    /**
     * Returns the query used to generate this result
     *
     * @return String
     */
    public function getQuery ()
    {
        return $this->decorated->getQuery();
    }

}

?>