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

namespace cPHP\FileSys;

/**
 * Filesystem Directory class
 */
class Dir extends \cPHP\FileSys implements \RecursiveIterator
{

    /**
     * For iteration, this is the directory resource
     */
    private $resource;

    /**
     * For iteration, this is the integer offset of the current element
     */
    private $pointer;

    /**
     * Used for iteration, this is the value of the current directory item
     */
    private $current;

    /**
     * Whether or not to include ".." and "." when iterating
     */
    private $includeDots = TRUE;

    /**
     * Returns a Dir instance representing the system's temporary directory
     *
     * @return Object A \cPHP\FileSys\Dir instance
     */
    static public function getTemp ()
    {
        return new self( sys_get_temp_dir() );
    }

    /**
     * Destructor...
     *
     * Ensures that the directory iteration resource is properly closed
     */
    public function __destruct ()
    {
        if ( $this->hasResource() )
            @closedir( $this->resource );
    }

    /**
     * Returns the path represented by this instance
     *
     * @return String The full path
     */
    public function getPath ()
    {
        return $this->getRawDir();
    }

    /**
     * Sets the path that this instance represents
     *
     * @param String $path The new path
     * @return Object Returns a self reference
     */
    public function setPath ( $path )
    {
        return $this->setDir( $path );
    }

    /**
     * Returns whether this file exists
     *
     * @return boolean
     */
    public function exists ()
    {
        return $this->isDir();
    }

    /**
     * Returns the basename of this directory
     *
     * @return String
     */
    public function getBasename ()
    {
        if ( $this->dirExists() )
            return basename( $this->getRawDir() );
        else
            return null;
    }

    /**
     * Returns an array of the contents of this directory
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function toArray ()
    {
        $this->requirePath();

        $resource = @opendir( $this->getPath() );

        if ( $resource === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to open directory"
                );
            throw $err;
        }

        $path = $this->getPath();

        $result = array();

        while ( ($item = readdir($resource)) !== FALSE ) {

            // Respect the include Dots option
            if ( !$this->includeDots && ( $item == "." || $item == "..") )
                continue;

            $item = $path . $item;

            if ( is_dir( $item ) )
                $result[] = new \cPHP\FileSys\Dir( $item );
            else
                $result[] = new \cPHP\FileSys\File( $item );

        }

        closedir( $resource );

        return $result;
    }

    /**
     * Creates the current directory recursively
     *
     * @return Object Returns a self reference
     */
    public function make ()
    {
        if ( !$this->dirExists() )
            throw new \cPHP\Exception\Variable("Path", "No Path has been set");

        $path = $this->getRawDir();

        if ( is_dir($path) )
            return $this;

        if ( @mkdir( $path, 0777, TRUE ) === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $path,
                    "Unable to create directory"
                );
            throw $err;
        }

        return $this;
    }

    /**
     * Deletes all the files from in a directory
     *
     * @return Object Returns a self reference
     */
    public function purge ()
    {
        $this->requirePath();

        // Create a lambda method that will be called recursively to delete subdirectories
        $callback = function ( $dir, $callback ) {

            $dir = rtrim( $dir, "/" ) ."/";

            $resource = @opendir( $dir );

            if ( $resource === FALSE ) {
                $err = new \cPHP\Exception\FileSystem( $dir, "Unable to open directory" );
                throw $err;
            }

            // Loop through everything in this directory
            while ( ($item = readdir($resource)) !== FALSE ) {

                if ( $item == "." || $item == "..")
                    continue;

                // If this is a dir, then delete everything from in it
                if ( is_dir($dir . $item) ) {
                    $callback( $dir . $item, $callback );
                    $result = @rmdir( $dir . $item );
                }

                else {
                    $result = @unlink($dir . $item);
                }

                if ( $result === FALSE ) {
                    $err = new \cPHP\Exception\FileSystem( $dir . $item, "Unable to delete path" );
                    throw $err;
                }

            }

            closedir( $resource );

        };

        $callback( $this->getPath(), $callback );

        return $this;
    }

    /**
     * Deletes this directory
     *
     * This will NOT delete a directory if it contains any files or subdirectories.
     * This is a safety feature to help you avoid blowing off your foot. If you
     * want to delete a full directory, you can chain together this method
     * with the "purge()" method.
     *
     * @return Object Returns a self reference
     */
    public function delete ()
    {
        $path = $this->getRawDir();

        if ( !is_dir( $path ) )
            return $this;

        $result = @rmdir( $path );

        if ( $result === FALSE ) {
            $err = new \cPHP\Exception\FileSystem(
                    $path,
                    "Unable to delete directory"
                );
            throw $err;
        }

        return $this;
    }

    /**
     * Returns the name of a file that doesn't yet exist in this directory
     *
     * Without a prefix or extension, this will create a filename 15 characters
     * long. If you change the $moreEntropy flag to true, the result will be
     * 25 characters
     *
     * @param String $prefix A prefix to attach to the file name
     * @param String $extension The extension the file should have
     * @param Boolean $moreEntropy Increases the length of the generated filename
     * @return Object Returns a \cPHP\FileSys\File object
     */
    public function getUniqueFile ( $prefix = null, $extension = null, $moreEntropy = FALSE )
    {
        if ( !$this->dirExists() )
            throw new \cPHP\Exception\Variable("Dir", "No directory has been set for this instance");

        $file = new \cPHP\FileSys\File;
        $file->setDir( $this->getRawDir() );

        if ( !\cPHP\isVague($extension) )
            $file->setExt( $extension );

        $prefix = \cPHP\isVague($prefix) ? null : \cPHP\strval( $prefix );
        $moreEntropy = \cPHP\boolVal( $moreEntropy );

        do {

            if ( $moreEntropy )
                $uniq = md5( uniqid(null, true) );
            else
                $uniq = substr( md5( uniqid() ), 0, 15 );

            $file->setFileName( $prefix . $uniq );

        } while ( $file->exists() );

        return $file;
    }

    /**
     * Returns whether a sub-path exists below this directory
     *
     * @param Object|String $subPath The subpath to search for
     * @return Boolean
     */
    public function contains ( $subPath )
    {
        if ( !$this->dirExists() )
            throw new \cPHP\Exception\Variable("Dir", "No directory has been set for this instance");

        $subPath = new \cPHP\FileSys\File( $subPath );
        $subPath->resolve( $this->getPath() );
        return $subPath->isDir() || $subPath->isFile();
    }

    /**
     * Returns a new FileSys instance relative to this directory
     *
     * @param Object|String $subPath The subpath to search for
     * @return Object A new filesys object
     */
    public function getSubPath ( $subPath )
    {
        if ( !$this->dirExists() )
            throw new \cPHP\Exception\Variable("Dir", "No directory has been set for this instance");

        $subPath = new \cPHP\FileSys\File( $subPath );
        $subPath->resolve( $this->getPath() );

        if ( $subPath->isDir() )
            return new \cPHP\FileSys\Dir( $subPath );
        else
            return $subPath;
    }

    /**
     * Returns whether "." and ".." will be included during iteration.
     *
     * This defaults to true
     *
     * @return Boolean
     */
    public function getIncludeDots ()
    {
        return $this->includeDots;
    }

    /**
     * Sets whether "." and ".." will be included during iteration.
     *
     * @param Boolean $include Whether to include the dots
     * @return Object Returns a self reference
     */
    public function setIncludeDots ( $include )
    {
        $this->includeDots = $include ? TRUE : FALSE;
        return $this;
    }

    /**
     * Returns whether there is a valid directory iteration resource in this instance
     *
     * @return boolean
     */
    protected function hasResource ()
    {
        return is_resource($this->resource) && get_resource_type($this->resource) == "stream";
    }

    /**
     * Used for iteration, this resets to the beginning of the directory
     *
     * @return Object Returns a self reference
     */
    public function rewind ()
    {
        // If the directory is already open, then just rewind it
        if ( $this->hasResource() ) {
            rewinddir( $this->resource );
        }

        // Otherwise, open a new resource
        else {

            $this->requirePath();

            $resource = @opendir( $this->getPath() );

            if ( $resource === FALSE ) {
                $err = new \cPHP\Exception\FileSystem(
                        $this->getPath(),
                        "Unable to open directory for iteration"
                    );
                throw $err;
            }

            $this->resource = $resource;
        }

        // Reset the internal pointer offset
        $this->pointer = -1;

        // Grab the first item from the directory
        $this->next();

        return $this;
    }

    /**
     * Used for iteration, this moves the internal iteration pointer on to the next
     * element in the directory
     *
     * @return Object Returns a self reference
     */
    public function next ()
    {
        if ( !$this->hasResource() )
            throw new \cPHP\Exception\Interaction("Iteration has not been rewound");

        $this->pointer++;

        // Continue looping if we are excluding dots and the current resource IS a dot
        do {
            $this->current = readdir( $this->resource );
        } while ( !$this->includeDots && ( $this->current == "." || $this->current == ".." ) );

        return $this;
    }

    /**
     * Used for iteration, this returns whether the iterator has reached the last element
     *
     * @return Boolean
     */
    public function valid ()
    {
        if ( !$this->hasResource() )
            return FALSE;

        // If we have reached the end of the directory content, then automaticaly close the resource
        if ( $this->current === FALSE ) {

            @closedir( $this->resource );
            $this->resource = null;

            return FALSE;
        }

        return TRUE;
    }

    /**
     * Used for iteration, this returns the current file
     *
     * @return mixed
     */
    public function current ()
    {
        if ( !$this->hasResource() )
            throw new \cPHP\Exception\Interaction("Iteration has not been rewound");

        $current = $this->getRawDir() . $this->current;

        if ( is_dir( $current ) )
            return new \cPHP\FileSys\Dir( $current );
        else
            return new \cPHP\FileSys\File( $current );
    }

    /**
     * Used for iteration, this returns the key of the current file
     *
     * @return Integer
     */
    public function key ()
    {
        if ( !$this->hasResource() )
            throw new \cPHP\Exception\Interaction("Iteration has not been rewound");

        return $this->pointer;
    }

    /**
     * Used for recursive iteration, this returns whether the current element
     * has any children that can be iterated over.
     *
     * @return Boolean
     */
    public function hasChildren ()
    {
        if ( !$this->hasResource() )
            throw new \cPHP\Exception\Interaction("Iteration has not been rewound");

        if ( $this->current == ".." || $this->current == "." )
            return FALSE;

        return is_dir( $this->getRawDir() . $this->current );
    }

    /**
     * Used for recursive iteration, this returns the iterator for the current element
     *
     * @return Object Returns a \cPHP\FileSys\Dir object
     */
    public function getChildren ()
    {
        if ( !$this->hasResource() )
            throw new \cPHP\Exception\Interaction("Iteration has not been rewound");

        if ( !$this->hasChildren() )
            throw new \cPHP\Exception\Interaction("Current value does not have children");

        // Grab the current item as a \cPHP\FileSys\Dir object
        $current = $this->current();

        // Import the 'includeDots' setting
        $current->setIncludeDots( $this->includeDots );

        return $current;
    }

}

?>