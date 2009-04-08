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

/**
 * Adds a branch and value to an array tree
 *
 * @param Array $array The array to operate on. This is passed in by reference,
 *      so it will be changed in place.
 * @param mixed $value The value being pushed on to the tree
 * @param Array $keys The list of keys leading down to the value
 *      A Null key will cause that node to be pushed on to the array
 * @return Null
 */
function branch ( array &$array, $value, array $keys )
{
    // Get the list of keys as a flattened array
    $keys = \cPHP\ary\flatten($keys);

    if ( count($keys) <= 0 )
        return;

    // Grab the last key from the list and remove it. It can't be treated
    // like the rest of them
    $lastKey = array_pop ( $keys );

    // Start with the root of the array
    $current =& $array;

    // Loop through the list of keys and create the branch
    foreach ( $keys AS $index ) {

        // For null keys, just push a new array on the end
        if ( is_null($index) ) {

            $new = array();

            // Add the new value on the end
            $current[] =& $new;

            // Then switch the current leaf to pointing at the new array
            $current =& $new;

        }

        else {

            // If the key doesn't exist or it isn't an array, then overwrite it with an array
            if ( !isset($current[ $index ]) || !is_array($current[$index]) )
                $current[$index] = array();

            $current =& $current[$index];

        }

    }

    // Finally, push the value on to the end of the branch
    if ( is_null($lastKey) )
        $current[] = $value;
    else
        $current[ $lastKey ] = $value;

    return null;
}

?>