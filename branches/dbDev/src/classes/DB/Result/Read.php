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
abstract class Read extends ::cPHP::DB::Result implements Countable, SeekableIterator
{
    
    /**
     * The number of rows returned by this query
     */
    private $count;
    
    /**
     * The list of fields in the result set
     */
    private $fields;
    
    /**
     * The current offset of the result set
     */
    private $pointer;
    
    /**
     * The value of the current row
     */
    private $row;

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    abstract protected function rawCount ();
    
    /**
     * Returns the number of rows affected by a query
     *
     * This also provides the functionality to access the number of rows in this
     * result set via the "count" method
     *
     * @return Integer
     */
    public function count ()
    {
        
        if ( !isset($this->count) ) {
            
            $this->count = $this->rawCount();
            
            if ( !is_int($this->count) )
                $this->count = 0;
        }
    
        return $this->count;
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
            
            $this->fields = $this->rawFields();
            
            if ( !is_array($this->fields) )
                $this->fields = array();
        }
    
        return $this->fields;
    }

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
     * Returns the value of the current row
     *
     * Iterator interface function
     *
     * @return mixed Returns the current Row, or FALSE if the iteration has
     *      reached the end of the row list
     */
    public function current ()
    {
        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();
            
        return $this->row;
    }
    
    /**
     * Returns the whether the current row is valid
     *
     * Iterator interface function
     *
     * @return Boolean
     */
    public function valid ()
    {
        $count = $this->count();
        
        if ( $count == 0 )
            return FALSE;
        
        return $this->pointer < $count ? TRUE : FALSE;
    }
    
    /**
     * Increments to the next result row
     *
     * Iterator interface function
     *
     * @return Object Returns a self reference
     */
    public function next ()
    {
        
        // If the pointer isn't set yet, start it at 0
        if ( !isset($this->pointer) )
            $this->pointer = 0;
        
        // Don't increment beyond the count
        else if ( $this->pointer < $this->count() )
            $this->pointer++;
        
        // If there are still rows to fetch, grab the next one
        if ( $this->pointer < $this->count() )
            $this->row = $this->rawFetch();
        else
            $this->row = FALSE;
        
        return $this;
    }
    
    /**
     * Returns the offset of the current result row
     *
     * Iterator interface function
     *
     * @return Integer
     */
    public function key ()
    {
        // If the pointer has not yet been initialize, grab the first row
        if ( !isset($this->pointer) )
            $this->next();
        
        // The key is simply the internal row pointer
        return $this->pointer;
    }
    
    /**
     * Resets the result iterator to the beginning
     *
     * Iterator interface function
     *
     * @return Object Returns a self reference
     */
    public function rewind ()
    {
        // If the pointer hasn't been initialized at all, then we just need to fetch the first row
        if ( !isset($this->pointer) )
            $this->next();
        
        // If the pointer is already at zero, we don't need to do anything
        else if ( $this->pointer > 0 )
            $this->seek(0);
        
        return $this;
    }
    
    /**
     * Sets the internal result pointer to a given offset
     *
     * SeekableIterator interface function
     * 
     * @param Integer $offset The offset to seek to
     * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
     * @return Object Returns a self reference
     */
    public function seek ( $offset, $wrapFlag = ::cPHP::OFFSET_RESTRICT )
    {
        $offset = ::cPHP::offsetWrap(
                $this->count(),
                $offset,
                $wrapFlag
            );
        
        if ( $offset !== FALSE && $this->pointer !== $offset ) {
            $this->pointer = $offset;
            $this->row = $this->rawSeek( $offset );
        }
        
        return $this;
    }

}

?>