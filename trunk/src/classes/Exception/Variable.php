<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * Exception class for errors caused by variables, but not necissarily their data
 */
class Variable extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Variable Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused by variables, not necissarily their data";

    /**
     * Constructor
     *
     * @param String $variable The name of the variable that caused the error
     * @param String $message The error message
     * @param Integer $code The error code
     * @param Integer $fault The backtrace offset that caused the error
     */
    public function __construct($variable, $message = NULL, $code = 0, $fault = NULL)
    {
        parent::__construct($message, $code, $fault);
        
        $this->addData(
                "Variable Name",
                ::cPHP::strval( $variable )
            );
    }
    
}

?>