<?php
/**
 * Exception Class
 *
 * @package Exception
 */

namespace cPHP::Exception;

/**
 * An exception class to describe errors caused when code incorrectly
 * interfaces with other code
 */
class Interaction extends ::cPHP::Exception
{
    
    /**
     * The title of this exception
     */
    const TITLE = "Interaction Error";
    
    /**
     * A brief description of this error type
     */
    const DESCRIPTION = "Errors caused when code incorrectly interfaces with other code";

}

?>