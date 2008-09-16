<?php
/**
 * Base Validator class
 *
 * @package Filters
 */

namespace cPHP;

/**
 * This provides an interface for comparing a value to a set of parameters
 */
abstract class Validator extends cPHP::ErrorList implements cPHP::iface::Validator
{

    /**
     * Performs the validation and returns the result
     *
     * @param mixed $value The value to validate
     * @return Object Returns an instance of cPHP::Validator::Result
     */
    public function validate ( $value )
    {
        $result = $this->process( $value );
        
        if ( is_array($result) || $result instanceof ::cPHP::Ary )
            $result = ::cPHP::Ary::create( $result )->flatten()->collect("cPHP::strval")->compact()->get();
        
        elseif ( $result instanceof ::cPHP::Validator::Result )
            $result = $result->getErrors();
            
        else
            $result = ::cPHP::strval( $result );
        
        $output = new ::cPHP::Validator::Result( $value );
        
        if ( !::cPHP::is_empty($result) ) {
            
            if ( $this->hasErrors() )
                $output->addErrors( $this->getErrors() );
            else
                $output->addErrors( $result );
            
        }
        
        return $output;
    }
    
    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @return Boolean
     */
    public function isValid ( $value )
    {
        return $this->validate( $value )->isValid();
    }

    /**
     * The function that actually performs the validation
     *
     * @param mixed $value It will be given the value to validate
     * @return mixed Should return any errors that are encountered.
     *      This can be an array, a string, a cPHP::Validator::Result instance
     */
    abstract protected function process ($value);
    
}

?>