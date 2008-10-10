<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class for database errors
 */
class Database extends ::cPHP::Exception
{

    /**
     * Title of this exception
     */
    const TITLE = "Database Error";
    
    /**
     * A brief description of this exception
     */
    const DESCRIPTION = "Database related errors";
    
    /**
     * Constructor...
     *
     * @param String $query The query that caused the error
     * @param String $message The error message
     * @param Integer $code The error code
     * @param mixed $Link The database Link associated with this error
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct ( $message = NULL, $code = 0, $Link = null, $fault = NULL )
    {
        parent::__construct( $message, $code, $fault );
    }
}

?>