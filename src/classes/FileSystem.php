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
     * Checks to see if the path exists and throws an exception if it doesn't
     *
     * @return Object Returns a self reference
     */
    public function requirePath ()
    {
        if ( !$this->exists() ) {
            throw new ::cPHP::Exception::FileSystem::Missing(
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
     * Returns when a file was created
     *
     * @return Object Returns a date/time object
     */
    public function getCTime ()
    {
        $this->requirePath();

        $time = filectime( $this->getPath() );

        if ( $time === FALSE ) {
            throw new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable to resolve creation time"
                );
        }

        return new ::cPHP::DateTime( $time );
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
            throw new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable to resolve access time"
                );
        }

        return new ::cPHP::DateTime( $time );
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
            throw new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable to resolve last modified time"
                );
        }

        return new ::cPHP::DateTime( $time );
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
            throw new ::cPHP::Exception::FileSystem(
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
            throw new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable to resolve owner id"
                );
        }

        return $owner;
    }

}

?>