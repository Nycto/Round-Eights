<?php
/**
 * URL filtering class
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * Cleans up a string in preparation for using it as an e-mail address
 *
 * Remove everything except letters, digits and $-_.+!*'(),{}|\\^~[]`<>#%";/?:@&=
 */
class URL implements cPHP::iface::Filter
{
    
    /**
     * Cleans up a string in preparation for using it as an e-mail address
     *
     * @param mixed $value The value to filter
     * @return string
     */
    public function filter ( $value )
    {
        return filter_var(
            ::cPHP::strval($value),
            FILTER_SANITIZE_URL
        );
    }
    
}