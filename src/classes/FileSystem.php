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

}

?>