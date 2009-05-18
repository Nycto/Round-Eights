<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Iterator
 */

namespace cPHP\Iterator\Stream;

/**
 * Tokenizes the read value from an input stream
 */
class Tokenize implements \Iterator
{

    /**
     * The stream to tokenize
     *
     * @var \cPHP\iface\Stream\In
     */
    private $Stream;

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
     * The buffer to read the stream into while we look for the delimiter
     *
     * @var String
     */
    private $buffer;

    /**
     * Constructor...
     *
     * @param \cPHP\iface\Stream\In $stream The Input stream to tokenize
     * @param String $delim The delimiter to use when splitting the stream
     * @param Integer $bytes The number of bytes to read from the stream at a time
     */
    public function __construct ( \cPHP\iface\Stream\In $stream, $delim, $bytes = 1024 )
    {
        $this->stream = $stream;
        $this->delim = \cPHP\strval( $delim );
        $this->bytes = max( \intval( $bytes ), 1 );
    }

    /**
     * Returns the current value of the iterator
     *
     * @return String
     */
    public function current()
    {

    }

    /**
     * Increments the iterator to the next value
     *
     * @return \cPHP\Iterator\Stream\Tokenize Returns a self reference
     */
    public function next ()
    {

    }

    /**
     * Returns the key of the current value
     *
     * @return Integer
     */
    public function key ()
    {

    }

    /**
     * Returns whether the iterator currently has a valid value
     *
     * @return Boolean
     */
    public function valid ()
    {

    }

    /**
     * Restarts the iterator
     *
     * @return \cPHP\Iterator\Stream\Tokenize Returns a self reference
     */
    public function rewind ()
    {

    }

}

?>