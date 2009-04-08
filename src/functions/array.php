<?php
/**
 * Array functions
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
 * @package numeric
 */

namespace cPHP\ary;

/**
 * Reduces a multi-dimensional array down to a single-dimensional array
 *
 * Takes a multi-dimensional array and flattens it down to a single-dimensional array
 *
 * @param array $array The array you wish to flatten
 * @param integer $maxDepth The maximum depth the array is allowed to be.
 * @return array Returns the flattened array
 */
function flatten ( array $array, $maxDepth = 1 )
{
    $maxDepth = max( intval($maxDepth), 1 );

    $output = array();

    foreach ( $array AS $key => $value ) {

        if ( !is_array($array[$key]) ) {
            $output = array_merge( $output, array( $key => $value ) );
        }

        else {

            if ($maxDepth <= 1) {
                $flat = \cPHP\ary\flatten($array[$key], 1);
                $output = array_merge($output, $flat);
                unset ($flat);
            }
            else {
                $output[$key] = \cPHP\ary\flatten($array[$key], $maxDepth - 1);
            }

        }

    }

    return $output;
}

?>