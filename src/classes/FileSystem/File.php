<?php
/**
 * File System Class
 *
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
 * @package FileSystem
 */

namespace cPHP\FileSystem;

/**
 * Filesystem File class
 */
class File extends \cPHP\FileSystem
{

    /**
     * The extension of this file
     */
    private $extension;

    /**
     * The filename name of this file
     */
    private $filename;

    /**
     * Returns the path represented by this instance
     *
     * @return String The full path
     */
    public function getPath ()
    {
        if ( !$this->dirExists() && !$this->filenameExists() )
            return null;

        return
            ( $this->dirExists() ? $this->getRawDir() : "" )
            .$this->getBasename();
    }

    /**
     * Sets the path that this instance represents
     *
     * @param String $path The new path
     * @return Object Returns a self reference
     */
    public function setPath ( $path )
    {
        $path = trim(\cPHP\strval( $path ));
        $path = pathinfo( $path );

        if ( isset($path['dirname']) )
            $this->setDir($path['dirname']);
        else
            $this->clearDir();

        if ( isset($path['basename']) )
            $this->setBaseName($path['basename']);
        else
            $this->clearBaseName();

        return $this;
    }

    /**
     * Returns whether this file exists
     *
     * @return boolean
     */
    public function exists ()
    {
        return $this->isFile();
    }

    /**
     * Returns the directory as a cPHP\FileSystem\Dir object
     *
     * @return Object Returns a cPHP\FileSystem\Dir objec
     */
    public function getDir ()
    {
        return new \cPHP\FileSystem\Dir( $this->getRawDir() );
    }

    /**
     * Returns the extension, if there is one, for this file
     *
     * The extension will be returned without a leading period
     *
     * @return String|Null Returns null if no extension has been set
     */
    public function getExt ()
    {
        return $this->extension;
    }

    /**
     * Sets the extension for this file
     *
     * @param String $extension The new extension
     * @return Object Returns a self reference
     */
    public function setExt ( $extension )
    {
        $extension = trim(\cPHP\strval( $extension ));
        $extension = ltrim( $extension, "." );
        $this->extension = \cPHP\isEmpty( $extension ) ? null : $extension;
        return $this;
    }

    /**
     * Returns whether this file has an extension
     *
     * @return Boolean
     */
    public function extExists ()
    {
        return isset( $this->extension );
    }

    /**
     * Clears the extension from this file
     *
     * @return Object Returns a self reference
     */
    public function clearExt ()
    {
        $this->extension = null;
        return $this;
    }

    /**
     * Returns the filename, if there is one, for this file
     *
     * @return String|Null Returns null if no filename has been set
     */
    public function getFilename ()
    {
        return $this->filename;
    }

    /**
     * Sets the filename for this file
     *
     * @param String $filename The new filename
     * @return Object Returns a self reference
     */
    public function setFilename ( $filename )
    {
        $filename = trim(\cPHP\strval( $filename ));
        $filename = rtrim( $filename, "." );
        $this->filename = \cPHP\isEmpty( $filename ) ? null : $filename;
        return $this;
    }

    /**
     * Returns whether this file has an filename
     *
     * @return Boolean
     */
    public function filenameExists ()
    {
        return isset( $this->filename );
    }

    /**
     * Clears the filename from this file
     *
     * @return Object Returns a self reference
     */
    public function clearFilename ()
    {
        $this->filename = null;
        return $this;
    }

    /**
     * Returns the basename for this file
     *
     * The basename is the combined filename and extension. If no filename
     * has been set, this will always return null.
     *
     * @return String|Null Returns null if no filename has been set
     */
    public function getBasename ()
    {
        if ( !$this->filenameExists() )
            return null;

        if ( !$this->extExists() )
            return $this->getFilename();

        return \cPHP\str\weld(
                $this->getFilename(),
                $this->getExt(),
                "."
            );
    }

    /**
     * Sets the basename for this file
     *
     * This sets the extension and filename at once
     *
     * @param String $basename The new basename
     * @return Object Returns a self reference
     */
    public function setBasename ( $basename )
    {
        $basename = trim(\cPHP\strval( $basename ));
        $basename = pathinfo( $basename );

        // Handle filenames that start with a dot, like ".htaccess"
        if ( \cPHP\str\startsWith( $basename['basename'], "." ) ) {
            $basename = pathinfo( substr($basename['basename'], 1) );
            $basename['filename'] = ".".$basename['filename'];
        }

        if ( isset($basename['filename']) )
            $this->setFilename($basename['filename']);
        else
            $this->clearFilename();

        if ( isset($basename['extension']) )
            $this->setExt($basename['extension']);
        else
            $this->clearExt();

        return $this;
    }

    /**
     * Returns the content from this file
     *
     * @return String
     */
    public function get ()
    {
        $this->requirePath();
        $result = @file_get_contents( $this->getPath() );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable read data from file"
                );
            throw $err;
        }

        return $result;
    }

    /**
     * Sets the content in this file
     *
     * @param String $content The content to set
     * @return Object Returns a self reference
     */
    public function set ( $content )
    {
        $result = @file_put_contents( $this->getPath(), $content );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable write data to file"
                );
            throw $err;
        }

        return $this;
    }

    /**
     * Appends a chunk of content to this file
     *
     * @param String $content The content to append
     * @return Object Returns a self reference
     */
    public function append ( $content )
    {
        $result = @file_put_contents( $this->getPath(), $content, FILE_APPEND );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable write data to file"
                );
            throw $err;
        }

        return $this;
    }

    /**
     * Returns the content of this file as an array, where each line is an element
     * of the array
     *
     * @return Array
     */
    public function toArray ()
    {
        $this->requirePath();
        $result = @file( $this->getPath() );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to read data from file"
                );
            throw $err;
        }

        return $result;
    }

    /**
     * Returns the size of this file
     *
     * @return Integer
     */
    public function getSize ()
    {
        $this->requirePath();
        return filesize( $this->getPath() );
    }

    /**
     * Removes all the content from a file
     *
     * @return Object Returns a self reference
     */
    public function truncate ()
    {
        return $this->set("");
    }

    /**
     * Deletes this file from the filesystem
     *
     * @return Object Returns a self reference
     */
    public function delete ()
    {
        // We don't need to delete it if it doesn't exist
        if ( !$this->exists() )
            return $this;

        if ( !@unlink( $this->getPath() ) ) {
            $err = new \cPHP\Exception\FileSystem( $this->getPath(), "Unable to delete file" );
            throw $err;
        }

        return $this;
    }

    /**
     * Returns the mime type of this file
     *
     * @return String
     */
    public function getMimeType ()
    {
        $this->requirePath();

        $finfo = finfo_open(\FILEINFO_MIME);

        if ( $finfo === FALSE )
            throw new \cPHP\Exception\Extension( "Unable to open finfo database" );

        $result = finfo_file( $finfo, $this->getPath() );

        finfo_close( $finfo );

        if ( \cPHP\str\contains(" ", $result) )
            $result = strstr( $result, " ", TRUE );

        $result = rtrim($result, ";");

        return $result;
    }

    /**
     * Copies this file to a new location
     *
     * @param String $destination The new location for the file
     * @return Object Returns a new instance of \cPHP\FileSystem\File with the
     *      path pointing to the new file
     */
    public function copy ( $destination )
    {
        $this->requirePath();

        $destination = \cPHP\strval($destination);

        $result = @copy( $this->getPath(), $destination );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to copy file"
                );
            throw $err;
        }

        return new self( $destination );
    }

    /**
     * Moves this file to a new location
     *
     * On success, this will automatically update the path in this instance
     *
     * @param String $destination The new location for the file
     * @return Object Returns a self reference
     */
    public function move ( $destination )
    {
        $this->requirePath();

        $destination = \cPHP\strval($destination);

        $result = @rename( $this->getPath(), $destination );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to move file"
                );
            throw $err;
        }

        $this->setPath( $destination );

        return $this;
    }

}

?>