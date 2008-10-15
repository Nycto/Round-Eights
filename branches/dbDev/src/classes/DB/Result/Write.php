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
abstract class Write extends ::cPHP::DB::Result
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
     * Internal method to return the number of rows affected by this query
     *
     * @return Integer
     */
    abstract protected function rawAffected ();
    
    /**
     * Returns the number of rows affected by a query
     *
     * @return Integer|False
     */
    public function getAffected ()
    {
        if ( !isset($this->affected) ) {
            
            $this->affected = $this->rawAffected();
            
            if ( !is_int($this->affected) )
                $this->affected = FALSE;
        }
    
        return $this->affected;
    }

    /**
     * Internal method to return the insert ID for this query
     *
     * @return Integer
     */
    abstract protected function rawInsertID ();
    
    /**
     * Returns the ID of the row inserted by this query
     *
     * @return Integer|False This will return FALSE if no ID is returned
     */
    public function getInsertID ()
    {
        if ( !isset($this->insertID) ) {
            
            $this->insertID = $this->rawInsertID();
            
            if ( !is_int($this->insertID) )
                $this->insertID = FALSE;
        }
    
        return $this->insertID;
    }

}

?>