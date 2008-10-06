<?php
/**
 * Base Class for a collection of validators
 *
 * @package Validator
 */

namespace cPHP::Validator;

/**
 * An interface for grouping a set of validators in to one object
 */
abstract class Collection extends cPHP::Validator
{
    
    /**
     * The list of validators contained in this instance
     */
    protected $validators = array();
    
    /**
     * Static method for creating a new validator instance
     *
     * This takes the called function and looks for a class under
     * the cPHP::Validator namespace.
     *
     * @throws cPHP::Exception::Argument Thrown if the validator class can't be found
     * @param String $validator The validator class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new cPHP::Validator subclass
     */
    static public function __callStatic ( $validator, $args )
    {
        return parent::__callStatic( "Collection::". $validator, $args );
    }
    
    /**
     * Constructor
     *
     * Allows you to add validators on construction
     *
     * @param object $validators...
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            $this->addMany( $args );
        }
    }
    
    /**
     * Adds a validator to this instance
     *
     * @param Object The validator to addd to this instance
     * @return Object Returns a self reference
     */
    public function add( $validator )
    {
        if ( is_object($validator) ) {
            
            if ( !$validator instanceof cPHP::iface::Validator )
                throw new cPHP::Exception::Argument( 0, "Validator", "Must be an instance of cPHP::iface::Validator" );
            
        }
        else {
            $validator = ::cPHP::strval( $validator );
            
            if ( !is_subclass_of($validator, "cPHP::iface::Validator") ) {
                
                $refl = new ReflectionClass( $validator );
                if ( !$refl->implementsInterface( "cPHP::iface::Validator" ) )
                    throw new cPHP::Exception::Argument( 0, "Validator", "Must be an instance of cPHP::iface::Validator" );
                
            }
            
            $validator = new $validator;
        }
        
        $this->validators[] = $validator;
        return $this;
    }
    
    /**
     * Returns the list of validators contained in this instance
     *
     * @return object Returns a cPHP::Ary object
     */
    public function getValidators ()
    {
        return new ::cPHP::Ary( $this->validators );
    }
    
    /**
     * Adds many validators to this instance at once
     *
     * @param mixed $validators... Any arguments passed will be flattened down and filtered
     * @return Object Returns a self reference
     */
    public function addMany ( $validators )
    {
        $validators = func_get_args();
        ::cPHP::Ary::create( $validators )
            ->flatten()
            ->filter(function($validator) {
                return $validator instanceof ::cPHP::iface::Validator;
            })
            ->each(array($this, "add"));
        return $this;
    }
    
}

?>