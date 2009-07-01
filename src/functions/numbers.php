<?php
/**
 * Math functions
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package numeric
 */

namespace h2o\num;

/**
 * Offset wrapping flag. No wrapping will be perfomed. If the given offset falls outside of the
 * length, FALSE is returned. Negative offsets are allowed
 */
const OFFSET_NONE = 1;

/**
 * Offset wrapping flag. The offset will be wrapped until it fits within the length. Negative
 * offsets are allowed
 */
const OFFSET_WRAP = 2;

/**
 * Offset wrapping flag. The offset will be wrapped once. Anything past the edge after this initial
 * wrap is cut down to the edge. Negative offsets are allowed
 */
const OFFSET_RESTRICT = 3;

/**
 * Offset wrapping flag. The offset is forced to within the length. Negative offsets are NOT allowed
 */
const OFFSET_LIMIT = 4;

/**
 * Returns boolean whether a number is positive
 *
 * This function is most useful as a callback
 *
 * @param Integer|Float $number
 * @return Boolean Returns boolean whether the given argument is positive
 */
function positive ($number)
{
    return $number > 0?TRUE:FALSE;
}

/**
 * Returns boolean whether a number is negative
 *
 * This function is most useful as a callback
 *
 * @param Integer|Float $number
 * @return Boolean Returns boolean whether the given argument is negative
 */
function negative ($number)
{
    return $number < 0?TRUE:FALSE;
}

/**
 * Negates a value
 *
 * This function is most useful as a callback
 *
 * @param Integer|Float $number
 * @return Boolean Returns the negated value of the given number
 */
function negate ($number)
{
    return -1 * $number;
}

/**
 * Determines if a number falls within a given range
 *
 * @param Integer|Float $value The number to test
 * @param Integer|Float $lower The lower limit
 * @param Integer|Float $upper The upper limit
 * @param Boolean $inclusive Whether the lower and upper limits should be included in the range
 * @return Boolean Returns whether the given value is between the given limits
 */
function between ($value, $lower, $upper, $inclusive = TRUE)
{
    $value = \h2o\numVal($value);
    $lower = \h2o\numVal($lower);
    $upper = \h2o\numVal($upper);

    $inclusive = \h2o\boolVal($inclusive);
    if ($upper < $lower)
        \h2o\swap($upper, $lower);

    if ($inclusive && ($value < $lower || $value > $upper))
        return FALSE;

    else if (!$inclusive && ($value <= $lower || $value >= $upper))
        return FALSE;

    return TRUE;
}

/**
 * Restricts a numeric value to a given range
 *
 * If the restricted value falls outside the set limits, the value is snapped
 * to the appropriate limit.
 *
 * @param Integer|Float $value The number to limit
 * @param Integer|Float $low The lower limit to enforce on the given number
 * @param Integer|Float $high The upper limit to enforce on the given number
 *
 * @return Integer|Float If the given value falls outside the defined range, the appropriate limit is returned
 */
function limit ($value, $low, $high)
{
    $value = \h2o\numVal($value);
    $low = \h2o\numVal($low);
    $high = \h2o\numVal($high);

    if ($low == $high)
        return $low;
    else if ($high < $low)
        \h2o\swap($high, $low);

    return max( min($value, $high), $low);
}

/**
 * Restricts a numeric value to a range, but wraps the number if it falls outside
 * that range.
 *
 * If the restricted value falls outside of the set limits, the distance from
 * the outside of the range is calculated and applied iteratively so the number
 * is within the range.
 *
 * @param Integer $value The value to be wrapped
 * @param Integer $lower The lower limit of the wrap range
 * @param Integer $upper The upper limit of the wrap range
 *
 * @return Integer Returns the value, wrapped to within the given range
 */
function intWrap ($value, $lower, $upper)
{
    $value = intval( \h2o\reduce($value) );
    $lower = intval( \h2o\reduce($lower) );
    $upper = intval( \h2o\reduce($upper) );

    if ($lower == $upper)
        return $lower;

    if ($upper < $lower)
        \h2o\swap ($upper, $lower);

    if ( \h2o\num\between($value, $lower, $upper) )
        return $value;

    $delta = $upper - $lower + 1;

    if ($value > $upper) {
        $distance = $value - $upper - 1;
        return ($distance % $delta) + $lower;
    }
    else {
        $distance = $lower - $value - 1;
        return $upper - ($distance % $delta);
    }
}

/**
 * Keeps a value within a range by wrapping values that fall outside the boundaries.
 *
 * Unlike intWrap, this function handles float values. However, because of this,
 * it means that the limits are considered equal.
 *
 * @param Integer|Float $value The value to be wrapped
 * @param Integer|Float $lower The lower limit of the wrap range
 * @param Integer|Float $upper The upper limit of the wrap range
 * @param Boolean $useLower Because the limits are equal, this toggles whether the upper or lower limit will be returned if the value becomes equal to one of them.
 *
 * @return Integer|Float Returns the value, wrapped to within the given range
 */
function numWrap ($value, $lower, $upper, $useLower = TRUE)
{
    $value = \h2o\numVal($value);
    $lower = \h2o\numVal($lower);
    $upper = \h2o\numVal($upper);
    $useLower = \h2o\boolVal($useLower);

    if ($lower == $upper)
        return $lower;

    if ($upper < $lower)
        \h2o\swap ($upper, $lower);

    if ( \h2o\num\between($value, $lower, $upper) ) {
        $out = $value;
    }
    else {

        $delta = $upper - $lower;

        if ($value < $lower) {
            $distance = $lower - $value;

            if ($distance > $delta)
                $distance -= floor ($distance / $delta) * $delta;

            $out = $upper - $distance;
        }
        else {
            $distance = $value - $upper;

            if ($distance > $delta)
                $distance -= floor ($distance / $delta) * $delta;

            $out = $lower + $distance;
        }
    }

    if ($useLower && $out == $upper)
        return $lower;

    else if (!$useLower && $out == $lower)
        return $upper;

    return $out;
}

/**
 * calculates the offset based on the wrap flag
 *
 * This is generally used by array functions to wrap offsets
 *
 * @param Integer $length Starting from 1 (not 0), the length of the list being wrapped around
 * @param Integer $offset The offset being wrapped
 * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
 * @return Integer|Boolean Returns the wrapped offset. Returns FALSE on failure
 */
function offsetWrap ($length, $offset, $wrapFlag)
{

    $length = intval( $length );

    if ( $length <= 0 )
        throw new \h2o\Exception\Index($offset, "Offset", "List is empty");

    $offset = intval( \h2o\reduce($offset) );

    switch ($wrapFlag) {

        default:
            throw new \h2o\Exception\Argument(2, "wrapFlag", "Invalid offset wrap flag");

        case \h2o\num\OFFSET_NONE:
            if ( !\h2o\num\between($offset, 0 - $length, $length - 1) )
                throw new \h2o\Exception\Index($offset, "Offset", "Offset is out of bounds");

            else if ($offset >= 0)
                return $offset;

            else
                return $length + $offset;

        case \h2o\num\OFFSET_WRAP:
            return \h2o\num\intWrap($offset, 0, $length - 1);

        case FALSE:
        case \h2o\num\OFFSET_RESTRICT:
            $offset = \h2o\num\limit($offset, 0 - $length, $length - 1);
            if ($offset < 0)
                $offset = $length + $offset;
            return $offset;

        case \h2o\num\OFFSET_LIMIT:
            return \h2o\num\limit($offset, 0, $length - 1);
    }

}

?>