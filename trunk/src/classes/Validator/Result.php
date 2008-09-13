<?php
/**
 * Validator Results
 *
 * @package validator
 */

namespace cPHP::Validator;

/**
 * Contains the results of a validation
 */
class Result extends cPHP::ErrorList
{

    /**
     * The value that was validated
     */
    protected $value;

    /**
     * Constructor
     *
     * @param mixed $value The value that was validated
     */
    public function __construct ( $value )
    {
        $this->value = $value;
    }
    
    /**
     * Returns the value that was validated
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Returns whether there aren't any errors in the list
     *
     * @return Boolean
     */
    public function isValid ()
    {
        return !$this->hasErrors();
    }

}

?>