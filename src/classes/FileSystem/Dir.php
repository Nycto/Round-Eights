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
     * Used for iteration, this returns the current file
     *
     * @return mixed
     */
    public function current ()
    {
    }

    /**
     * Used for iteration, this returns the key of the current file
     *
     * @return Integer
     */
    public function key ()
    {
    }

    /**
     * Used for iteration, this moves the internal iteration pointer on to the next
     * element in the directory
     *
     * @return Object Returns a self reference
     */
    public function next ()
    {
        return $this;
    }

    /**
     * Used for iteration, this resets to the beginning of the directory
     *
     * @return Object Returns a self reference
     */
    public function rewind ()
    {
        return $this;
    }

    /**
     * Used for iteration, this returns whether the iterator has reached the last element
     *
     * @return Boolean
     */
    public function valid ()
    {
    }

    /**
     * Used for recursive iteration, this returns whether the current element
     * has any children that can be iterated over.
     *
     * @return Boolean
     */
    public function hasChildren ()
    {
    }

    /**
     * Used for recursive iteration, this returns the iterator for the current element
     *
     * @return Object Returns a cPHP::FileSystem::Dir object
     */
    public function getChildren ()
    {
    }

}

?>