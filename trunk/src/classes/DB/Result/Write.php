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

}

?>