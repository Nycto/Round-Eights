<?php
/**
 * Debug related functions
 *
 * @package debug
 */

namespace cPHP;

/**
 * Dumps the content of a variable to the output buffer
 *
 * This works exactly like var_dump, except it detects if it needs to wrap the output in <pre> tags
 *
 * @param mixed $value The value to dump
 */
function dump ( $value )
{
    if ( _LOCAL ) {
        var_dump( $value );
    }
    else {
        echo "<pre>";
        var_dump( $value );
        echo "</pre>";
    }
}

/**
 * Returns a string containing information about this value
 *
 * @param mixed $value The value to return information about
 * @return String A shoft string describing the input
 */
function getDump ($value)
{

    if (is_bool($value))
        return "boolean(". ($value?"TRUE":"FALSE") .")";

    else if (is_null($value))
        return "null()";

    else if (is_int($value))
        return "int(". $value .")";

    else if (is_float($value))
        return "float(". $value .")";

    else if (is_string($value))
        return "string('". str_replace( Array("\n", "\r", "\t"), Array('\n', '\r', '\t'), strTruncate( addslashes($value), 50, "'...'") ) ."')";

    else if (is_array($value))
        return "array(length: ". count($value) .")";

    else if (is_object($value))
        return "object(type: ". get_class($value) .")";

    else if (is_resource($value))
        return "resource(type: ". get_resource_type($value) .")";

    else
        return "unknown(". gettype($value) .")";

}

?>