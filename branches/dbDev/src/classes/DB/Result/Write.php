<?php
/**
 * Database Query Result
 *
 * @package Database
 */

namespace cPHP::DB::Result;

/**
 * Database Read Query Results
 */
class Write extends ::cPHP::DB::Result
{
    
    /**
     * This is the cached value of the affected number of rows
     */
    private $affected;
    
    /**
     * This is the cached value of the insert ID
     */
    private $insertID;
    
    /**
     * Constructor...
     *
     * @param Integer|NULL $affected The number of rows affected by this query
     * @param Integer|NULL $insertID The ID of the row inserted by this query
     * @param String $query The query that produced this result
     */
    public function __construct ( $affected, $insertID, $query )
    {
        if ( !::cPHP::is_vague($insertID) ) {
            $insertID = intval($insertID);
            $this->insertID = $insertID > 0 ? $insertID : NULL;
        }
        
        $this->affected = max( intval( $affected ), 0 );
        
        parent::__construct($query);
    }
    
    /**
     * Returns the number of rows affected by a query
     *
     * @return Integer|False
     */
    public function getAffected ()
    {
        return $this->affected;
    }
    
    /**
     * Returns the ID of the row inserted by this query
     *
     * @return Integer|False This will return FALSE if no ID is returned
     */
    public function getInsertID ()
    {
        return $this->insertID;
    }

}

?>