<?php
/**
 * Base Database Decorator
 */

namespace cPHP::DB;

/**
 * Base wrapper for increasing the functionality of a database connection
 */
abstract class Adapter implements ::cPHP::iface::DB::Connection
{
    
    /**
     * The connection this decorator wraps around
     */
    private $connection;    
   
    /**
     * Constructor...
     *
     * @param Object $connection The database connection this instance wraps around
     */
    public function __construct ( ::cPHP::iface::DB::Connection $connection )
    {
        $this->connection = $connection;
    }
    
    /**
     * Returns the connection this instance wraps
     *
     * @return Object
     */
    public function getConnection ()
    {
        return $this->connection;
    }
    
    /**
     * Runs a query and returns the result
     * 
     * Wraps the equivilent function in the connection
     *
     * @param String $query The query to run
     * @result Object Returns a result object
     */
    public function query ( $query )
    {
        return $this->connection->query( $query );
    }
    
    /**
     * Quotes a variable to be used in a query
     *
     * Wraps the equivilent function in the connection
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function quote ( $value, $allowNull = TRUE )
    {
        return $this->connection->quote( $value, $allowNull );
    }
    
    /**
     * Escapes a variable to be used in a query
     *
     * Wraps the equivilent function in the connection
     *
     * @param mixed $value The value to quote
     * @param Boolean $allowNull Whether to allow 
     * @return String|Array
     */
    public function escape ( $value, $allowNull = TRUE )
    {
        return $this->connection->escape( $value, $allowNull );
    }
   
}

?>