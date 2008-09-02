<?php
/**
 * Core filter interface
 *
 * @package Filters
 */

namespace cPHP::iface;

/**
 * Basic filter definition
 */
interface Filter {
    
    /**
     * Takes a value, processes it in a standard way and returns the result
     *
     * @param mixed $value The value to filter
     * @result mixed The result of the filtering process
     */
    public function filter ( $value );
    
}

?>