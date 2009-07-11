<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Iterator
 */

namespace h2o\Iterator\Stream;

/**
 * Tokenizes the read value from an input stream
 */
class Tokenize implements \Iterator
{

    /**
     * The stream to tokenize
     *
     * @var \h2o\iface\Stream\In
     */
    private $stream;

    /**
     * The delimiter to use when splitting the stream
     *
     * @var String
     */
    private $delim;

    /**
     * The number of bytes to read from the stream at a time
     *
     * @var Integer
     */
    private $bytes;

    /**
     * The buffer that holds any spill off characters returned while looking
     * for the next value
     *
     * @var String
     */
    private $buffer = "";

    /**
     * The current value of the iterator
     *
     * @var String
     */
    private $current;

    /**
     * The current key of the iterator
     *
     * @var Integer
     */
    private $key;

    /**
     * Constructor...
     *
     * @param \h2o\iface\Stream\In $stream The Input stream to tokenize
     * @param String $delim The delimiter to use when splitting the stream
     * @param Integer $bytes The number of bytes to read from the stream at a time
     */
    public function __construct ( \h2o\iface\Stream\In $stream, $delim, $bytes = 1024 )
    {
        $this->stream = $stream;
        $this->delim = \h2o\strval( $delim );
        $this->bytes = max( \intval( $bytes ), 1 );
    }

    /**
     * Returns the current value of the iterator
     *
     * @return String|NULL Returns NULL if there is no current value
     */
    public function current()
    {
        return $this->current;
    }

    /**
     * Returns the key of the current value
     *
     * @return Integer
     */
    public function key ()
    {
        return $this->key;
    }

    /**
     * Increments the iterator to the next value
     *
     * @return \h2o\Iterator\Stream\Tokenize Returns a self reference
     */
    public function next ()
    {
        // Update the key
        if ( isset($this->key) )
            $this->key++;
        else
            $this->key = 0;

        // Start with any spill over from the previous read
        $read = $this->buffer;

        $pos = FALSE;

        // Loop until one of the internal tests breaks
        while ( TRUE ) {

            $read = ltrim( $read, $this->delim );

            $pos = \strpos( $read, $this->delim );

            // If we found the delmiter among this set of data, then we
            // can stop reading
            if ( $pos !== FALSE )
                break;

            // If we are able to read from the stream, grab the next
            // few bytes
            if ( $this->stream->canRead() )
                $read .= $this->stream->read( $this->bytes );
            else
                break;
        }

        // If the delimiter was found
        if ( $pos !== FALSE ) {
            $this->current = substr( $read, 0, $pos );
            $this->buffer = substr( $read, $pos + strlen($this->delim) );
        }

        // If there is trailing content...
        else if ( $read !== "" ) {
            $this->buffer = "";
            $this->current = $read;
        }

        // Otherwise, there is nothing left in this stream
        else {
            $this->buffer = "";
            $this->current = null;
            $this->key = null;
        }

        return $this;
    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {
        return isset( $this->current );
    }

    /**
     * Restarts the iterator
     *
     * @return \h2o\Iterator\Stream\Tokenize Returns a self reference
     */
    public function rewind ()
    {
        // Set the up the initial values for the internal state
        $this->buffer = "";
        $this->key = null;
        $this->current = null;

        // Rewind the stream so it can be re-read
        $this->stream->rewind();

        // Queue up the first string
        $this->next();
    }

}

?>