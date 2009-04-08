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
 * No wrapping will be perfomed. If the given offset falls outside of the
 * length, FALSE is returned. Negative offsets are allowed
 */
const OFFSET_NONE = \cPHP\num\OFFSET_NONE;

/**
 * The offset will be wrapped until it fits within the length. Negative
 * offsets are allowed
 */
const OFFSET_WRAP = \cPHP\num\OFFSET_WRAP;

/**
 * The offset will be wrapped once. Anything past the edge after this initial
 * wrap is cut down to the edge. Negative offsets are allowed
 */
const OFFSET_RESTRICT = \cPHP\num\OFFSET_RESTRICT;

/**
 * The offset is forced to within the length. Negative offsets are NOT allowed
 */
const OFFSET_LIMIT = \cPHP\num\OFFSET_LIMIT;

/**
 * For the changeCase method, flag for setting the case to lower case
 */
const CASE_LOWER = \CASE_LOWER;

/**
 * For the changeCase method, flag for setting the case to upper case
 */
const CASE_UPPER = \CASE_UPPER;

/**
 * For the changeCase method, flag for setting the case to upper on the first letter
 */
const CASE_UCFIRST = 2;

/**
 * For the changeCase method, flag for setting the case to upper on the first
 * letter of every world
 */
const CASE_UCWORDS = 3;

/**
 * For the changeCase method, flag for setting the case to properly case any
 * all upper case words
 */
const CASE_NOSHOUT = 4;

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

/**
 * Changes the keys in an array from one value to another using an associative array
 *
 * @param Array $array The array whose keys should be translated
 * @param Array $map The lookup map to use for translation
 * @return Array Returns a new array with the keys changed
 */
function translateKeys ( array $array, array $map )
{
    $output = array();

    foreach ( $array AS $key => $value ) {

        if ( array_key_exists( $key, $map ) ) {

            $map[$key] = \cPHP\indexVal($map[$key]);

            // Don't overwrite any existing keys
            if ( array_key_exists( $map[$key], $array ) )
                $output[ $key ] = $value;
            else
                $output[ $map[$key] ] = $value;

        }
        else {
            $output[ $key ] = $value;
        }
    }

    return $output;
}

/**
 * calculates the offset based on the wrap flag
 *
 * This is generally used by array functions to wrap offsets
 *
 * @param array $array The array to use as a base for calculating the offset
 * @param Integer $offset The offset being wrapped
 * @param Integer $wrapFlag How to handle offsets that fall outside of the
 *      length of the list. Allowed values are:
 *          - \cPHP\ary::OFFSET_NONE
 *          - \cPHP\ary::OFFSET_WRAP
 *          - \cPHP\ary::OFFSET_RESTRICT
 *          - \cPHP\ary::OFFSET_LIMIT
 * @return Integer Returns the wrapped offset
 */
function calcOffset ( array $array, $offset, $wrapFlag )
{
    return \cPHP\num\offsetWrap(
            count( $array ),
            $offset,
            $wrapFlag
        );
}

/**
 * Returns the value of an element at the given offset
 *
 * @param Array $array The array to pull the offset from
 * @param Integer $offset The offset to fetch
 * @param Integer $wrapFlag How to handle offsets outside the array range
 * @return mixed
 */
function offset ( array $array, $offset, $wrapFlag = \cPHP\ary\OFFSET_RESTRICT )
{
    $offset = \cPHP\ary\calcOffset( $array, $offset, $wrapFlag );

    $sliced = array_slice( $array, $offset, 1 );

    return reset($sliced);
}

/**
 * Recursively removes all the empty values from an array
 *
 * @param array $array The array to compact
 * @param integer $flags Any valid isEmpty flags to use to determine if a value is empty
 * @return object Returns a compacted version of the current array
 */
function compact ( array $array, $flags = 0 )
{
    $flags = max( intval($flags), 0 );

    // Create the callback to apply to each sub-array
    $compact = function ( $array, &$compact ) use ( $flags ) {

        $output = array();

        foreach ( $array AS $key => $value ) {

            // Recurse in to sub arrays
            if ( is_array($value) && count($value) > 0 )
                $value = $compact( $value, $compact );

            // Add the value on to the result array only if it isn't empty
            if ( !\cPHP\isEmpty($value, $flags) )
                $output[ $key ] = $value;

        }

        return $output;

    };

    return $compact( $array, $compact );

}

/**
 * Translates an array to contain the specified keys
 *
 * If a key isn't set in the original array, it fills the array by offset.
 *
 * @param mixed $keys... The keys being filtered
 * @return array
 */
function hone ( array $array, $keys )
{
    $keys = \func_get_args();
    \array_shift($keys);
    $keys = \cPHP\ary\flatten( $keys );
    $keys = \array_unique( $keys );

    // get values in the array that do not have the required keys
    $no_keys = array_diff_key( $array, array_flip($keys) );

    $out = array();

    // Rather than using internal functions, we are looping in order to
    // preserve the order of the keys
    foreach ( $keys AS $key ) {

        if (array_key_exists($key, $array))
            $out[$key] = $array[$key];

        else if (count($no_keys) > 0)
            $out[$key] = array_shift($no_keys);
    }

    return $out;
}

?>