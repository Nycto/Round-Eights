<?php
/**
 * File for the array object
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
 * @package Array
 */

namespace cPHP;

/**
 * Object oriented array interface
 *
 * The choice was made not to extend the ArrayObject provided by the SPL
 * because it did not support direct access to the contained iterator.
 *
 * Why call it Ary? Because "Array" is a reserved word in PHP and something
 * short was needed
 */
class Ary implements Iterator, Countable, ArrayAccess
{

    /**
     * No wrapping will be perfomed. If the given offset falls outside of the
     * length, FALSE is returned. Negative offsets are allowed
     */
    const OFFSET_NONE = cPHP::num::OFFSET_NONE;

    /**
     * The offset will be wrapped until it fits within the length. Negative
     * offsets are allowed
     */
    const OFFSET_WRAP = cPHP::num::OFFSET_WRAP;

    /**
     * The offset will be wrapped once. Anything past the edge after this initial
     * wrap is cut down to the edge. Negative offsets are allowed
     */
    const OFFSET_RESTRICT = cPHP::num::OFFSET_RESTRICT;

    /**
     * The offset is forced to within the length. Negative offsets are NOT allowed
     */
    const OFFSET_LIMIT = cPHP::num::OFFSET_LIMIT;

    /**
     * The iterator being used in this instance
     */
    protected $array = array();

    /**
     * Class functions
     */

    /**
     * Class method for creating a new instance
     *
     * @param mixed The array to use for this instance
     * @return object Returns a new array object
     */
    static public function create( $array = array() )
    {
        return new self( $array );
    }

    /**
     * Returns a new array filled with a range, per the array_range() function
     *
     * @param mixed $low The low value
     * @param mixed $high The high value
     * @param Integer $step The value to increment by at every step
     * @return Object Returns a new array object
     */
    static public function range ( $low, $high, $step = 1 )
    {
        if ( func_num_args() > 2 )
            return new self ( range( $low, $high, $step ) );
        else
            return new self ( range( $low, $high ) );
    }

    /**
     * Returns whether a value is an array or a traversable object
     *
     * @param mixed $value The value to test
     * @return Boolean
     */
    static public function is ( $value )
    {
        return is_array( $value ) || $value instanceof Traversable;
    }

    /**
     * Member functions
     */

    /**
     * Constructor
     *
     * @param mixed The data to load in to this instance
     *      If an array is given, it will be copied in to this value
     *      If a cPHP::Ary is given, it's value will be extracted and imported
     *      If an instance of Traversable is given, it will be converted to an array
     *      If any other value is given, it will be wrapped in an array and imported
     */
    public function __construct( $array = array() )
    {
        if ( is_array($array) )
            $this->array = $array;

        else if ( $array instanceof self )
            $this->array = $array->get();

        else if ( $array instanceof Traversable )
            $this->array = iterator_to_array( $array );

        else
            $this->array = array( $array );
    }

    /**
     * calculates the offset based on the wrap flag
     *
     * This is generally used by array functions to wrap offsets
     *
     * @param Integer $offset The offset being wrapped
     * @param Integer $wrapFlag How to handle offsets that fall outside of the
     *      length of the list. Allowed values are:
     *          - cPHP::Ary::OFFSET_NONE
     *          - cPHP::Ary::OFFSET_WRAP
     *          - cPHP::Ary::OFFSET_RESTRICT
     *          - cPHP::Ary::OFFSET_LIMIT
     * @return Integer Returns the wrapped offset
     */
    public function calcOffset ($offset, $wrapFlag)
    {
        return cPHP::num::offsetWrap(
                count( $this->array ),
                $offset,
                $wrapFlag
            );
    }

    /**
     * Counts the number of elements in this array
     *
     * @return Integer
     */
    public function count ()
    {
        return count( $this->array );
    }

    /**
     * Navigation Methods
     */

    /**
     * Returns the value of the element at the current pointer
     *
     * @return mixed
     */
    public function current ()
    {
        return current( $this->array );
    }

    /**
     * Returns the key of the current pointer
     *
     * @return mixed
     */
    public function key ()
    {
        return key( $this->array );
    }

    /**
     * Increments the internal array pointer
     *
     * @return Object Returns a self reference
     */
    public function next ()
    {
        next( $this->array );
        return $this;
    }

    /**
     * Sets the internal array pointer to a specific offset
     *
     * @return Object Returns a self reference
     */
    public function prev ()
    {
        prev( $this->array );
        return $this;
    }

    /**
     * Resets the internal array pointer
     *
     * @return Object Returns a self reference
     */
    public function rewind ()
    {
        reset( $this->array );
        return $this;
    }

    /**
     * Returns whether the current value is valid
     *
     * @return Object Returns a self reference
     */
    public function valid ()
    {
        return !is_null( key( $this->array ) );
    }

    /**
     * Sets the internal array pointer to a specific offset
     *
     * @param Integer $offset The offset to seek to
     * @param Integer $wrapFlag How to handle offsets outside the array range
     * @return Object Returns a self reference
     */
    public function seek ( $offset, $wrapFlag = FALSE )
    {

        $offset = $this->calcOffset( $offset, $wrapFlag );

        $count = count( $this->array ) - 1;

        // escape from the easy outs
        if ($offset == 0) {
            reset( $this->array );
            return $this;
        }

        else if ($offset == $count) {
            end( $this->array );
            return $this;
        }

        // Get the position of the current pointer
        $pointer = $this->pointer();

        // If we are already at our destination...
        if ($pointer == $offset)
            return $this;

        // If the point we are seeking to is closer to the beginning than it is
        // to the end or to the current pointer position, seek from the start
        if ($offset < abs($pointer - $offset) && $offset < abs($count - $offset)) {
            reset( $this->array );
            $pointer = 0;
        }

        // If the point we are seeking to is closer to the end than the start or
        // the current pointer position, seek from the end
        else if (abs($count - $offset) < abs($pointer - $offset)) {
            end( $this->array );
            $pointer = $count;
        }

        // If we are seeking backward
        if ($pointer > $offset) {

            // seek to the before final point
            for ($pointer--; $pointer >= $offset; $pointer--)
                prev( $this->array );

        }

        // If we are seeking forward
        else {

            // seek to the final point
            for ($pointer++; $pointer <= $offset; $pointer++)
                next( $this->array );

        }

        return $this;

    }

    /**
     * Sets the internal array pointer to the end of the current array
     *
     * @return Object Returns a self reference
     */
    public function end ()
    {
        end( $this->array );
        return $this;
    }

    /**
     * Array Accessor methods
     */

    /**
     * Returns whether a specific index exists in this array
     *
     * @param mixed $index The index being tested
     * @return Boolean
     */
    public function offsetExists( $index )
    {
        return array_key_exists( $index, $this->array );
    }

    /**
     * Returns the value of a specific index in this array
     *
     * @param mixed $index The index to fetch the value of
     * @return mixed This will return the value, or NULL if the index doesn't exist
     */
    public function offsetGet( $index )
    {
        if ( array_key_exists( $index, $this->array ) )
            return $this->array[ $index ];
        else
            return NULL;
    }

    /**
     * Sets the value of a specific index in this array
     *
     * @param mixed $index The index being set
     * @param mixed $value The new value
     * @return object Returns a self reference
     */
    public function offsetSet( $index, $value )
    {
        // This handles the append shortcut, ie $ary[] = "value"
        if ( is_null($index) )
            $this->array[] = $value;

        else
            $this->array[ $index ] = $value;

        return $this;
    }

    /**
     * Unsets the value of a specific index in this array
     *
     * @param mixed $index The index being unset
     * @return object Returns a self reference
     */
    public function offsetUnset( $index )
    {
        if ( array_key_exists( $index, $this->array ) )
            unset( $this->array[ $index ] );
        return $this;
    }

    /**
     * Offset methods
     */

    /**
     * Returns the offset of a given key
     *
     * This will throw an exception if the key is not found in the array
     *
     * @return Integer Returns the offset of the key
     */
    public function keyOffset ( $key )
    {
        $key = reduce($key);
        if ( !array_key_exists($key, $this->array) )
            throw new ::cPHP::Exception::Argument(0, "Key", "Key does not exist in the given Array");

        $keyList = array_keys( $this->array );

        return array_search($key, $keyList);
    }

    /**
     * Returns the offset of the pointer
     *
     * @return Integer|Boolean Returns the offset, or FALSE if the array is empty
     */
    public function pointer ()
    {
        if (count( $this->array ) <= 0)
            return FALSE;

        return $this->keyOffset( $this->array, key($this->array) );
    }

    /**
     * Returns the value of the element at the given offset
     *
     * @param Integer $offset The offset to fetch
     * @param Integer $wrapFlag How to handle offsets outside the array range
     * @return mixed
     */
    public function offset ( $offset, $wrapFlag = self::OFFSET_RESTRICT )
    {
        $offset = $this->calcOffset( $offset, $wrapFlag );

        if ($offset === FALSE)
            throw new OffsetError(1, "Offset", "Invalid offset");

        $sliced = array_slice( $this->array, $offset, 1 );

        return reset($sliced);
    }

    /**
     * Key functions
     */

    /**
     * Returns whether a key exists in this array
     *
     * @param mixed $key
     * @return Boolean
     */
    public function keyExists ( $key )
    {
        return array_key_exists( $key, $this->array );
    }

    /**
     * List manipulation functions
     */

    /**
     * Appends a value to the end of this array
     *
     * @param mixed $value The value to append
     * @return Object Returns a self reference
     */
    public function push ( $value )
    {
        $this->array[] = $value;
        return $this;
    }

    /**
     * Pops the value off the end of this array
     *
     * @param Boolean $get Whether to return the popped value, or a self reference
     * @return Object Returns a self reference
     */
    public function pop ( $get = FALSE )
    {
        if ( $get )
            return array_pop( $this->array );

        array_pop( $this->array );
        return $this;
    }

    /**
     * Prepends a value to the beginning of this array
     *
     * @param mixed $value The value to prepend
     * @return Object Returns a self reference
     */
    public function unshift ( $value )
    {
        array_unshift( $this->array, $value );
        return $this;
    }

    /**
     * Shifts the value off the beginning of this array
     *
     * @param Boolean $get Whether to return the shifted value, or a self reference
     * @return mixed Returns a self reference or the shifted value
     */
    public function shift ( $get = FALSE )
    {
        if ( $get )
            return array_shift( $this->array );

        array_shift( $this->array );
        return $this;
    }

    /**
     *
     */

    /**
     * Returns the raw array contained in this instance
     *
     * @return Array
     */
    public function get()
    {
        return $this->array;
    }

    /**
     * Returns an array of the keys in this instance
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function keys ()
    {
        return new self( array_keys( $this->array ) );
    }

    /**
     * Returns an array of the values in this instance
     *
     * @return Object Returns a cPHP::Ary object
     */
    public function values ()
    {
        return new self( array_values( $this->array ) );
    }

    /**
     * Unsets all the values from this array
     *
     * @return Object Returns a self reference
     */
    public function clear ()
    {
        $this->array = array();
        return $this;
    }

    /**
     * Returns whether the given value is contained within the array
     *
     * @param mixed $value
     * @return Boolean
     */
    public function contains ( $value )
    {
        return in_array( $value, $this->array );
    }


    /**
     * Sorting Methods
     */

    /**
     * Sorts the array in this instance
     *
     * @param Boolean $reverse Whether to sort in to reverse order
     * @param Integer $type The type of comparison to use when sorting. Valid values are:
     *      SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     * @return Object Returns a self reference
     */
    public function sort( $reverse = FALSE, $type = SORT_REGULAR )
    {
        if ( !in_array($type, array( SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING ), TRUE) )
            throw new ::cPHP::Exception::Argument(1, "Sort Type", "Invalid Sort Type");

        if ( $reverse )
            arsort( $this->array, $type );
        else
            asort( $this->array, $type );

        return $this;
    }

    /**
     * Sorts this array by its keys
     *
     * @param Boolean $reverse Whether to sort in to reverse order
     * @param Integer $type The type of comparison to use when sorting. Valid values are:
     *      SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     * @return Object Returns a self reference
     */
    public function sortByKey( $reverse = FALSE, $type = SORT_REGULAR )
    {
        if ( !in_array($type, array( SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING ), TRUE) )
            throw new ::cPHP::Exception::Argument(1, "Sort Type", "Invalid Sort Type");

        if ( $reverse )
            ksort( $this->array, $type );
        else
            krsort( $this->array, $type );

        return $this;
    }

    /**
     * Sort this array using a natural sort algorithm
     *
     * @param Boolean $caseSensitive Whether the comparison should be case sensitive or not
     * @return Object Returns a self reference
     */
    public function naturalSort ( $caseSensitive = FALSE )
    {
        if ( $caseSensitive )
            natsort( $this->array );
        else
            natcasesort( $this->array );

        return $this;
    }

    /**
     * Sorts this array using a custom user callback, per the usort function
     *
     * @param mixed $callback A callable function/method
     * @param Boolean $assoc Whether to maintain index/value association
     * @return Object Returns a self reference
     */
    public function customSort ( $callback, $assoc = TRUE )
    {
        if ( !is_callable($callback) )
            throw new ::cPHP::Exception::Argument(0, "callback", "Must be callable");

        if ( $assoc )
            usort( $this->array, $callback );
        else
            uasort( $this->array, $callback );

        return $this;
    }

    /**
     * Reverses the elements in this array
     *
     * @param Boolean $assoc Whether to maintain index/value association
     * @return Object Returns a self reference
     */
    public function reverse ( $assoc = TRUE )
    {
        array_reverse( $this->array, booleanVal($assoc) );

        return $this;
    }

    /**
     * Randomly orders the values in the array
     *
     * @return Object Returns a self reference
     */
    public function shuffle ()
    {
       shuffle( $this->array );

       return $this;
    }

    /**
     * Sorts a list of specific keys to the top of the array
     */
    public function bubbleKeys ( array $keys )
    {

    }

    /**
     * Translates an array to contain the specified keys
     *
     * If a key isn't set in the original array, it fills the array by offset.
     *
     * @param mixed $keys... The keys being filtered
     * @return object Returns a cPHP::Ary object
     */
    public function hone ($keys)
    {
        $keys = func_get_args();
        $keys = self::create($keys)->flatten()->unique()->get();

        // get values in the array that do not have the required keys
        $no_keys = array_diff_key( $this->array, array_flip($keys) );

        $out = new ::cPHP::Ary;

        // Rather than using internal functions, we are looping in order to
        // preserve the order of the keys
        foreach ($keys AS $key) {

            if (array_key_exists($key, $this->array))
                $out[$key] = $this->array[$key];

            else if (count($no_keys) > 0)
                $out[$key] = array_shift($no_keys);
        }

        return $out;

    }

    /**
     * Changes the keys in this array from one value to another using an associative array
     *
     * @param Array $map The lookup map to use for translation
     * @return object Returns a cPHP::Ary object
     */
    public function translateKeys ( $map )
    {
        if ( !is_array($map) && !($map instanceof ::cPHP::Ary) )
            throw new ::cPHP::Exception::Argument(0, "Translation Map", "Must be an array or a cPHP::Ary object");

        if ( $map instanceof ::cPHP::Ary )
            $map = $map->get();

        $output = new ::cPHP::Ary;

        foreach ( $this->array AS $key => $value ) {

            if ( array_key_exists( $key, $map ) ) {

                // Ensure the new key is valid
                if ( is_object( $map[$key] ) || is_null( $map[$key] ) ) {
                    $err = new ::cPHP::Exception::Data($map[$key], "New Key Value", "Invalid key value");
                    $err->addData("Existing Key Value", ::cPHP::getDump($key) );
                    throw $err;
                }

                // Don't overwrite any existing keys
                if ( array_key_exists( $map[$key], $this->array ) )
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
     * Returns a version of this array where all the keys have had their case changed
     *
     * @param Integer $case
     */
    public function changeKeyCase ( $case = CASE_LOWER )
    {
        if ( $case != CASE_LOWER && $case != CASE_UPPER )
            throw new ::cPHP::Exception::Argument( 0, "Case Flag", "Must be CASE_LOWER or CASE_UPPER" );

        return new ::cPHP::Ary( array_change_key_case( $this->array, $case ) );
    }

    /**
     *
     */

    /**
     * Joins the elements in this array together using a string
     *
     * @param string $glue The string to put
     * @return String
     */
    public function implode( $glue = "" )
    {
       return implode( $glue, $this->array );
    }

    /**
     *
     */

    /**
     * Given a callback, determines if the key should be sent.
     *
     * @param mixed $callback
     * @return boolean
     */
    protected function sendKey ( $callback )
    {
        if ( is_string($callback) ) {
            $refl = new ReflectionFunction($callback);

            // We dont send the key to internal functions... this causes errors
            return !$refl->isInternal();
        }

        return TRUE;
    }

    /**
     * Applies a given callback to every element in this array and collects
     * result of each callback in to a resulting array
     *
     * @param mixed $callback The callback to apply. This must be callable
     * @return Object Returns a new array object
     */
    public function collect ( $callback )
    {

        if (!is_callable($callback))
            throw new ::cPHP::Exception::Argument(0, "Callback", "Must be callable");

        $sendKey = $this->sendKey( $callback );

        $output = new self;

        foreach ( $this->array AS $key => $value ) {

            if ( $sendKey ) {
                if ( is_object($callback) )
                    $output->offsetSet( $key, $callback->__invoke( $value, $key ) );
                else
                    $output->offsetSet( $key, call_user_func( $callback, $value, $key ) );
            }
            else {
                if ( is_object($callback) )
                    $output->offsetSet( $key, $callback->__invoke( $value ) );
                else
                    $output->offsetSet( $key, call_user_func( $callback, $value ) );
            }

        }

        return $output;
    }

    /**
     * Removes the values from an array that cause the callback to return false
     *
     * @param array $Array The array to be filtered
     */
    public function filter ( $callback )
    {

        if (!is_callable($callback))
            throw new ::cPHP::Exception::Argument(0, "Callback", "Must be callable");

        $sendKey = $this->sendKey( $callback );

        $output = new self;

        foreach( $this->array AS $key => $value ) {

            if ( $sendKey ) {

                if ( call_user_func($callback, $value, $key) )
                    $output[ $key ] = $value;

            }
            else {

                if ( call_user_func( $callback, $value) )
                    $output[ $key ] = $value;

            }

        }

        return $output;
    }

    /**
     * Applies a given callback to every element in this array
     *
     * @param mixed $callback The callback to apply. This must be callable
     * @return Object Returns a self reference
     */
    public function each ( $callback )
    {
        if (!is_callable($callback))
            throw new ::cPHP::Exception::Argument(0, "Callback", "Must be callable");

        $sendKey = $this->sendKey( $callback );

        foreach ( $this->array AS $key => $value ) {
            if ( $sendKey )
                call_user_func( $callback, $value, $key );
            else
                call_user_func( $callback, $value );
        }

        return $this;
    }

    /**
     * Reduces a multi-dimensional array down to a single-dimensional array
     *
     * Takes a multi-dimensional array and flattens it down to a single-dimensional array
     *
     * @param array $Array The array you wish to flatten
     * @return array Returns the flattened array
     */
    public function flatten ( $maxDepth = 1 )
    {

        $flatten = function ( $array, $maxDepth, $flatten ) {

            $output = array();

            foreach($array AS $key => $value) {

                // If it isn't an array, just plop it on to the end of the output
                if ( !is_array($array[$key]) && !($array[$key] instanceof cPHP::Ary) ) {

                    // There really is a good reason I do it like this... and that
                    // is "because of the way array_merge handles conflicting keys."
                    if ( !isset($output[$key]) )
                        $output[ $key ] = $value;
                    else
                        $output = array_merge( $output, array( $key => $value ) );

                }

                else if ($maxDepth <= 1) {

                    if ( $array[$key] instanceof cPHP::Ary )
                        $flat = $flatten($array[$key]->get(), 1, $flatten);
                    else
                        $flat = $flatten($array[$key], 1, $flatten);

                    $output = array_merge($output, $flat);
                    unset ($flat);
                }

                // If we have not yet reached the maximum depth, maintain this key
                else if ( $array[$key] instanceof cPHP::Ary ) {
                    $output[$key] = $array[$key]->flatten($maxDepth - 1);
                }

                else {
                    $output[$key] = $flatten($array[$key], $maxDepth - 1, $flatten);
                }

            }

            return $output;
        };

        $maxDepth = max(intval(reduce($maxDepth)), 1);

        return new self(
                $flatten( $this->array, $maxDepth, $flatten )
            );
    }

    /**
     *
     */
    public function inject ()
    {

    }

    /**
     * Recursively removes all the empty values from an array
     *
     * @param integer $flags Any valid isEmpty flags to use to determine if a value is empty
     * @return object Returns a compacted version of the current array
     */
    public function compact ( $flags = 0 )
    {
        $flags = max( intval($flags), 0 );

        $compact = function ( $array, &$compact ) use ( $flags ) {

            $output = array();

            foreach ( $array AS $key => $value ) {

                if ( $value instanceof cPHP::Ary ) {

                    if ( count($value) > 0 ) {

                        $value = $compact( $value->get(), $compact );

                        if ( !isEmpty($value, $flags) )
                            $output[ $key ] = new cPHP::Ary($value);

                    }

                }
                else {

                    if ( is_array($value) && count($value) > 0 )
                        $value = $compact( $value, $compact );

                    if ( !isEmpty($value, $flags) )
                        $output[ $key ] = $value;

                }

            }

            return $output;

        };

        return new cPHP::Ary( $compact( $this->array, $compact ) );

    }

    /**
     *
     */
    public function invoke ()
    {

    }

    /**
     *
     */
    public function pluck ()
    {

    }

    /**
     * Returns a version of the current array without duplicates
     *
     * @return object
     */
    public function unique ()
    {
        return new cPHP::Ary( array_unique($this->array) );
    }

    /**
     * Merges an array in to this array and returns the result
     *
     * This is like running array_merge( $this, $array ). Thus, the keys
     * in the argument will overwrite the keys in this array
     *
     * @param mixed $array The input data to merge in to the array
     * @return Object Returns a self reference
     */
    public function merge ( $array )
    {
        return new self( array_merge(
                $this->array,
                self::create( $array )->get()
            ) );
    }

    /**
     * Merges an array in to this array and returns the result
     *
     * This is like running "$this + $array". Meaning, the keys in the argument
     * the conflict with existing keys will be ignored
     *
     * @param mixed $array The input data to merge in to the array
     * @return Object Returns a self reference
     */
    public function add ( $array )
    {
        return new self( $this->array + self::create( $array )->get() );
    }

    /**
     *
     */

    /**
     *
     */
    public function any ()
    {

    }

    /**
     *
     */
    public function all ()
    {

    }

    /**
     * Returns the first value that causes the callback to return TRUE
     *
     * @param mixed $callback The callback to apply. This must be callable
     * @return mixed Returns the found value, or FALSE if the value couldn't be found
     */
    public function find ( $callback )
    {

        if (!is_callable($callback))
            throw new ::cPHP::Exception::Argument(0, "Callback", "Must be callable");

        $sendKey = $this->sendKey( $callback );

        foreach ( $this->array AS $key => $value ) {

            if ( $sendKey ) {
                if ( is_object($callback) )
                    $result = $callback->__invoke( $value, $key );
                else
                    $result = call_user_func( $callback, $value, $key );
            }
            else {
                if ( is_object($callback) )
                    $result = $callback->__invoke( $value );
                else
                    $result = call_user_func( $callback, $value );
            }

            if ( $result )
                return $value;

        }

        return FALSE;
    }

    /**
     * Searches the array for a value and returns it's key
     *
     * If the value appears multiple times, only the first key will be returned
     *
     * @param mixed $value The value to search for
     * @return mixed Returns the corresponding key, or FALSE if the key can't be found
     */
    public function search ( $value )
    {
        return array_search( $value, $this->array );
    }

    /**
     * Returns a copy of this array without a given value
     *
     * @param mixed $value... The value to remove
     * @return Object Returns a new cPHP::Ary instance
     */
    public function without ( $value )
    {
        $value = func_get_args();
        return new self(
                array_diff( $this->array, $value )
            );
    }

}

?>