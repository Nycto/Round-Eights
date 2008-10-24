<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Validates that a value is not considered empty using the is_empty function
 */
class NotEmpty extends ::cPHP::Validator
{
    
    /**
     * Any flags to pass to the is_empty function
     */
    protected $flags = 0;
    
    /**
     * Constructor...
     *
     * @param Integer $flags Any flags to pass to the is_empty function. For
     *      more details, take a look at that function
     */
    public function __construct ( $flags = 0 )
    {
        $this->flags = max( intval($flags), 0 );
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( ::cPHP::is_empty($value, $this->flags) )
            return "Must not be empty";
    }

}

?>