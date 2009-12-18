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

namespace r8\iface\DB;

/**
 * Database Link interface
 */
interface Link
{

    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @returns \r8\DB\Result Returns a result object
     */
    public function query ( $query, $flags = 0 );

    /**
     * Quotes a variable to be used in a query
     *
     * When given a string, it escapes the string and puts quotes around it. When
     * given a number, it returns the number as is. When given a boolean value,
     * it returns 0 or 1. When given a NULL value, it returns the word NULL as
     * a string.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return the array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE );

    /**
     * Escapes a variable to be used in a query
     *
     * This function works almost exactly like quote() except that it does
     * not add quotation marks to strings. It just escapes each value.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param mixed $value The value to escape
     * @param Boolean $allowNull Whether to allow
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE );

    /**
     * Escapes a string to be used in a query
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param String $value The value to escape
     * @return String|Array
     */
    public function escapeString ( $value );

}

?>