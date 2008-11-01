<?php
/**
 * Quote parsing result class
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
 * @package Quoter
 */

namespace cPHP::Quoter;

/**
 * Representation of each section of the parsed string
 */
abstract class Section
{

    /**
     * The content of this section
     */
    private $content;

    /**
     * In the grand scheme of the original string, this is the offset
     * of the content
     */
    private $offset;

    /**
     * Constructor...
     *
     * @param Integer $offset The offset of the content in the scope of the original string
     * @param String $content The string content of this section
     */
    public function __construct( $offset, $content )
    {
        $offset = intval($offset);
        if ( $offset < 0 )
            throw new ::cPHP::Exception::Argument( 0, "Offset", "Must not be less than zero");
        $this->offset = $offset;
        $this->setContent( $content );
    }

    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    abstract public function isQuoted ();

    /**
     * Returns the content in this section
     *
     * @return String
     */
    public function getContent ()
    {
        return $this->content;
    }

    /**
     * Sets the content in this section
     *
     * @param String $content The content for this section
     * @return Object Returns a self reference
     */
    public function setContent ( $content )
    {
        $this->content = is_null($content) ? null : ::cPHP::strval( $content );
        return $this;
    }

    /**
     * Unsets the content from this section
     *
     * @return Object Returns a self reference
     */
    public function clearContent ()
    {
        $this->content = null;
        return $this;
    }

    /**
     * Returns whether this instance has any content
     *
     * @return Boolean
     */
    public function contentExists ()
    {
        return isset( $this->content );
    }

    /**
     * Returns whether the content in this instance could be considered empty
     *
     * @param Integer $flags Any boolean flags to set. See cPHP::is_empty
     * @return Boolean
     */
    public function isEmpty ( $flags = 0 )
    {
        return ::cPHP::is_empty( $this->content, $flags );
    }

    /**
     * Returns the offset of the content in this string
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * To be overwriten, converts this value in to a string
     *
     * @return String
     */
    abstract public function __toString();
}

?>