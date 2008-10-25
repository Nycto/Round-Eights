<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates that a given value is the same or shorter than a given length
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative
 */
class MaxLength extends ::cPHP::Validator
{
    
    /**
     * The string length the value must be less than or equal to
     */
    protected $length;
    
    /**
     * Constructor...
     *
     * @param Integer $length The string length the value must be less than or equal to
     *      This must be greater than or equal to 0. Any negative numbers will be set to 0
     */
    public function __construct( $length )
    {
        $this->length = max( intval($length), 0 );
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( is_bool($value) || is_int($value) || is_float($value) || is_null($value) )
            $value = ::cPHP::strval($value);
        
        if ( !is_string($value) )
            return "Must be a string";
        
        if ( strlen($value) > $this->length ) {
            return ::cPHP::pluralize(
                    "Must not be longer than ". $this->length ." character",
                    $this->length
                );
        }
    }

}

?>