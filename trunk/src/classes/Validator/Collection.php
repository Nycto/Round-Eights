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
     * Static method for creating a new collection instance
     *
     * @param object $validators... Any validators to add
     * @return Object Returns a new collection
     */
    static public function create ()
    {
        $output = new static;
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            $output->addMany( $args );
        }
        return $output;
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
    public function add( ::cPHP::iface::Validator $validator )
    {
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