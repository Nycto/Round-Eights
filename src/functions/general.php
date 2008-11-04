<?php
/**
 * General functions
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package General
 */

namespace cPHP;

// Used by is_empty to define what is allowed
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

    if (is_bool($value) || is_int($value) || is_float($value) || is_string($value) || is_null($value))
        return $value;

    else if (is_array($value)) {
        if (count($value) <= 0)
            return NULL;
        else
            return reduce(current($value));
    }

    else if (is_object($value))
        return reduce(get_object_vars($value));

    else if (is_resource($value))
        return get_resource_type($value);

    else
        return NULL;

}

/**
 * Defines a constant if it hasn't already been defined
 *
 * @param string $constant The constant to define
 * @param mixed $value The constant's value
 * @return Boolean Returns whether the operation was succesful. This will return TRUE if the constant is already defined
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
function is_empty ($value, $flags = 0)
{

    $flags = max(intval(reduce($flags)), 0);

    // Use the default empty function, if we can
    // Note that empty() considers the string '0' to be empty and this function doesn't
    // empty() also thinks that a string of blank characters isn't empty
    if ( $flags == 0 && $value !== '0' && ( !is_string($value) || ( $value != "" && trim($value) != "" ) ) )
        return empty($value);

    if (is_null($value)) {
        return ($flags & cPHP::ALLOW_NULL)?FALSE:TRUE;
    }
    else if ($value === FALSE) {
        return ($flags & cPHP::ALLOW_FALSE)?FALSE:TRUE;
    }
    else if (is_string($value)) {

        if ($value == "")
            return ($flags & cPHP::ALLOW_BLANK)?FALSE:TRUE;
        else if (trim($value) == "")
            return ($flags & cPHP::ALLOW_SPACES)?FALSE:TRUE;

    }
    else if (is_numeric($value) && $value == 0) {
        return ($flags & cPHP::ALLOW_ZERO)?FALSE:TRUE;
    }
    else if (is_array($value) && count($value) <= 0) {
        return ($flags & cPHP::ALLOW_EMPTY_ARRAYS)?FALSE:TRUE;
    }

    return FALSE;
}

/**
 * Returns true if the value is boolean or empty
 *
 * @param mixed $value The value being tested
 * @param Integer $flags Any is_empty() flags
 * @return Boolean
 */
function is_vague ($value, $flags = 0)
{
    return (is_bool($value) || is_empty($value, $flags));
}

/**
 * Returns whether the value type is float, integer, string, boolean or null
 *
 * @param mixed $value The value being tested
 * @return Boolean
 */
function is_basic ( $value )
{
    return is_bool($value)
        || is_int($value)
        || is_float($value)
        || is_null($value)
        || is_string($value);
}

/**
 * Forces an array to always be returned
 *
 * @param mixed $value The value to transform in to an array
 * @return array
 */
function arrayVal ($value)
{
    return is_array($value)?$value:Array($value);
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
    $value = reduce($value);
    return intval($value) == floatval($value)?intval($value):floatval($value);
}

/**
 * Forces a value to boolean true or false
 *
 * @param boolean $value The value to force to boolean
 * @return Boolean
 */
function boolVal ($value)
{
    return $value?TRUE:FALSE;
}

/**
 * Returns the string value of a variable
 *
 * This differs from strval in that it invokes __toString if an object is given
 * and the object has that method
 *
 * @param mixed $value The value to force to a string
 * @return String
 */
function strVal ($value)
{

    // We use get_class_methods instead of method_exists to ensure that __toString is a public method
    if (is_object($value) && in_array("__toString", get_class_methods($value)))
        return ::strval( $value->__toString() );
    else
        return ::strval( reduce($value) );
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
    $className = ltrim( trim( strval( $className ) ), ":" );

    if ( is_object($value) )
        return ( $value instanceof $className ) ? TRUE : FALSE;

    $value = trim( trim( strval( $value ) ), ":" );

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

?>