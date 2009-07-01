<?php
/**
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
 * @package Stream
 */

namespace h2o\Stream\In;

/**
 * Provides a Stream interface for a String
 */
class String implements \h2o\iface\Stream\In
{

    /**
     * The string being streamed
     *
     * @var String
     */
    private $string;

    /**
     * Caches the length of the string so it isn't calculated each time it's needed
     *
     * @var Integer
     */
    private $length;

    /**
     * The internal position of the read
     *
     * @var Integer
     */
    private $pointer = 0;

    /**
     * Constructor...
     *
     * @param String $string The string to stream
     */
    public function __construct ( $string )
    {
        $this->string = \h2o\strval($string);
        $this->length = strlen( $this->string );
    }

    /**
     * Returns whether there is any more information to read from this stream
     *
     * @return Boolean
     */
    public function canRead ()
    {
        return $this->pointer < $this->length;
    }

    /**
     * Reads a given number of bytes from this stream
     *
     * @param Integer $bytes The number of bytes to read
     * @return String|NULL Null is returned if there is no data available to read
     */
    public function read ( $bytes )
    {
        if ( !$this->canRead() )
            return NULL;

        $bytes = intval( $bytes );

        $result = substr( $this->string, $this->pointer, $bytes );

        $this->pointer += $bytes;

        return $result;
    }

    /**
     * Reads the remaining data from this stream
     *
     * @return String
     */
    public function readAll ()
    {
        if ( !$this->canRead() )
            return NULL;

        if ( $this->pointer == 0 )
            $result = $this->string;
        else
            $result = substr( $this->string, $this->pointer );

        $this->pointer = $this->length;

        return $result;
    }

    /**
     * Rewinds this stream back to the beginning
     *
     * @return \h2o\iface\Stream\In Returns a self reference
     */
    public function rewind ()
    {
        $this->pointer = 0;
        return $this;
    }

}

?>