<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception::Database;

/**
 * Exception class for database queries
 */
class Deadlock extends ::cPHP::Exception::Database
{

    /**
     * Title of this exception
     */
    const TITLE = "Database Query Deadlock";
    
    /**
     * A brief description of this exception
     */
    const DESCRIPTION = "Thrown when a query experiences a deadlock";
    
    /**
     * Constructor...
     *
     * @param String $query The query that caused the deadlock
     * @param String $message The error message
     * @param Integer $code The error code
     * @param mixed $connection The database connection associated with this error
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $query, $message = NULL, $code = 0, $connection = null, $fault = NULL )
    {
        parent::__construct( $message, $code, $connection, $fault );
        $this->addData("Query", $query);
    }

}

?>