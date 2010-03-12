<?php
/**
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
 * @package Iterator
 */

namespace r8\Iterator;

/**
 * An iterator that represents a range of values
 */
class Range implements \Iterator
{

    /**
     * The various modes this iterator can be in
     */
    const MODE_NUMERIC = 1;
    const MODE_ALPHA_LOWER = 2;
    const MODE_ALPHA_UPPER = 3;

    /**
     * The mode this iterator is operating in
     *
     * @var Integer
     */
    private $mode;

    /**
     * The beginning value
     *
     * @var Integer
     */
    private $start;

    /**
     * The ending value
     *
     * @var Integer
     */
    private $end;

    /**
     * The size of the step to take between values
     *
     * @var Integer
     */
    private $step;

    /**
     * The current value
     *
     * @var Mixed
     */
    private $current;

    /**
     * The current offset
     *
     * @var Integer
     */
    private $offset;

    /**
     * Converts an integer to a string of uppercase letters (A-Z, AA-ZZ, AAA-ZZZ, etc.)
     *
     * @author Tamas http://us3.php.net/manual/en/function.base-convert.php#96304
     * @param Integer $int
     * @return String
     */
    static public function num2alpha ( $int )
    {
        for ( $result = ""; $int >= 0; $int = intval((int)$int / 26) - 1 ) {
            $result = chr( $int % 26 + 0x41 ) . $result;
        }
        return $result;
    }

    /**
     * Convert a string of uppercase letters to an integer.
     *
     * @author Tamas http://us3.php.net/manual/en/function.base-convert.php#96304
     * @param String $string
     * @return Integer
     */
    static public function alpha2num ( $string )
    {
        $string = strtoupper( (string) $string );
        $length = strlen($string);
        $int = 0;
        for ( $i = 0; $i < $length; $i++ ) {
            $int = ($int * 26) + ord($string[$i]) - 0x40;
        }
        return $int - 1;
    }

    /**
     * Constructor...
     *
     * @param Mixed $start The starting value
     * @param Mixed $end The ending value
     * @param Integer $step The size of the step to take between values
     */
    public function __construct ( $start, $end, $step = 1 )
    {
        $start = \r8\reduce( $start );
        $end = \r8\reduce( $end );

        if ( ctype_alpha($start) && ctype_alpha($end) )
        {
            if ( ctype_upper( substr($start, 0, 1) ) )
                $this->mode = self::MODE_ALPHA_UPPER;
            else
                $this->mode = self::MODE_ALPHA_LOWER;

            $this->start = self::alpha2num( (string) $start );
            $this->end = self::alpha2num( (string) $end );
        }
        else
        {
            $this->mode = self::MODE_NUMERIC;
            $this->start = \r8\numVal( $start );
            $this->end = \r8\numVal( $end );
        }

        $this->step = (int) $step == 0 ? 1 : $step;
    }

    /**
     * Returns the key of the current value
     *
     * @return Mixed
     */
    public function key ()
    {
        return $this->offset;
    }

    /**
     * Returns the current value of the iterator
     *
     * @return Mixed Returns NULL if there is no current value
     */
    public function current()
    {
        if ( $this->mode == self::MODE_NUMERIC )
            return $this->current;
        else if ( $this->mode == self::MODE_ALPHA_LOWER )
            return strtolower( self::num2alpha( $this->current ) );
        else
            return self::num2alpha( $this->current );
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        if ( $this->end > $this->start )
        {
            $this->current += $this->step;
            $result = $this->current <= $this->end;
        }
        else
        {
            $this->current -= $this->step;
            $result = $this->current >= $this->end;
        }

        if ( !$result )
        {
            $this->current = NULL;
            $this->key = NULL;
        }
        else
        {
            $this->offset++;
        }
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return isset( $this->key );
    }

    /**
     * Restarts the iterator
     *
     * @return NULL
     */
    public function rewind ()
    {
        $this->current = $this->start;
        $this->key = 0;
    }

    /**
     * Provides a list of inner values that should be serialized
     *
     * @return Array
     */
    public function __sleep ()
    {
        return array("mode", "start", "end", "step");
    }

}

?>