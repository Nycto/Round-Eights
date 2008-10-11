<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class for PHP extension errors
 */
class Extension extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Extension Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "PHP Extension Errors";

    /**
     * Constructor
     *
     * @param String $extension The PHP extension related to this error
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($extension, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);
        
        $extension = ::cPHP::strval( $extension );
        
        $this->addData( "Extension", $extension );
    }

}

?>