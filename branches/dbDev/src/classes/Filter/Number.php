<?php
/**
 * Number filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Converts a value to either a float or an integer
 */
class Number extends cPHP::Filter
{
    
    /**
     * Converts the given value to a float or an integer
     *
     * @param mixed $value The value to filter
     * @return Float|Integer
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
        
        if ( is_object($value) )
            return 1;
        
        if ( floatval( $value ) == intval( $value ) )
            return intval( $value );
        else
            return floatval( $value );
    }
    
}

?>