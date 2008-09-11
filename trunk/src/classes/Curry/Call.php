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
        if ( !is_callable($callback) )
            throw new cPHP::Exception::Data::Argument( 0, "Callback", "Must be Callable" );
        
        $this->callback = $callback;
    }
    
    /**
     * Invokes the current callback with the given array of arguments and returns the results
     *
     * @param $args Array The arguments to apply to the callback
     * @return mixed
     */
    public function apply ( array $args = array() )
    {
        
        return call_user_func_array(
            
                // For object, skip the shortcuts and just jump straight to the invoke method
                is_object($this->callback) ?
                    array( $this->callback, "__invoke") : $this->callback,
                    
                $this->collectArgs( $args )
                
            );
        
    }
    
}

?>