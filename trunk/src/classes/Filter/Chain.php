<?php
/**
 * Base filter class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Collects a list of filters into a single filter
 *
 * This will feed the result of each filter in to the next
 *
 * The original plan was for this class to be called cPHP::Filter. However, this caused
 * the "filter" method to be considered a constructor, which threw standards compliance
 * errors. Thus, it was renamed.
 */
class Chain implements cPHP::iface::Filter
{
    
    /**
     * The list of filters to run through
     */
    protected $filters = array();
    
    /**
     * Static method for creating a new chaining filter
     * 
     * @param object $filters... Allows you to add filters on instantiation
     * @return Object Returns a new instance of this class
     */
    static public function create ()
    {
        $output = new self;
        
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            
            foreach ( $args AS $filter ) {
                if ( $filter instanceof cPHP::iface::Filter )
                    $output->add( $filter );
            }
        }
        
        return $output;
    }
    
    /**
     * Constructor
     *
     * @param object $filters... Allows you to add filters on instantiation
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            foreach ( $args AS $filter ) {
                if ( $filter instanceof cPHP::iface::Filter )
                    $this->add( $filter );
            }
        }
    }
    
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
    public function add ( cPHP::iface::Filter $filter )
    {
        $this->filters[] = $filter;
        return $this;
    }
    
    /**
     * Returns the array of filters contained in this instance
     *
     * @return Object Returns an Array object
     */
    public function get ()
    {
        return new cPHP::Ary( $this->filters );
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
    
    /**
     * Magic method to allow this instance to be invoked like a function.
     *
     * Causes the filtering to happen as if the filter method was invoked
     *
     * @param mixed $value The value to filter
     * @return mixed The result of the filtering
     */
    public function __invoke( $value )
    {
        return $this->filter( $value );
    }
}

?>