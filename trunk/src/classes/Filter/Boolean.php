<?php
/**
 * Boolean filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Converts a value to boolean TRUE or FALSE
 */
class Boolean implements cPHP::iface::Filter
{
    
    /**
     * Converts the given value to boolean
     *
     * @param mixed $value The value to filter
     * @return Boolean
     */
    public function filter ( $value )
    {
        if ( is_bool($value) ) {
            return $value;
        }
        
        else if ( is_int($value) || is_float($value) ) {
            return $value == 0 ? FALSE : TRUE;
        }
        
        else if ( is_null($value) ) {
            return FALSE;
        }
        
        else if ( is_string($value) ) {
            
            $value = strtolower( ::cPHP::stripW( $value ) );
            if ( $value == "f" || $value == "false" || $value == "n" || $value == "no" || $value == "off" || ::cPHP::is_empty($value) )
                return FALSE;
            else
                return TRUE;
            
        }
        
        else if ( is_array($value) ) {
            return count($value) == 0 ? FALSE : TRUE;
        }
        
        else {
            return $value ? TRUE : FALSE;
        }
        
    }
    
}