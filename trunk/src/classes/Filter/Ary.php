<?php
/**
 * Array filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Applies a given filter to every value in an array, non-recursively
 */
class Ary extends cPHP::Filter
{
    
    /**
     * The filter that will be applied to each value
     */
    private $filter;
    
    /**
     * Constructor...
     *
     * @param Object The filter to apply to each value in the array
     */
    public function __construct( ::cPHP::iface::Filter $filter )
    {
        $this->setFilter( $filter );
    }
    
    /**
     * Returns the filter loaded in this instance
     *
     * @return Object
     */
    public function getFilter ()
    {
        return $this->filter;
    }
    
    /**
     * Sets the filter that will be applied to each value of a filtered array
     *
     * @param Object The filter to load in to this instance
     * @return Object Returns a self reference
     */
    public function setFilter ( ::cPHP::iface::Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }
    
    /**
     * Apply this filter to an array value
     *
     * @param Array $value The value to filter
     * @return Array Returns the filtered version
     */
    public function filter ( $value )
    {
        if ( !::cPHP::Ary::is($value) || ( is_object($value) && !( $value instanceof ArrayAccess) ) )
            $value = array($value);
        
        foreach( $value AS $key => $val ) {
            $value[ $key ] = $this->filter->filter( $val );
        }
        
        return $value;
    }
    
}

?>