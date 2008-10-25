<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates that a value matches a given regular expression
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative.
 *
 * This uses preg_match to execute the regular expression and does not add the
 * wrap characters. You must include those on instantiation
 */
class RegEx extends ::cPHP::Validator
{
    
    /**
     * The Regular Expression to compare the value to
     */
    protected $regex;
    
    /**
     * Constructor...
     *
     * @param String $regex The Regular Expression to compare the value to
     */
    public function __construct( $regex )
    {
        $regex = ::cPHP::strVal( $regex );
        if ( ::cPHP::is_empty($regex) )
            throw new ::cPHP::Exception::Argument(0, "Regular Expression", "Must not be empty");
        $this->regex = $regex;
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
        
        if ( !preg_match($this->regex, $value) )
            return "Must match the following regular expression: ". $this->regex;
    }

}

?>