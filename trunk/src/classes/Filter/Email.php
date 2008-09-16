<?php
/**
 * Email filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Cleans up a EMail address string
 */
class Email implements cPHP::iface::Filter
{
    
    /**
     * Cleans up a string in preparation for using it as an e-mail address
     *
     * Remove everything except letters, digits and !#$%&'*+-/=?^_`{|}~@.[]
     *
     * @param mixed $value The value to filter
     * @return 
     */
    public function filter ( $value )
    {
        return filter_var(
                ::cPHP::strval( $value ),
                FILTER_SANITIZE_EMAIL
            );
    }
    
}