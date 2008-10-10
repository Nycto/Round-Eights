<?php
/**
 * Database Connection
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
     * Constructor...
     *
     * @param Resource $resource The database resource
     * @param String $query The query that produced this result
     */
    public function __construct ( $resource, $query )
    {
        if (is_resource($resource))
            $this->resource = $resource;
    }
    
    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    abstract protected function rawFree ();

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    abstract protected function rawNumRows ();

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    abstract protected function rawFetch ();

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    abstract protected function rawSeek ($offset);

    /**
     * Internal method to return the number of rows affected by this query
     *
     * @return Integer
     */
    abstract protected function rawAffected ();

    /**
     * Internal method to return the insert ID for this query
     *
     * @return Integer
     */
    abstract protected function rawInsertID ();

    /**
     * Internal method to get the number of fields returned
     *
     * @return Integer
     */
    abstract protected function rawNumFields ();

}

?>