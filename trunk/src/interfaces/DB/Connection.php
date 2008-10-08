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
    
}

?>