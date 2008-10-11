<?php
/**
 * Validator Collection
 *
 * @package Validator
 */

namespace cPHP::Validator::Collection;

/**
 * Validator that requires all of its contained validators to return positively
 */
class All extends ::cPHP::Validator::Collection
{
    
    /**
     * Runs all of the contained validators
     *
     * @param mixed $value The value being validated
     */
    protected function process ( $value )
    {
        $errors = array();
        
        foreach( $this->validators AS $valid ) {
            
            $result = $valid->validate( $value );
            
            if ( !$result instanceof ::cPHP::Validator::Result ) 
                throw new ::cPHP::Exception::Data( $result, "Validator Result", "Must be an instance of cPHP::Validator::Result" );
        
            $errors = array_merge( $errors, $result->getErrors()->get() );
            
        }
        
        return $errors;
    }
    
}

?>