<?php
/**
 * Core filter interface
 *
 * @package Filters
 */

if (!isset($_SERVER["SCRIPT_FILENAME"]) || strcasecmp($_SERVER["SCRIPT_FILENAME"], __FILE__) == 0) die("This file can not be loaded directly");

/**
 * Basic filter definition
 */
interface iFilter {
    
    /**
     * Takes a value, processes it in a standard way and returns the result
     *
     * @param mixed $value The value to filter
     * @result mixed The result of the filtering process
     */
    public function filter ( $value );
    
}

?>