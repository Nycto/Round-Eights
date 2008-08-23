<?php
/**
 * Function Currying
 *
 * @package curry
 */

namespace cPHP::Curry;

/**
 * The most basic curry class. Invokes a defined callback
 */
class Call extends cPHP::Curry
{

    /**
     * The callback to be invoked
     */
    protected $callback;

    /**
     * Constructor...
     *
     * @param mixed $callback The callback to invoke
     * @param mixed $args... Any rightward arguments 
     */
    public function __construct ( $callback )
    {
        
    }
    
    /**
     *
     */
    public function apply ( array $args )
    {
        
    }
    
}

?>