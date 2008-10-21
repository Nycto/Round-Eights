<?php
/**
 * Database Read result
 *
 * @package Database
 */

namespace cPHP::DB::MySQLi;

/**
 * MySQLi Database read result
 */
class Read extends ::cPHP::DB::Result::Read
{

    /**
     * Internal method that returns the number of rows found
     *
     * @return Integer
     */
    protected function rawCount ()
    {
        return $this->getResult()->num_rows;
    }

    /**
     * Internal method to fetch the next row in a result set
     *
     * @return Array Returns the field values
     */
    protected function rawFetch ()
    {
        return $this->getResult()->fetch_assoc();
    }

    /**
     * Internal method to seek to a specific row in a result resource
     *
     * @param Integer $offset The offset to seek to
     * @return Array Returns the field values
     */
    protected function rawSeek ($offset)
    {
        $this->getResult()->data_seek($offset);
        return $this->rawFetch();
    }

    /**
     * Internal method to get a list of field names returned
     *
     * @return Array
     */
    protected function rawFields ()
    {
        $fields = $this->getResult()->fetch_fields();
        
        foreach ( $fields AS $key => $field ) {
            $fields[ $key ] = $field->name;
        }
        
        return $fields;
    }
    
    /**
     * Internal method to free the result resource
     *
     * @return null
     */
    protected function rawFree ()
    {
        $result = $this->getResult();
        $result->free();
    }

}

?>