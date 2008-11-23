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
 * Filesystem File class
 */
class File extends ::cPHP::FileSystem
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
        $path = trim(::cPHP::strval( $path ));
        $path = pathinfo( $path );

        if ( isset($path['dirname']) )
            $this->setDir($path['dirname']);
        else
            $this->clearDir();

        if ( isset($path['filename']) )
            $this->setFilename($path['filename']);
        else
            $this->clearFilename();

        if ( isset($path['extension']) )
            $this->setExt($path['extension']);
        else
            $this->clearExt();

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
        $extension = trim(::cPHP::strval( $extension ));
        $extension = ltrim( $extension, "." );
        $this->extension = ::cPHP::isEmpty( $extension ) ? null : $extension;
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
        $filename = trim(::cPHP::strval( $filename ));
        $filename = rtrim( $filename, "." );
        $this->filename = ::cPHP::isEmpty( $filename ) ? null : $filename;
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

        return ::cPHP::str::weld(
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
        $basename = trim(::cPHP::strval( $basename ));
        $basename = pathinfo( $basename );

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
            $err = new ::cPHP::Exception::FileSystem(
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
            $err = new ::cPHP::Exception::FileSystem(
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
            $err = new ::cPHP::Exception::FileSystem(
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
            $err = new ::cPHP::Exception::FileSystem(
                    $this->getPath(),
                    "Unable read data from file"
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

}

?>