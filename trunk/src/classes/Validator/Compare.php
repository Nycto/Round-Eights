<?php
/**
 * Validation class
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * Takes a comparison operator and a value and validates the given value against it
 */
class Compare extends ::cPHP::Validator
{
    
    /**
     * The operator to use for comparison
     */
    protected $operator;
    
    /**
     * The value to compare against
     */
    protected $versus;
    
    /**
     * Constructor...
     *
     * @param String $operator The operator to use for comparison
     * @param mixed $versus The value to compare against
     */
    public function __construct( $operator, $versus )
    {

        $operator = trim( ::cPHP::strval($operator) );

        if ( !preg_match( '/^(?:<=?|>=?|={1,3}|<>|!={1,2})$/', $operator ) )
            throw new ::cPHP::Exception::Argument( 0, "Comparison Operator", "Unsupported comparison operator" );
        
        $this->operator = $operator;
        
        $this->versus = $versus;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        
        switch( $this->operator ) {

            case "<":
                if ($value >= $this->versus)
                    return "Must be less than ". $this->versus;
                break;

            case ">":
                if ($value <= $this->versus)
                    return "Must be greater than ". $this->versus;
                break;

            case "<=":
                if ($value > $this->versus)
                    return "Must be less than or equal to ". $this->versus;
                break;

            case ">=":
                if ($value < $this->versus)
                    return "Must be greater than or equal to ". $this->versus;
                break;

            case "===":
                if ($value !== $this->versus)
                    return "Must be exactly equal to ". $this->versus;
                break;

            case "==":
            case "=":
                if ($value != $this->versus)
                    return "Must be equal to ". $this->versus;
                break;

            case "!==":
                if ($value === $this->versus)
                    return "Must not be exactly equal to ". $this->versus;
                break;

            case "!=":
            case "<>":
                if ($value == $this->versus)
                    return "Must not be equal to ". $this->versus;
                break;

        }
        
    }

}

?>