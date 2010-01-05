<?php
/**
 * General functions
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
 * @copyright Copyright 2009, James Frasca, All Rights Reserved
 * @package General
 */

namespace r8;

// Used by isEmpty to define what is allowed
const ALLOW_NULL = 1;
const ALLOW_FALSE = 2;
const ALLOW_ZERO = 4;
const ALLOW_BLANK = 8;
const ALLOW_SPACES = 16;
const ALLOW_EMPTY_ARRAYS = 32;

/**
 * Swaps the values of two variables
 *
 * Takes two values. Sets the value of the first to that of the second
 * and the value of the second to that of the first.
 *
 * @param mixed $one The first variable
 * @param mixed $two The second variable
 */
function swap(&$one, &$two)
{
    $temp = $one;
    $one = $two;
    $two = $temp;
}

/**
 * Takes any kind of variable and flattens it down to a number, a boolean value, or a string.
 *
 * When you want to make sure a variable is a flat value, feed it in to this. It will analyze
 * the type of variable and return the best value for it.
 *
 * @param mixed $Value Any kind of variable
 * @return NULL|boolean|int|float|string A usable version of the input
 */
function reduce ($value)
{
    switch ( gettype($value) ) {
        case 'boolean':
        case 'integer':
        case 'double':
        case 'string':
        case 'NULL':
            return $value;

        case 'array':
            return empty($value) ? NULL : \r8\reduce( current($value) );

        case 'object':
            return \r8\reduce( get_object_vars($value) );

        case 'resource':
            return get_resource_type($value);

        // @codeCoverageIgnoreStart
        default:
            return NULL;
        // @codeCoverageIgnoreEnd
    }
}

/**
 * Defines a constant if it hasn't already been defined
 *
 * @param string $constant The constant to define
 * @param mixed $value The constant's value
 * @return Boolean Returns whether the operation was succesful. This will return
 *      TRUE if the constant is already defined
 */
function defineIf ($constant, $value)
{
    return defined($constant)?TRUE:define($constant, $value);
}

/**
 * Returns boolean whether a value is empty or not
 *
 * The built in empty function has limitations: You can only give it a variable.
 * This also allows you to define exactly what empty is in any circumstance
 *
 * By default, empty is:
 * NULL
 * FALSE
 * 0
 * Blank Strings
 * A String containing only spaces
 * Arrays without any values
 *
 * Note that a string containing the character "0" is NOT considered empty via this function
 *
 * @param mixed $value The value you are testing
 * @param integer $flags Any flags to alter what is considered empty
 * @return Boolean
 */
function isEmpty ($value, $flags = 0)
{
    $flags = max( (int) $flags, 0 );

    // Use the default empty function, if we can
    // Note that empty() considers the string '0' to be empty and this function doesn't
    // empty() also thinks that a string of blank characters isn't empty
    if ( $flags == 0 && $value !== '0' && ( !is_string($value) || ( $value != "" && trim($value) != "" ) ) )
        return empty($value);

    if (is_null($value)) {
        return ($flags & \r8\ALLOW_NULL)?FALSE:TRUE;
    }
    else if ($value === FALSE) {
        return ($flags & \r8\ALLOW_FALSE)?FALSE:TRUE;
    }
    else if (is_string($value)) {

        if ($value == "")
            return ($flags & \r8\ALLOW_BLANK)?FALSE:TRUE;
        else if (trim($value) == "")
            return ($flags & \r8\ALLOW_SPACES)?FALSE:TRUE;

    }
    else if (is_numeric($value) && $value == 0) {
        return ($flags & \r8\ALLOW_ZERO)?FALSE:TRUE;
    }
    else if (is_array($value) && count($value) <= 0) {
        return ($flags & \r8\ALLOW_EMPTY_ARRAYS)?FALSE:TRUE;
    }

    return FALSE;
}

/**
 * Returns true if the value is boolean or empty
 *
 * @param mixed $value The value being tested
 * @param Integer $flags Any isEmpty() flags
 * @return Boolean
 */
function isVague ($value, $flags = 0)
{
    return (is_bool($value) || \r8\isEmpty($value, $flags));
}

/**
 * Returns whether the value type is float, integer, string, boolean or null
 *
 * @param mixed $value The value being tested
 * @return Boolean
 */
function isBasic ( $value )
{
    return is_bool($value)
        || is_int($value)
        || is_float($value)
        || is_null($value)
        || is_string($value);
}

/**
 * Forces a value to either an integer or a float, whichever is more appropriate
 *
 * @param mixed $value The value to transform to a number
 * @return integer|float
 */
function numVal ($value)
{
    if (is_int($value) || is_float($value))
        return $value;
    $value = \r8\reduce($value);
    return (int) $value == (float) $value ? (int) $value : (float) $value;
}

/**
 * Prepares a value to be used as an array index
 *
 * This will reduce the value down to a basic type, then convert it to an integer
 * or a string. The idea is to simulate what PHP does when it uses a non-standard
 * value as an array index.
 *
 * @param mixed $value The value to convert
 * @return String|Integer
 */
function indexVal ( $value )
{
    $value = \r8\reduce( $value );

    if ( is_string($value) || is_int($value) )
        return $value;

    else if ( is_float($value) || is_bool($value) )
        return (int) $value;

    else
        return (string) $value;
}

/**
 * Determines if an object or class name is an implementation of a class or interface
 *
 * This will return true if:
 *  - $value is the same class as $className
 *  - $value is a subclass of $className
 *  - $value or one of its parents implements $className
 *
 * @param Object|String $value The object or class name to test
 * @param String $className The comparison class name
 * @return Boolean
 */
function kindOf ( $value, $className )
{
    $className = ltrim( trim( (string) $className ), "\\" );

    if ( is_object($value) )
        return ( $value instanceof $className ) ? TRUE : FALSE;

    $value = trim( trim( (string) $value ), "\\" );

    if ( !class_exists($value) )
        return FALSE;

    if ( strcasecmp($value, $className) == 0 )
        return TRUE;

    if ( is_subclass_of($value, $className) )
        return TRUE;

    return in_array(
            $value,
            array_map( "strtolower", class_implements( $value ) )
        );

}

/**
 * Determines if the given object responds to a specific function
 *
 * Note that this doesn't take method overloading in to account
 *
 * @param Object $object The instance being tested
 * @param String $function The function to test
 * @return Boolean
 */
function respondTo ($object, $function)
{
    if (!is_object($object))
        return FALSE;

    $function = (string) $function;

    return in_array( $function, get_class_methods($object) );
}

?>