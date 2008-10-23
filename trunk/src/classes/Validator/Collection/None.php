<?php
/**
 * Validator Collection
 *
 * @package Validator
 */

namespace cPHP::Validator::Collection;

/**
 * Validator that requires none of its contained validators to return positively
 */
class None extends ::cPHP::Validator::Collection
{
    
    /**
     * Runs all of the contained validators
     *
     * @param mixed $value The value being validated
     */
    protected function process ( $value )
    {
        
        foreach( $this->validators AS $valid ) {
            
            $result = $valid->validate( $value );
            
            if ( !$result instanceof ::cPHP::Validator::Result ) 
                throw new ::cPHP::Exception::Data( $result, "Validator Result", "Must be an instance of cPHP::Validator::Result" );
            
            if ( $result->isValid() )
                return "Value is not valid";
            
        }
        
    }
    
}

?>