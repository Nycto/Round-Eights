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

namespace r8\DB\SQLite;

/**
 * SQLite read result
 */
class Result implements \r8\iface\DB\Adapter\Result
{

    /**
     * The result resource
     *
     * @var Resource
     */
    private $result;

    /**
     * Constructor...
     *
     * @param Resource $result The SQLite result resource
     */
    public function __construct ( $result )
    {
        if ( !is_resource($result) || get_resource_type($result) != 'sqlite result' )
            throw new \r8\Exception\Argument( 0, "Result Resource", "Must be a SQLite Result resource");

        $this->result = $result;
    }

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    public function count ()
    {
        return sqlite_num_rows( $this->result );
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Integer
     */
    public function getFields ()
    {
        $fields = sqlite_num_fields( $this->result );

        $result = array();

        for ( $i = 0; $i < $fields; $i++ ) {
            $result[ $i ] = sqlite_field_name( $this->result, $i );
        }

        return $result;
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    public function fetch ()
    {
        $current = sqlite_current( $this->result, SQLITE_ASSOC );

        if ( sqlite_valid($this->result) )
            sqlite_next($this->result);

        return $current ?: NULL;
    }

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    public function seek ($offset)
    {
        sqlite_seek( $this->result, $offset );
    }

    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    public function free ()
    {
        unset( $this->result );
    }

}

