<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates that a given value does not contain spaces, tabs, or newlines
 *
 * This will return positive for Boolean, Integers, Floats and Null
 */
class NoSpaces extends ::cPHP::Validator
{

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( is_bool($value) || is_int($value) || is_float($value) || is_null($value) )
            return null;
        
        if ( !is_string($value) )
            return "Must be a string";
        
        if ( ::cPHP::strContains(' ', $value) )
            return "Must not contain any spaces";
        
        if ( ::cPHP::strContains("\t", $value) )
            return "Must not contain any tabs";
        
        if ( ::cPHP::strContains("\n", $value) || ::cPHP::strContains("\r", $value) )
            return "Must not contain any new lines";
    }

}

?>