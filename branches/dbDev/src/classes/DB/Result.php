<?php
/**
 * Database Query Result
 *
 * @package Database
 */

namespace cPHP::DB;

/**
 * Database Query Result
 */
abstract class Result
{
    
    /**
     * The database result resource
     */
    private $result;
    
    /**
     * The query associated with these results
     */
    private $query;
    
    /**
     * Constructor...
     *
     * @param Resource|Object $result The database result resource or object
     * @param String $query The query that produced this result
     */
    public function __construct ( $result, $query )
    {
        if (is_resource($result) || is_object($result))
            $this->result = $result;
            
        $this->query = ::cPHP::strval($query);
    }
    
    /**
     * Destructor...
     *
     * Ensures that the resource is freed
     */
    public function __destruct()
    {
        $this->free();
    }
    
    /**
     * Returns the query associated with this result
     *
     * @return String
     */
    public function getQuery ()
    {
        return $this->query;
    }
    
    /**
     * Returns whether this instance currently holds a valid resource
     *
     * @return Boolean
     */
    public function hasResult ()
    {
        return isset( $this->result )
            && ( is_resource( $this->result ) || is_object( $this->result ) );
    }
    
    /**
     * Returns the result resource this instance encases
     *
     * @return mixed Returns NULL if there is no resource set
     */
    protected function getResult ()
    {
        if ( $this->hasResult() )
            return $this->result;
        else
            return NULL;
    }
    
    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    abstract protected function rawFree ();
    
    /**
     * Frees the resource in this instance
     *
     * @return Object Returns a self reference
     */
    public function free ()
    {
        if ( $this->hasResult() ) {
            $this->rawFree();
            $this->result = null;
        }
        return $this;
    }

}

?>