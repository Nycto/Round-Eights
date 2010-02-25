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
     * The beginning value
     *
     * @var Mixed
     */
    private $start;

    /**
     * The ending value
     *
     * @var Mixed
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

        if ( !(is_int($start) || is_float($start)) || !(is_int($end) || is_float($end)) )
        {
            $start = (string) $start;
            $end = (string) $end;
        }

        $this->start = $start;
        $this->end = $end;
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
        return $this->current;
    }

    /**
     * Internal method for incrementing the value as a number
     *
     * @return Boolean Returns whether the end of iteration has been reached
     */
    private function nextNumber ()
    {
        if ( $this->end > $this->start )
        {
            $this->current += $this->step;
            return $this->current <= $this->end;
        }
        else
        {
            $this->current -= $this->step;
            return $this->current >= $this->end;
        }
    }

    /**
     * Internal method for incrementing the value as a string
     *
     * @return Boolean Returns whether the end of iteration has been reached
     */
    private function nextString ()
    {
        $end = ord( $this->end );
        $current = ord( $this->current );

        if ( $end > ord( $this->start ) )
        {
            $this->current = chr( $current + $this->step );
            return $current + $this->step <= $end;
        }
        else
        {
            $this->current = chr( $current - $this->step );
            return $current - $this->step >= $end;
        }
    }

    /**
     * Increments the iterator to the next value
     *
     * @return NULL
     */
    public function next ()
    {
        if ( is_int($this->start) || is_float($this->start) )
            $result = $this->nextNumber();
        else
            $result = $this->nextString();

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
        return array("start", "end", "step");
    }

}

?>