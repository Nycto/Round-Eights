<?php
/**
 * Integer filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Converts a value to an integer
 */
class Integer implements cPHP::iface::Filter
{
    
    /**
     * Converts the given value to an integer
     *
     * @param mixed $value The value to filter
     * @return Boolean
     */
    public function filter ( $value )
    {
        if ( is_array($value) ) {
            
            if ( count($value) == 0 )
                return 0;
            else
                $value = ::cPHP::reduce($value);
            
        }
        
        if ( is_string($value) ) {
            $value = preg_replace('/[^\-0-9\.]/', '', $value);
            $value = preg_replace('/(?<!^)\-/', '', $value);
        }
        
        return intval( $value );
        
    }
    
}