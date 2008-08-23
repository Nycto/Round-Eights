<?php
/**
 * Base filter class
 *
 * @package Filters
 */

if (!isset($_SERVER["SCRIPT_FILENAME"]) || strcasecmp($_SERVER["SCRIPT_FILENAME"], __FILE__) == 0) die("This file can not be loaded directly");

/**
 * Collects a list of filters into a single filter
 *
 * This will feed the result of each filter in to the next
 */
class Filter implements iFilter
{
    
    /**
     * The list of filters to run through
     */
    protected $filters = array();
    
    /**
     * Removes all the filters from this instance
     *
     * @return object Returns a self reference
     */
    public function clear ()
    {
        $this->filters = array();
        return $this;
    }
    
    /**
     * Adds a new filter to this interface
     *
     * @param object $filter The filter to add
     * @return object Returns a self reference
     */
    public function add ( iFilter $filter )
    {
        $this->filters[] = $filter;
        return $this;
    }
    
    /**
     * Applies the contained filters to the given value and returns the results
     *
     * @param mixed $value The value to filter
     * @return mixed The result of the filtering
     */
    public function filter ( $value )
    {
        foreach ( $this->filters AS $filter ) {
            $value = $filter->filter( $value );
        }
        return $value;
    }
    
}

?>