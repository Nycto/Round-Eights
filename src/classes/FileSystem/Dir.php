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

namespace cPHP::FileSystem;

/**
 * Filesystem Directory class
 */
class Dir extends ::cPHP::FileSystem implements RecursiveIterator
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
     * @return Object A cPHP::FileSystem::Dir instance
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
     * @return Object Returns a cPHP::Ary object
     */
    public function toArray ()
    {
        $this->requirePath();

        $resource = @opendir( $this->getPath() );

        if ( $resource === FALSE ) {
            $err = new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable to open directory"
                );
            throw $err;
        }

        $path = $this->getPath();

        $result = new ::cPHP::Ary;

        while ( ($item = readdir($resource)) !== FALSE ) {

            // Respect the include Dots option
            if ( !$this->includeDots && ( $item == "." || $item == "..") )
                continue;

            $item = $path . $item;

            if ( is_dir( $item ) )
                $result[] = new ::cPHP::FileSystem::Dir( $item );
            else
                $result[] = new ::cPHP::FileSystem::File( $item );

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
            throw new ::cPHP::Exception::Variable("Path", "No Path has been set");

        $path = $this->getRawDir();

        if ( is_dir($path) )
            return $this;

        if ( @mkdir( $path, 0777, TRUE ) === FALSE ) {
            $err = new ::cPHP::Exception::FileSystem(
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
                $err = new ::cPHP::Exception::FileSystem( $dir, "Unable to open directory" );
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
                    $err = new ::cPHP::Exception::FileSystem( $dir . $item, "Unable to delete path" );
                    throw $err;
                }

            }

            closedir( $resource );

        };

        $callback( $this->getPath(), $callback );

        return $this;
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
                $err = new ::cPHP::Exception::FileSystem(
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
            throw new ::cPHP::Exception::Interaction("Iteration has not been rewound");

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
            throw new ::cPHP::Exception::Interaction("Iteration has not been rewound");

        $current = $this->getRawDir() . $this->current;

        if ( is_dir( $current ) )
            return new ::cPHP::FileSystem::Dir( $current );
        else
            return new ::cPHP::FileSystem::File( $current );
    }

    /**
     * Used for iteration, this returns the key of the current file
     *
     * @return Integer
     */
    public function key ()
    {
        if ( !$this->hasResource() )
            throw new ::cPHP::Exception::Interaction("Iteration has not been rewound");

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
            throw new ::cPHP::Exception::Interaction("Iteration has not been rewound");

        if ( $this->current == ".." || $this->current == "." )
            return FALSE;

        return is_dir( $this->getRawDir() . $this->current );
    }

    /**
     * Used for recursive iteration, this returns the iterator for the current element
     *
     * @return Object Returns a cPHP::FileSystem::Dir object
     */
    public function getChildren ()
    {
        if ( !$this->hasResource() )
            throw new ::cPHP::Exception::Interaction("Iteration has not been rewound");

        if ( !$this->hasChildren() )
            throw new ::cPHP::Exception::Interaction("Current value does not have children");

        // Grab the current item as a cPHP::FileSystem::Dir object
        $current = $this->current();

        // Import the 'includeDots' setting
        $current->setIncludeDots( $this->includeDots );

        return $current;
    }

}

?>