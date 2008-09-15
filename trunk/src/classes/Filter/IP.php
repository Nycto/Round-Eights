<?php
/**
 * IP filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Cleans up a string in preparation for using it as an IP
 *
 * Removes everything except numbers and periods
 */
class IP implements cPHP::iface::Filter
{
    
    /**
     * Cleans up a string in preparation for using it as an IP
     *
     * @param mixed $value The value to filter
     * @return 
     */
    public function filter ( $value )
    {
        return preg_replace(
                '/[^0-9\.]/',
                '',
                ::cPHP::strval( $value )
            );
    }
    
}