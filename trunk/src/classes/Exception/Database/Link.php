<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception::Database;

/**
 * Exception class for database connection errors
 */
class Link extends ::cPHP::Exception::Database
{

    /**
     * Title of this exception
     */
    const TITLE = "Database Link Error";
    
    /**
     * A brief description of this exception
     */
    const DESCRIPTION = "Errors encountered while connecting to a database";

}

?>