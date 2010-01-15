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

namespace r8\DB\Result;

/**
 * Database Read Query Results
 */
class Read extends \r8\DB\Result\Base implements \r8\iface\DB\Result\Read
{

    /**
     * The query result adapter that provides a standard way to interface with
     * the results
     *
     * @var \r8\iface\DB\Adapter\Result
     */
    private $adapter;

    /**
     * The number of rows returned by this query
     *
     * @var Integer
     */
    private $count;

    /**
     * The list of fields in the result set
     *
     * @var Array
     */
    private $fields;

    /**
     * The current offset of the result set
     *
     * @var Integer
     */
    private $pointer;

    /**
     * The value of the current row
     *
     * @var Array
     */
    private $row;

    /**
     * Constructor...
     *
     * @param String $query The query that produced this result
     * @param \r8\iface\DB\Adapter\Result $adapter The query result adapter that
     *      provides a standard way to interface with the results
     */
    public function __construct ( \r8\iface\DB\Adapter\Result $adapter, $query )
    {
        parent::__construct($query);
        $this->adapter = $adapter;
    }

    /**
     * Destructor...
     *
     * Ensures that the resource is freed
     */
    public function __destruct()
    {
        $this->free();
    }

    /**
     * Returns the Adapter wrapped inside this result
     *
     * @return \r8\iface\DB\Adapter\Result Returns NULL if the result has been freed
     */
    public function getAdapter ()
    {
        return isset( $this->adapter ) ? $this->adapter : NULL;
    }

    /**
     * Returns whether this instance currently holds a valid resource
     *
     * @return Boolean
     */
    public function hasResult ()
    {
        return isset( $this->adapter );
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
        if ( !isset($this->count) ) {

            if ( isset($this->adapter) )
                $this->count = max( (int) $this->adapter->count(), 0 );
            else
                $this->count = 0;

        }

        return $this->count;
    }

    /**
     * Returns a list of field names returned by the query
     *
     * @return Array
     */
    public function getFields ()
    {
        if ( !isset($this->adapter) )
            return array();

        if ( !isset($this->fields) )
            $this->fields = (array) $this->adapter->getFields();

        return $this->fields;
    }

    /**
     * Returns whether a field exists in the results
     *
     * @param String $field The case-sensitive field name
     * @return Boolean
     */
    public function isField ( $field )
    {
        return in_array( (string) $field, $this->getFields() );
    }

    /**
     * Returns the number of fields in the result set
     *
     * @return Integer
     */
    public function fieldCount ()
    {
        return count( $this->getFields() );
    }

    /**
     * Returns the value of the current row
     *
     * Iterator interface function
     *
     * @return mixed Returns the current Row, or NULL if the iteration has
     *      reached the end of the row list
     */
    public function current ()
    {
        if ( !isset($this->adapter) )
            return NULL;

        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();

        return $this->row;
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
        if ( !isset($this->adapter) )
            return FALSE;

        $count = $this->count();

        if ( $count == 0 )
            return FALSE;

        return $this->pointer < $count ? TRUE : FALSE;
    }

    /**
     * Increments to the next result row
     *
     * Iterator interface function
     *
     * @return \r8\DB\Result\Read Returns a self reference
     */
    public function next ()
    {
        if ( !isset($this->adapter) )
            return $this;

        // If the pointer isn't set yet, start it at 0
        if ( !isset($this->pointer) )
            $this->pointer = 0;

        // Don't increment beyond the count
        else if ( $this->pointer < $this->count() )
            $this->pointer++;

        // If there are still rows to fetch, grab the next one
        if ( $this->pointer < $this->count() )
            $this->row = (array) $this->adapter->fetch();
        else
            $this->row = FALSE;

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
        if ( !isset($this->adapter) )
            return NULL;

        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();

        // The key is simply the internal row pointer
        return $this->pointer;
    }

    /**
     * Resets the result iterator to the beginning
     *
     * Iterator interface function
     *
     * @return \r8\DB\Result\Read Returns a self reference
     */
    public function rewind ()
    {
        if ( isset($this->adapter) ) {

            // If the pointer hasn't been initialized at all, then we just need to fetch the first row
            if ( !isset($this->pointer) )
                $this->next();

            // If the pointer is already at zero, we don't need to do anything
            else if ( $this->pointer > 0 )
                $this->seek(0);
        }

        return $this;
    }

    /**
     * Sets the internal result pointer to a given offset
     *
     * SeekableIterator interface function
     *
     * @param Integer $offset The offset to seek to
     * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
     * @return \r8\DB\Result\Read Returns a self reference
     */
    public function seek ( $offset, $wrapFlag = \r8\num\OFFSET_RESTRICT )
    {
        if ( !isset($this->adapter) )
            return $this;

        $offset = \r8\num\offsetWrap(
                $this->count(),
                $offset,
                $wrapFlag
            );

        if ( $offset !== FALSE && $this->pointer !== $offset ) {
            $this->pointer = $offset;
            $this->adapter->seek( $offset );
            $this->row = (array) $this->adapter->fetch();
        }

        return $this;
    }

    /**
     * Frees the resource in this instance
     *
     * @return \r8\DB\Result\Read Returns a self reference
     */
    public function free ()
    {
        if ( $this->hasResult() ) {
            $this->adapter->free();
            unset(
                $this->adapter,
                $this->pointer,
                $this->row
            );
        }
        return $this;
    }

}

?>