<?php
/**
 * Core Validator interface
 *
 * @package Validator
 */

namespace cPHP::iface;

/**
 * Basic filter definition
 */
interface Validator
{
    
    /**
     * Takes a value, processes it, and returns an instance of Validator Results
     *
     * @param mixed $value The value to validate
     * @result object An instance of validator results
     */
    public function validate ( $value );
   
}

?>