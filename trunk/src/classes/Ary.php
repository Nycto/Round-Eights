<?php
/**
 * File for the array object
 *
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
     * No wrapping will be perfomed. If the given offset falls outside of the length, FALSE is returned. Negative offsets are allowed
     */
    const OFFSET_NONE = 1;
    
    /**
     * The offset will be wrapped until it fits within the length. Negative offsets are allowed
     */
    const OFFSET_WRAP = 2;
    
    /**
     * The offset will be wrapped once. Anything past the edge after this initial wrap is cut down to the edge. Negative offsets are allowed
     */
    const OFFSET_RESTRICT = 3;
    
    /**
     * The offset is forced to within the length. Negative offsets are NOT allowed
     */
    const OFFSET_LIMIT = 4;
    
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
        if ( is_array($array) )
            return new self( $array );
        
        else if ( $array instanceof self )
            return $array;
        
        else
            return new self( array( $array ) );
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
     * Member functions
     */
    
    /**
     * Constructor
     *
     * @param Array The array to use for this instance
     */
    public function __construct( array $array = array() )
    {
        
        if ( func_num_args() > 0 )
            $this->array = $array;
        
    }
    
    /**
     * calculates the offset based on the wrap flag
     *
     * This is generally used by array functions to wrap offsets
     *
     * @param Integer $offset The offset being wrapped
     * @param Integer $wrapFlag How to handle offsets that fall outside of the length of the list.
     *      Allowed values are: cArray::OFFSET_NONE, cArray::OFFSET_WRAP, cArray::OFFSET_RESTRICT, cArray::OFFSET_LIMIT
     * @return Integer Returns the wrapped offset
     */
    public function calcOffset ($offset, $wrapFlag)
    {
        $length = count( $this->array );
    
        $offset = intval( reduce($offset) );
        
        switch ($wrapFlag) {
            
            default:
                throw new ArgumentError(1, "wrapFlag", "Invalid wrap type");
            
            case self::OFFSET_NONE:
                if (!between($offset, 0 - $length, $length - 1))
                    throw new ArgumentError(0, "wrapFlag", "Invalid offset");
                
                else if ($offset >= 0)
                    return $offset;
                
                else
                    return $length + $offset;
    
            case self::OFFSET_WRAP:
                return intwrap($offset, 0, $length - 1);
    
            case FALSE:
            case self::OFFSET_RESTRICT:
                $offset = limit($offset, 0 - $length, $length - 1);
                if ($offset < 0)
                    $offset = $length + $offset;
                return $offset;
    
            case self::OFFSET_LIMIT:
                return limit($offset, 0, $length - 1);
        }
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
        $pointer = array_pointer_offset($Array);
    
        // If we are already at our destination...
        if ($pointer == $offset)
            return current($Array);
    
        // If we the point we are seeking to is closer to the beginning than it is to the end or to the pointer, seek from the start
        if ($offset < abs($pointer - $offset) && $offset < abs($end - $offset)) {
            reset($Array);
            $pointer = 0;
        }
    
        // If the point we are seeking to is closer to the end than the start or the current pointer, seek from the end
        else if (abs($end - $offset) < abs($pointer - $offset)) {
            end($Array);
            $pointer = $end;
        }
    
        // If we are seeking backward
        if ($pointer > $offset) {
    
            // seek to JUST before final point
            for ($pointer--; $pointer > $offset; $pointer--)
                prev($Array);
    
            return prev($Array);
    
        }
    
        // If we are seeking forward
        else {
    
            // seek to JUST before final point
            for ($pointer++; $pointer < $offset; $pointer++)
                next($Array);
    
            return next($Array);
    
        }
    

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
     * Returns the current offset
     *
     * @return Integer|Boolean Returns the offset, or FALSE if the array is empty
     */
    public function getOffset ()
    {
        if (count($Array) <= 0)
            return FALSE;
    
        return array_key_offset($Array, key($Array));
    }
    
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
            throw new ArgumentError(0, "Key", "Key does not exist in the given Array");
    
        $Array = array_keys($Array);
    
        return array_search($key, $Array);
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
     * Sorting Methods
     */
    
    /**
     * Sorts the array in this instance
     *
     * @param Boolean $assoc Whether to preserve the index/value relationship
     * @param Boolean $reverse Whether to sort in to reverse order
     * @param Integer $type The type of comparison to use when sorting. Valid values are:
     *      SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING
     * @return Object Returns a self reference
     */
    public function sort( $assoc = TRUE, $reverse = FALSE, $type = SORT_REGULAR )
    {
        if ( !in_array($type, array( SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING )) )
            throw new ArgumentError(2, "Sort Type", "Invalid Sort Type");
        
        if ( !$assoc ) {
            
            if ( $reverse )
                sort( $this->array, $type );
            else
                rsort( $this->array, $type );
            
        }
        else {
            
            if ( $reverse )
                asort( $this->array, $type );
            else
                arsort( $this->array, $type );
            
        }
        
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
        if ( !in_array($type, array( SORT_REGULAR, SORT_NUMERIC, SORT_STRING, SORT_LOCALE_STRING )) )
            throw new ArgumentError(2, "Sort Type", "Invalid Sort Type");
        
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
            throw new ArgumentError(0, "callback", "Must be callable");
        
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
     * 
     */
    public function hone ()
    {
        
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
     * @return Object Returns a cArray object
     */
    public function keys ()
    {
        return new self( array_keys( $this->array ) );
    }
    
    /**
     * Returns an array of the values in this instance
     * 
     * @return Object Returns a cArray object
     */
    public function values ()
    {
        return new self( array_values( $this->array ) );
    }
    
    /**
     * Selects a random value from the array and returns it
     *
     * @return mixed
     */
    public function random ()
    {
        
    }
    
    
    /**
     *
     */
    
    /**
     * Applies a given callback to every element in this array
     *
     * @param mixed $callback The callback to apply. This must be callable
     * @param Integer $flags Flags to adjust the internal functioning. Valid flags are:
     *      cArray::RECURSIVE, cArray::LEFT_ARGS, cArray::SEND_KEY
     * @param mixed $args... Any arguments to send to the callback
     * @return Object Returns a self reference
     */
    public function map ( $callback, $flags = 0 )
    {
    }
    
    /**
     *
     */
    public function walk ()
    {
        
    }
    
    /**
     *
     */
    public function flatten ( $maxDepth = 1 )
    {
        
    }
    
    /**
     *
     */
    public function inject ()
    {
        
    }
    
    /**
     *
     */
    public function compact ()
    {
        
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
     */
    public function find ()
    {
        
    }
    
    
}

?>