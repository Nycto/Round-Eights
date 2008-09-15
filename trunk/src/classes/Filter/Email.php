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
        $value = ::cPHP::strval( $value );
        
        $value = preg_replace(
                '/[^a-z0-9'. preg_quote("!#$%&'*+-/=?^_`{|}~@.[]", '/') .']/i',
                '',
                $value
            );
        
        if ( substr_count($value, "@") > 1 ) {
            $pos = strpos($value, "@");
            
            $value =
                substr($value, 0, $pos)
                ."@"
                .str_replace("@", "", substr($value, $pos + 1));
        }
        
        return $value;
    }
    
}