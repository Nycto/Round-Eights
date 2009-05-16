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

namespace cPHP\Stream\Out;

/**
 * Provides a Stream interface for a URI string
 */
class URI implements \cPHP\iface\Stream\Out
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
     * @param Boolean $append Whether to append to the end of the URI
     */
    public function __construct ( $uri, $append = FALSE )
    {
        $uri = \cPHP\strval( $uri );

        $this->resource = @fopen(
                $uri,
                $append ? "a" : "w"
            );

        if ( $this->resource === FALSE ) {
            throw new \cPHP\Exception\FileSystem\Permissions(
                    $uri,
                    "Could not open URI for writing"
                );
        }
    }

    /**
     * Destructor...
     *
     * @return null
     */
    public function __destruct ()
    {
        $this->close();
    }

    /**
     * Closes this resource
     *
     * @return \cPHP\iface\Stream\In\URI Returns a self reference
     */
    public function close ()
    {
        if ( is_resource($this->resource) ) {
            fclose( $this->resource );
            $this->resource = null;
        }

        return $this;
    }

    /**
     * Writes a string of data to this stream
     *
     * @param String $data The string of data to to write to this stream
     * @return \cPHP\Stream\Out\URI Returns a self reference
     */
    public function write ( $data )
    {
        $data = \cPHP\strval( $data );

        if ( is_resource($this->resource) )
            fwrite( $this->resource, $data );

        return $this;
    }

}

?>