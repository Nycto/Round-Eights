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
     * Internal method to get a list of field names returned
     *
     * @return Integer
     */
    abstract protected function rawFields ();

    /**
     * Internal method to get the number of fields returned
     *
     * @return Integer
     */
    abstract protected function rawNumFields ();

}

?>