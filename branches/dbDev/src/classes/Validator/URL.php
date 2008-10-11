<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates a URL
 */
class URL extends ::cPHP::Validator
{

    /**
     * Validates a URL
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !is_string($value) )
            return "URL must be a string";
        
        if ( ::cPHP::strContains(" ", $value) )
            return "URL must not contain spaces";
        
        if ( ::cPHP::strContains("\t", $value) )
            return "URL must not contain tabs";
        
        if ( ::cPHP::strContains("\n", $value) || ::cPHP::strContains("\r", $value) )
            return "URL must not contain line breaks";
        
        if ( preg_match('/[^a-z0-9'. preg_quote('$-_.+!*\'(),{}|\\^~[]`<>#%";/?:@&=', '/') .']/i', $value) )
            return "URL contains invalid characters";
        
        if ( !filter_var( $value, FILTER_VALIDATE_URL ) )
            return "URL is not valid";
    }

}

?>