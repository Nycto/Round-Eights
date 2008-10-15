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
abstract class Read extends ::cPHP::DB::Result
{
    
    /**
     * The number of rows returned by this query
     */
    private $numRows;

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    abstract protected function rawNumRows ();
    
    /**
     * Returns the number of rows affected by a query
     *
     * @return Integer|False
     */
    public function getNumRows ()
    {
        if ( !isset($this->numRows) ) {
            
            $this->numRows = $this->rawNumRows();
            
            if ( !is_int($this->numRows) )
                $this->numRows = FALSE;
        }
    
        return $this->numRows;
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Integer
     */
    abstract protected function rawFields ();
    
    /**
     * Returns a list of field names returned by the query
     *
     * @return Integer|False
     */
    public function getFields ()
    {
        if ( !isset($this->fields) ) {
            
            $this->numRows = $this->rawNumRows();
            
            if ( !is_int($this->numRows) )
                $this->numRows = FALSE;
        }
    
        return $this->numRows;
    }

    /**
     * Internal method to get the number of fields returned
     *
     * @return Integer
     */
    abstract protected function rawNumFields ();

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

}

?>