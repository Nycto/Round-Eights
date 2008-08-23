<?php
/**
 * Function Currying
 *
 * @package curry
 */

namespace cPHP;

/**
 * Base class for Argument Currying classes
 */
abstract class Curry
{

    /**
     * Any arguments to pass to curry to the left
     */
    protected $leftArgs = array();
    
    /**
     * Any arguments to pass to curry to the right
     */
    protected $rightArgs = array();
    
    /**
     * For slicing the input arguments, this is the offset.
     *
     * See array_slice for details
     */
    protected $offset = 0;
    
    /**
     * For slicing the input arguments, this is the length of the array to allow
     *
     * See array_slice for details
     */
    protected $length;
    
    /**
     * Static method for in-line create
     *
     * @param mixed $action The callable action being curried
     * @param mixed $args... any arguments to pass to the constructor
     * @return Returns a new instance
     */
    static public function create ( $action )
    {
        $instance = new static( $action );
        
        if ( func_num_args() > 1 ) {
            
            $args = func_get_args();
            array_shift( $args );
            $instance->setRightByArray( $args );
            
        }
        
        return $instance;
    }
    
    /**
     * Sets the leftward arguments
     *
     * @param mixed $args... Any arguments to curry to the left
     * @return object Returns a self reference
     */
    public function setLeft ()
    {
        $args = func_get_args();
        $this->leftArgs = array_values( $args );
        return $this;
    }
    
    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setLeftByArray ( array $args = array() )
    {
        $this->leftArgs = array_values( $args );
        return $this;
    }
    
    /**
     * Returns the leftward argument list
     *
     * @return Array
     */
    public function getLeft ()
    {
        return $this->leftArgs;
    }
    
    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearLeft ()
    {
        $this->leftArgs = array();
        return $this;
    }
    
    /**
     * Sets the rightward arguments
     *
     * @param mixed $args... Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRight ()
    {
        $args = func_get_args();
        $this->rightArgs = array_values( $args );
        return $this;
    }
    
    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRightByArray ( array $args = array() )
    {
        $this->rightArgs = array_values( $args );
        return $this;
    }
    
    /**
     * Returns the rightward argument list
     *
     * @return Array
     */
    public function getRight ()
    {
        return $this->rightArgs;
    }
    
    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearRight ()
    {
        $this->rightArgs = array();
        return $this;
    }
    
    /**
     * Clears both the left and right arguments
     *
     * @return object Returns a self reference
     */
    public function clearArgs ()
    {
        return $this->clearRight()->clearLeft();
    }
    
    /**
     *
     */
    public function setOffset ( $offset )
    {
        
    }
    
    /**
     * Applies the slicing and combines the given arguments with the left args and right args
     *
     * @param array $args The arguments to curry
     * @return Returns the arguments to pass to the function
     */
    public function collectArgs ( array $args )
    {
        if ( $this->offset != 0 || !isset($this->length) ) {
            
            if ( !isset($this->length) )
                $args = array_slice( $args, $this->offset );
            else
                $args = array_slice( $args, $this->offset, $this->length );
            
        }
        
        return array_merge( $this->leftArgs, $args, $this->rightArgs );
    }
    
    /**
     * Calls the method using the contents of an array as the arguments
     *
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    abstract public function apply ( array $args );
    
    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... The arguments to pass to the callback
     * @return mixed Returns the result of the invokation
     */
    public function call ()
    {
        $args = func_get_args();
        return $this->apply( $args );
    }
    
    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... Any arguments to apply to the function
     * @return mixed Returns the results of the invokation
     */
    public function __invoke ()
    {
        $args = func_get_args();
        return $this->apply( $args );
    }
    
}

?>