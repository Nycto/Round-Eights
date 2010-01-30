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

namespace r8\DB\Link;

/**
 * Base decorator for increasing the functionality of a database Link
 */
abstract class Decorator implements \r8\iface\DB\Link
{

    /**
     * The Link this decorator wraps around
     *
     * @var \r8\iface\DB\Link
     */
    private $link;

    /**
     * Constructor...
     *
     * @param \r8\iface\DB\Link $link The database Link this instance wraps around
     */
    public function __construct ( \r8\iface\DB\Link $link )
    {
        $this->link = $link;
    }

    /**
     * Returns the Link this instance wraps
     *
     * @return \r8\iface\DB\Link
     */
    public function getLink ()
    {
        return $this->link;
    }

    /**
     * Runs a query and returns the result
     *
     * Wraps the equivilent function in the Link
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @return \r8\DB\Result Returns a result object
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
     * Quotes the named identifier. This could be the name of a field, table,
     * or database
     *
     * @param String $name The named identifier to quote
     * @return String
     */
    public function quoteName ( $name )
    {
        return $this->link->quoteName( $name );
    }

    /**
     * Returns a brief string that can be used to describe this connection
     *
     * @return String Returns a URI that loosely identifies this connection
     */
    public function getIdentifier ()
    {
        return $this->link->getIdentifier();
    }

}

?>