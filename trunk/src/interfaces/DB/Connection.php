<?php
/**
 * Database connection interface
 *
 * @package Filters
 */

namespace cPHP::iface::DB;

/**
 * Database connection interface
 */
interface Connection
{
    
    /**
     * Runs a query and returns the result
     *
     * @param String $query The query to run
     * @result Object Returns a result object
     */
    public function query ( $query );
    
    /**
     * Quotes a variable to be used in a query
     *
     * When given a string, it escapes the string and puts quotes around it. When
     * given a number, it returns the number as is. When given a boolean value,
     * it returns 0 or 1. When given a NULL value, it returns the word NULL as a string.
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
     * This function works almost exactly like cDB::quote except that it does
     * not add quotation marks to strings. It just escapes each value.
     *
     * If this function is given an array, it will apply itself to every value
     * in the array and return that array.
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE );

}

?>