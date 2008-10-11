<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception::DB;

/**
 * Exception class for database queries
 */
class Deadlock extends ::cPHP::Exception::DB
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
     * @param mixed $Link The database Link associated with this error
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $query, $message = NULL, $code = 0, $Link = null, $fault = NULL )
    {
        parent::__construct( $message, $code, $Link, $fault );
        $this->addData("Query", $query);
    }

}

?>