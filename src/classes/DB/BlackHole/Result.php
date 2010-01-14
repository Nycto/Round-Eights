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

namespace r8\DB\BlackHole;

/**
 * BlackHole Database results
 */
class Result implements \r8\iface\DB\Adapter\Result
{

    /**
     * The list of results to return
     *
     * @var Array
     */
    private $results = array();
    
    /**
     * Constructor...
     *
     * @param Array $row... Any rows to add to this result set
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            foreach ( $args AS $row ) {
                $this->addRow( (array) $row );
            }
        }
    }
    
    /**
     * Returns all the rows loaded in this result
     *
     * @return Array
     */
    public function getAllRows ()
    {
        return $this->results;
    }
    
    /**
     * Adds a new row to this result
     *
     * @param Array $row The row of data to add
     * @return \r8\DB\BlackHole\Read Returns a self reference
     */
    public function addRow ( array $row )
    {
        $this->results[] = $row;
        return $this;
    }

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    public function count ()
    {
        return count( $this->results );
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return array()
     */
    public function getFields ()
    {
        if ( empty($this->results) )
            return array();

        return array_keys( $this->results[0] );
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    public function fetch ()
    {
        $current = current( $this->results );

        if ( !$current )
            return NULL;

        next($this->results);

        return $current;
    }

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return NULL
     */
    public function seek ($offset)
    {
        \r8\ary\seek( $this->results, $offset );
    }

    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    public function free ()
    {
        $this->results = array();
    }

}

?>