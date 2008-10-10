<?php
/**
 * Base Database Decorator
 */

namespace cPHP::DB;

/**
 * Base wrapper for increasing the functionality of a database Link
 */
abstract class Adapter implements ::cPHP::iface::DB::Link
{
    
    /**
     * The Link this decorator wraps around
     */
    private $link;    
   
    /**
     * Constructor...
     *
     * @param Object $link The database Link this instance wraps around
     */
    public function __construct ( ::cPHP::iface::DB::Link $link )
    {
        $this->link = $link;
    }
    
    /**
     * Returns the Link this instance wraps
     *
     * @return Object
     */
    public function getLink ()
    {
        return $this->link;
    }
    
    /**
     * Runs a query and returns the result
     * 
     * Wraps the equivilent function in the Link
     *
     * @param String $query The query to run
     * @param Integer $flags Any boolean flags to set
     * @result Object Returns a result object
     */
    public function query ( $query, $flags = 0 )
    {
        return $this->link->query( $query );
    }
    
    /**
     * Quotes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE )
    {
        return $this->link->quote( $value, $allowNull );
    }
    
    /**
     * Escapes a variable to be used in a query
     *
     * Wraps the equivilent function in the Link
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE )
    {
        return $this->link->escape( $value, $allowNull );
    }
   
}

?>