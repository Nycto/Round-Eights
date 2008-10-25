<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class for regular expression errors
 */
class RegEx extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Regular Expression Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused by invalid regular expressions";

    /**
     * Constructor
     *
     * @param String $value The value of the data that caused the error
     * @param String $label The name of the data
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($regex, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);
        
        $this->addData(
                "Regular Expression",
                ::cPHP::getDump($regex)
            );
    }
}

?>