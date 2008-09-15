<?php
/**
 * Filter class for standardizing an empty value
 *
 * @package Filters
 */

namespace cPHP::Filter;

/**
 * This funnels the multiple incarnations of "empty" in to a standard value
 *
 * If the value passed in ISN'T empty, it will return the value. Otherwise,
 * it returns the defined "standard" value
 */
class StandardEmpty implements cPHP::iface::Filter
{
    
    /**
     * Any flags to use while calling cPHP::is_empty
     */
    protected $flags = 0;
    
    /**
     * The value returned when the filter input is empty
     */
    protected $value = NULL;
    
    /**
     * Constructor
     *
     * @param mixed $value The value to return when the filter input is empty
     * @param Integer $flags Any flags to pass to is_empty
     */
    public function __construct ( $value = NULL, $flags = 0 )
    {
        $this->setValue( $value );
        $this->setFlags( $flags );
    }
    
    /**
     * Returns the current flag
     *
     * @return Integer
     */
    public function getFlags ()
    {
        return $this->flags;
    }
    
    /**
     * Sets the value for the flags in this instance
     *
     * This unsets any existing values and replaces it with the given parameter
     *
     * @param Integer $flags
     * @return Object Returns a self reference
     */
    public function setFlags ( $flags )
    {
        $this->flags = max( intval($flags), 0 );
        return $this;
    }
    
    /**
     * Adds the value for the flags in this instance
     *
     * This adds the given flags in to the existing flags, leaving the current values set.
     *
     * @param Integer $flags
     * @return Object Returns a self reference
     */
    public function addFlags ( $flags )
    {
        $flags = max( intval($flags), 0 );
        $this->flags = $this->flags | $flags;
        return $this;
    }
    
    /**
     * Returns the standard empty value
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }
    
    /**
     * Sets the standard empty value
     *
     * @param mixed $value The new value
     * @return object Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * Performs the filtering
     *
     * @param mixed $value The value to filter
     * @return mixed
     */
    public function filter ( $value )
    {
        if ( ::cPHP::is_empty( $value, $this->flags ) )
            return $this->value;
        
        return $value;
    }
    
}