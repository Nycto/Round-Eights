<?php
/**
 * Debug related functions
 *
 * @license Artistic License 2.0
 *
 * This file is part of Round Eights.
 *
 * Round Eights is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * Round Eights is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with Round Eights. If not, see <http://www.RoundEights.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RoundEights.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package debug
 */

namespace r8;

/**
 * Dumps the content of a variable to the output buffer
 *
 * This works exactly like var_dump, except it detects if it needs to wrap the output in <pre> tags
 *
 * @param mixed $value The value to dump
 */
function dump ( $value )
{
    if ( isset($_SERVER['SHELL']) ) {
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
        return "bool(". ($value?"TRUE":"FALSE") .")";

    else if (is_null($value))
        return "null()";

    else if (is_int($value))
        return "int(". $value .")";

    else if (is_float($value))
        return "float(". $value .")";

    else if (is_string($value)) {
        return "string('"
            .str_replace(
                    Array("\n", "\r", "\t"),
                    Array('\n', '\r', '\t'),
                    \r8\str\truncate( addslashes($value), 50, "'...'")
                )
            ."')";
    }

    else if (is_array($value)) {

        if ( count($value) == 0 )
            return "array(0)";

        $output = array();

        $i = 0;
        foreach( $value AS $key => $val ) {

            $i++;

            $output[] =
                getDump($key)
                ." => "
                . ( is_array($val) ? "array(". count($val) .")" : getDump($val) );

            if ( $i == 2 )
                break;
        }

        return "array(". count($value) .")("
            .implode(", ", $output)
            .( count($value) > 2 ? ",..." : "" )
            .")";


    }

    else if (is_object($value))
        return "object(". get_class($value) .")";

    else if (is_resource($value))
        return "resource(". get_resource_type($value) .")";

    else
        return "unknown(". gettype($value) .")";

}

?>