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
 * Provides a Stream interface for reading a File
 */
class File implements \cPHP\iface\Stream\In
{

    /**
     * The file resource to read from
     *
     * @var Resource
     */
    private $resource;

    /**
     * Constructor...
     *
     * @param \cPHP\FileSys\File $file The file being opened
     */
    public function __construct ( \cPHP\FileSys\File $file )
    {
        $file->requirePath();

        if ( !$file->isReadable() ) {
            throw new \cPHP\Exception\FileSystem\Unreadable(
                    $file->getPath(),
                    "File is not readable"
                );
        }

        $this->resource = @fopen( $file->getPath(), "r" );

        if ( $this->resource === FALSE ) {
            throw new \cPHP\Exception\FileSystem\Unreadable(
                    $file->getPath(),
                    "Could not open file for reading"
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

    }

    /**
     * Rewinds this stream back to the beginning
     *
     * @return \cPHP\iface\Stream\In Returns a self reference
     */
    public function rewind ()
    {

    }

}

?>