<?php
/**
 * Boolean filtering class
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace cPHP\Filter;

/**
 * Converts a value to boolean TRUE or FALSE
 */
class Boolean extends \cPHP\Filter
{

    /**
     * Converts the given value to boolean
     *
     * @param mixed $value The value to filter
     * @return Boolean
     */
    public function filter ( $value )
    {
        if ( is_bool($value) ) {
            return $value;
        }

        else if ( is_int($value) || is_float($value) ) {
            return $value == 0 ? FALSE : TRUE;
        }

        else if ( is_null($value) ) {
            return FALSE;
        }

        else if ( is_string($value) ) {

            $value = strtolower( \cPHP\str\stripW( $value ) );
            if ( $value == "f" || $value == "false" || $value == "n" || $value == "no" || $value == "off" || \cPHP\isEmpty($value) )
                return FALSE;
            else
                return TRUE;

        }

        else if ( is_array($value) ) {
            return count($value) == 0 ? FALSE : TRUE;
        }

        else {
            return $value ? TRUE : FALSE;
        }

    }

}

?>