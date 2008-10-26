<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class for data that is not what it should be
 */
class Index extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Index Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors relating to list indexes";

    /**
     * Constructor
     *
     * @param String $value The value of the data that caused the error
     * @param String $label The name of the data
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($value, $label = NULL, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);
        
        $this->addData( "Index Label", ::cPHP::strval( $label ) );
        $this->addData( "Value", ::cPHP::getDump($value) );
    }
}

?>