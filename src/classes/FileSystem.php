<?php
/**
 * File System Class
 *
 * PHP version 5.3
 *
 * This source file is subject to version 2.0 of the Artistic License. A copy
 * of the license should have been bundled with this source file inside a file
 * named LICENSE.txt. It is also available through the world-wide-web at one
 * of the following URIs:
 * http://www.commonphp.com/license.php
 * http://www.opensource.org/licenses/artistic-license-2.0.php
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS
 * FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
 * COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT,
 * INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING,
 * BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
 * LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
 * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT
 * LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN
 * ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @author James Frasca <james@commonphp.com>
 * @license Artistic License 2.0 http://www.commonphp.com/license.php
 * @package FileSystem
 */

namespace cPHP;

/**
 * The base filesystem class
 */
abstract class FileSystem
{

    /**
     * The directory of the current path
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
        $path = trim( \cPHP\strval($path) );
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
        $hasTail = \cPHP\str\endsWith($path, "/");

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
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
     */
    public function setDir ( $dir )
    {
        $dir = \cPHP\strval( $dir );

        if ( \cPHP\isEmpty($dir, \cPHP\str\ALLOW_BLANK) ) {
            $this->dir = null;
        }
        else {
            $dir = str_replace('\\', '/', $dir);
            $dir = \cPHP\str\stripRepeats($dir, "/");
            $dir = \cPHP\str\tail($dir, "/");
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
     * @return Object Returns a self reference
     */
    public function clearDir ()
    {
        $this->dir = null;
        return $this;
    }

    /**
     * Checks to see if the path exists and throws an exception if it doesn't
     *
     * @return Object Returns a self reference
     */
    public function requirePath ()
    {
        if ( !$this->exists() ) {
            throw new \cPHP\Exception\FileSystem\Missing(
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
     * @return Object Returns a date/time object
     */
    public function getCTime ()
    {
        $this->requirePath();

        $time = filectime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve creation time"
                );
        }

        return new \cPHP\DateTime( $time );
    }

    /**
     * Returns the last access time of a file
     *
     * @return Object Returns a date/time object
     */
    public function getATime ()
    {
        $this->requirePath();

        $time = fileatime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve access time"
                );
        }

        return new \cPHP\DateTime( $time );
    }

    /**
     * Returns the last modified time of a file
     *
     * @return Object Returns a date/time object
     */
    public function getMTime ()
    {
        $this->requirePath();

        $time = filemtime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new \cPHP\Exception\FileSystem(
                    $this->getPath(),
                    "Unable to resolve last modified time"
                );
        }

        return new \cPHP\DateTime( $time );
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
            throw new \cPHP\Exception\FileSystem(
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
            throw new \cPHP\Exception\FileSystem(
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
            throw new \cPHP\Exception\FileSystem(
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
     * @return Object Returns a self reference
     */
    public function resolve ( $base = null, $strict = FALSE )
    {
        if ( \cPHP\isVague($base) )
            $base = $this->getCWD();

        $base = self::resolvePath( $base );

        // If the base doesn't start with a root of some sort, attach the cwd
        if ( !preg_match('/^(?:[a-z]+:)?\//i', $base ) )
            $base = \cPHP\str\weld( $this->getCWD(), $base, "/" );

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
            $path = \cPHP\str\weld( $base, $path, "/" );
        }

        else {

            // If they didn't give us a root, use the base, but let them dip in to it
            if ( $root === FALSE )
                $path = \cPHP\str\weld( $base, $path, "/" );
            else
                $path = $root . $path;

            $path = self::resolvePath( $path );
        }

        $this->setPath( $path );

        return $this;
    }

}

?>