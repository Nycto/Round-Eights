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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Stream
 */

namespace r8\Stream\Out;

/**
 * Provides a Stream interface for a URI string
 */
class URI implements \r8\iface\Stream\Out
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
        $uri = \r8\strval( $uri );

        $this->resource = @fopen(
                $uri,
                $append ? "a" : "w"
            );

        if ( $this->resource === FALSE ) {
            throw new \r8\Exception\FileSystem\Permissions(
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
     * @return \r8\iface\Stream\In\URI Returns a self reference
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
     * @return \r8\Stream\Out\URI Returns a self reference
     */
    public function write ( $data )
    {
        $data = \r8\strval( $data );

        if ( is_resource($this->resource) )
            fwrite( $this->resource, $data );

        return $this;
    }

}

?>