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
 * @package FileSystem
 */

namespace r8;

/**
 * The base filesystem class
 */
abstract class FileSys
{

    /**
     * The directory of the current path
     *
     * @var String
     */
    protected $dir;

    /**
     * Basic path resolution method that operates on a string. This will take
     * a string and resolve any repeated slashes (//), dots (.) or double dots (..).
     *
     * This does nothing to try and resolve relative paths.
     *
     * The path that this method returns will only contain forward slashes
     *
     * @param String $path The path to resolve
     * @return String Returns the resolved path
     */
    static public function resolvePath ( $path )
    {
        $path = trim( (string) $path );
        $path = str_replace( '\\', '/', $path );

        // Pull the root value off of the path
        if ( preg_match('/^((?:[a-z]+:)?\/)(.*)/i', $path, $pathRootReg) ) {
            $root = $pathRootReg[1];
            $path = $pathRootReg[2];
        }
        else {
            $root = "";
        }

        // Record whether the path we are resolving ends with a "/"... this will
        // be used to re-attach the trailing slash later
        $hasTail = \r8\str\endsWith($path, "/");

        $pathStack = explode("/", $path);

        $out = array();

        foreach ($pathStack AS $pathElem) {

            if ( !empty($pathElem) ) {

                if ($pathElem == "..")
                    @array_pop($out);

                else if ($pathElem != ".")
                    $out[] = $pathElem;

            }

        }

        return $root . implode("/", $out) . ( $hasTail ? "/" : "" );
    }

    /**
     * Creates a new FileSys instance according to the given path. That is to say,
     * if the path is a file, a FileSys\File instance will be returned. If it is
     * a Dir, a FileSys\Dir instance will be returned
     *
     * If we can't determine the path type, a FileSys\File object will be created.
     *
     * @param \r8\FileSys|String $path The path to use for instantiation
     * @return \r8\FileSys Returns a FileSys object of the appropriate type
     */
    static public function create ( $path )
    {
        if ( $path instanceof self )
            return clone $path;

        $path = (string) $path;

        if ( is_dir($path) )
            return new \r8\FileSys\Dir( $path );
        else
            return new \r8\FileSys\File( $path );
    }

    /**
     * Constructor...
     *
     * @param String $path The File System path represented by this instance
     */
    public function __construct ( $path = null )
    {
        $this->setPath( $path );
    }

    /**
     * Returns the path represented by this instance
     *
     * @return String The full path
     */
    abstract public function getPath ();

    /**
     * Sets the path that this instance represents
     *
     * @param String $path The new path
     * @return \r8\FileSys Returns a self reference
     */
    abstract public function setPath ( $path );

    /**
     * Returns whether this file system item exists
     *
     * @return boolean
     */
    abstract public function exists ();

    /**
     * Returns the path as a string
     *
     * @return String
     */
    public function __toString ()
    {
        return strval( $this->getPath() );
    }

    /**
     * Returns the directory as a string
     *
     * @return String|Null Null will be returned if no directory has been set
     */
    public function getRawDir ()
    {
        return $this->dir;
    }

    /**
     * Sets the directory
     *
     * @param String $dir The new directory
     * @return \r8\FileSys Returns a self reference
     */
    public function setDir ( $dir )
    {
        $dir = (string) $dir;

        if ( \r8\isEmpty($dir, \r8\str\ALLOW_BLANK) ) {
            $this->dir = null;
        }
        else {
            $dir = str_replace('\\', '/', $dir);
            $dir = \r8\str\stripRepeats($dir, "/");
            $dir = \r8\str\tail($dir, "/");
            $this->dir = $dir;
        }

        return $this;
    }

    /**
     * Returns whether a directory has been set in this instance
     *
     * This does NOT return whether the directory exists on the filesystem! While
     * this may be confusing, it sticks to the accessor method naming conventions
     * used in all the other classes.
     *
     * @return Boolean
     */
    public function dirExists ()
    {
        return isset( $this->dir );
    }

    /**
     * Unsets the directory value from this instance
     *
     * @return \r8\FileSys Returns a self reference
     */
    public function clearDir ()
    {
        $this->dir = null;
        return $this;
    }

    /**
     * Checks to see if the path exists and throws an exception if it doesn't
     *
     * @return \r8\FileSys Returns a self reference
     */
    public function requirePath ()
    {
        if ( !$this->exists() ) {
            throw new \r8\Exception\FileSystem\Missing(
                    $this->getPath(),
                    "Path does not exist"
                );
        }

        return $this;
    }

    /**
     * Returns whether this item is an existing directory
     *
     * @return Boolean
     */
    public function isDir ()
    {
        return is_dir( $this->getPath() );
    }

    /**
     * Returns whether this item is an existing file
     *
     * @return Boolean
     */
    public function isFile ()
    {
        return is_file( $this->getPath() );
    }

    /**
     * Returns whether this item is a sym link
     *
     * @return Boolean
     */
    public function isLink ()
    {
        return is_link( $this->getPath() );
    }

    /**
     * Returns whether this item is readable
     *
     * @return Boolean
     */
    public function isReadable ()
    {
        return is_readable( $this->getPath() );
    }

    /**
     * Returns whether this item is writable
     *
     * @return Boolean
     */
    public function isWritable ()
    {
        return is_writable( $this->getPath() );
    }

    /**
     * Returns whether this item is writable
     *
     * @return Boolean
     */
    public function isExecutable ()
    {
        return is_executable( $this->getPath() );
    }

    /**
     * Returns when a file was created
     *
     * @return \r8\DateTime Returns a date/time object
     */
    public function getCTime ()
    {
        $this->requirePath();

        $time = filectime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve creation time"
                );
        }

        return new \r8\DateTime( $time );
    }

    /**
     * Returns the last access time of a file
     *
     * @return \r8\DateTime Returns a date/time object
     */
    public function getATime ()
    {
        $this->requirePath();

        $time = fileatime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve access time"
                );
        }

        return new \r8\DateTime( $time );
    }

    /**
     * Returns the last modified time of a file
     *
     * @return \r8\DateTime Returns a date/time object
     */
    public function getMTime ()
    {
        $this->requirePath();

        $time = filemtime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve last modified time"
                );
        }

        return new \r8\DateTime( $time );
    }

    /**
     * Returns the group ID for this path
     *
     * @return Integer
     */
    public function getGroupID ()
    {
        $this->requirePath();

        $group = @filegroup( $this->getPath() );

        if ( $group === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve group id"
                );
        }

        return $group;
    }

    /**
     * Returns the owner ID for this path
     *
     * @return Integer
     */
    public function getOwnerID ()
    {
        $this->requirePath();

        $owner = @fileowner( $this->getPath() );

        if ( $owner === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve owner id"
                );
        }

        return $owner;
    }

    /**
     * Returns the permissions for this path
     *
     * @return Integer
     */
    public function getPerms ()
    {
        $this->requirePath();

        $perms = @fileperms( $this->getPath() );

        if ( $perms === FALSE ) {
            throw new \r8\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve permissions"
                );
        }

        return $perms;
    }

    /**
     * Internal method to return the current working directory
     *
     * This method exists strictly for unit testing purposes. Overriding this
     * method allows you to spoof what the current working directory is
     *
     * @return String
     */
    protected function getCWD()
    {
        return getcwd();
    }

    /**
     * Expands any dots in the path to resolve the absolute pathname
     *
     * Resolve differs from realpath in that realpath fails if it is given
     * a path that does not exist. Resolve will still return a value.
     *
     * The path resolution is done in-place, that is to say, the internal path
     * value will be update (rather than returning a new object).
     *
     * @param string $base The base directory the path should stem from
     * @param boolean $strict Whether or not the path can dip in to the base dir
     * @return \r8\FileSys Returns a self reference
     */
    public function resolve ( $base = null, $strict = FALSE )
    {
        if ( \r8\isVague($base) )
            $base = $this->getCWD();

        $base = self::resolvePath( $base );

        // If the base doesn't start with a root of some sort, attach the cwd
        if ( !preg_match('/^(?:[a-z]+:)?\//i', $base ) )
            $base = \r8\str\weld( $this->getCWD(), $base, "/" );

        $path = $this->getPath();
        $path = str_replace('\\', '/', $path);

        // Pull the root value off of the path
        if ( preg_match('/^((?:[a-z]+:)?\/)(.*)/i', $path, $pathRootReg) ) {
            $root = $pathRootReg[1];
            $path = $pathRootReg[2];
        }
        else {
            $root = FALSE;
        }

        // If we are in strict mode, we always ignore the root and instead use the base
        if ( $strict ) {
            $path = self::resolvePath( $path );
            $path = \r8\str\weld( $base, $path, "/" );
        }

        else {

            // If they didn't give us a root, use the base, but let them dip in to it
            if ( $root === FALSE )
                $path = \r8\str\weld( $base, $path, "/" );
            else
                $path = $root . $path;

            $path = self::resolvePath( $path );
        }

        $this->setPath( $path );

        return $this;
    }

}

?>