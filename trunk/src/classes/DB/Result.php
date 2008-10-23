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
     * The query associated with these results
     */
    private $query;
    
    /**
     * Constructor...
     *
     * @param String $query The query that produced this result
     */
    public function __construct ( $query )
    {
        $this->query = ::cPHP::strval($query);
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

}

?>