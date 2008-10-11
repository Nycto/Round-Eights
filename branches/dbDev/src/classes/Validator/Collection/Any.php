<?php
/**
 * Validator Collection
 *
 * @package Validator
 */

namespace cPHP::Validator::Collection;

/**
 * Validator collection that will return positive if ANY of the contained validators return positive
 */
class Any extends cPHP::Validator::Collection
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
            
            // Break out once any of the validators returns positively
            if ( $result->isValid() )
                return array();
        
            $errors = array_merge( $errors, $result->getErrors()->get() );
            
        }
        
        return $errors;
    }
    
}

?>