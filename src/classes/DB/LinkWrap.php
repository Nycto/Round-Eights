<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Database
 */

namespace h2o\DB;

/**
 * Base wrapper for increasing the functionality of a database Link
 */
abstract class LinkWrap implements \h2o\iface\DB\Link
{

    /**
     * The Link this decorator wraps around
     */
    private $link;

    /**
     * Constructor...
     *
     * @param Object $link The database Link this instance wraps around
     */
    public function __construct ( \h2o\iface\DB\Link $link )
    {
        $this->link = $link;
    }

    /**
     * Returns the Link this instance wraps
     *
     * @return Object
     */
    public function getLink ()
    {
        return $this->link;
    }

    /**
     * Walks the chain of link wraps until a leaf is found, which is then returned
     *
     * @return Object Returns a \h2o\iface\DB\Link instance
     */
    public function getTopLink ()
    {
        $link = $this->link;

        // walk the chain until we find something that isn't a link wrap
        while ( $link instanceof \h2o\DB\LinkWrap ) {
            $link = $link->getLink();
        }

        return $link;
    }

    /**
     * Runs a query and returns the result
     *
     * Wraps the equivilent function in the Link
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @return \h2o\DB\Result Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        return $this->link->query( $query );
    }

    /**
     * Quotes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE )
    {
        return $this->link->quote( $value, $allowNull );
    }

    /**
     * Escapes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE )
    {
        return $this->link->escape( $value, $allowNull );
    }

    /**
     * Escapes a string to be used in a query
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param String $value The value to escape
     * @return String|Array
     */
    public function escapeString ( $value )
    {
        return $this->link->escapeString( $value );
    }

}

?>