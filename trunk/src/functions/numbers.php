<?php
/**
 * Math functions
 *
 * @package numeric
 */

namespace cPHP;

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
    $value = numVal($value);
    $lower = numVal($lower);
    $upper = numVal($upper);
    
    $inclusive = boolVal($inclusive);
    if ($upper < $lower)
        swap($upper, $lower);

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
    $value = numVal($value);
    $low = numVal($low);
    $high = numVal($high);

    if ($low == $high)
        return $low;
    else if ($high < $low)
        swap($high, $low);

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
    $value = intval(reduce($value));
    $lower = intval(reduce($lower));
    $upper = intval(reduce($upper));

    if ($lower == $upper)
        return $lower;

    if ($upper < $lower)
        swap ($upper, $lower);

    if (between($value, $lower, $upper))
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
    $value = numVal($value);
    $lower = numVal($lower);
    $upper = numVal($upper);
    $useLower = boolVal($useLower);

    if ($lower == $upper)
        return $lower;

    if ($upper < $lower)
        swap ($upper, $lower);

    if (between($value, $lower, $upper)) {
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

?>