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
 * @package Stream
 */

namespace cPHP\Stream\In;

/**
 * Provides a Stream interface for from a URI string
 */
class URI implements \cPHP\iface\Stream\In
{

    /**
     * The stream resource to read from
     *
     * @var Resource
     */
    private $resource;

    /**
     * Constructor...
     *
     * @param String $uri The URI to open
     */
    public function __construct ( $uri )
    {
        $uri = \cPHP\strval( $uri );

        $this->resource = @fopen( $uri, "r" );

        if ( $this->resource === FALSE ) {
            throw new \cPHP\Exception\FileSystem\Unreadable(
                    $uri,
                    "Could not open URI for reading"
                );
        }
    }

    /**
     * Returns whether there is any more information to read from this stream
     *
     * @return Boolean
     */
    public function canRead ()
    {
        if ( !is_resource($this->resource) )
            return FALSE;

        return feof( $this->resource ) ? FALSE : TRUE;
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

        $bytes = max( intval($bytes), 0 );

        if ( $bytes == 0 )
            return "";

        return fread( $this->resource, $bytes );
    }

    /**
     * Reads the remaining data from this stream
     *
     * @return String|NULL Null is returned if there is no data available to read
     */
    public function readAll ()
    {
        if ( $this->canRead() )
            return stream_get_contents( $this->resource );
        else
            return NULL;
    }

    /**
     * Rewinds this stream back to the beginning
     *
     * @return \cPHP\iface\Stream\In Returns a self reference
     */
    public function rewind ()
    {
        if ( is_resource($this->resource) )
            fseek($this->resource, 0);

        return $this;
    }

}

?>