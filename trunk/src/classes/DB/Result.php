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
    private $resource;
    
    /**
     * The query associated with these results
     */
    private $query;
    
    /**
     * Constructor...
     *
     * @param Resource $resource The database resource
     * @param String $query The query that produced this result
     */
    public function __construct ( $resource, $query )
    {
        if (is_resource($resource))
            $this->resource = $resource;
            
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
    public function hasResource ()
    {
        return isset( $this->resource ) && is_resource( $this->resource );
    }
    
    /**
     * Returns the resource this instance encases
     *
     * @return Resource|Null Returns NULL if there is no resource set
     */
    public function getResource ()
    {
        if ( $this->hasResource() )
            return $this->resource;
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
        if ( $this->hasResource() )
            $this->rawFree();
        $this->resource = NULL;
        return $this;
    }
    

}

?>