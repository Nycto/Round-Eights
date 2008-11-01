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

namespace cPHP::Quoter::Section;

/**
 * Representation of a quoted section of the parsed string
 */
class Quoted extends ::cPHP::Quoter::Section
{

    /**
     * The string quote that opened
     */
    protected $openQuote;

    /**
     * The string that closed the section
     */
    protected $closeQuote;

    /**
     * Constructor...
     *
     * @param Integer $offset The offset of the content in the scope of the original string
     * @param String $content The string content of this section
     * @param String $openQuote The open quote
     * @param String $closeQuote The quote that closed this section
     */
    public function __construct( $offset, $content, $openQuote, $closeQuote )
    {
        parent::__construct( $offset, $content );

        $this->setOpenQuote( $openQuote )
            ->setCloseQuote( $closeQuote );
    }

    /**
     * Returns whether the current section is quoted
     *
     * @return Boolean
     */
    public function isQuoted ()
    {
        return true;
    }

    /**
     * Returns the open quote string
     *
     * @return String|null Returns null if there is no open quote set
     */
    public function getOpenQuote ()
    {
        return $this->openQuote;
    }

    /**
     * Sets the open quote
     *
     * @param String $quote The new open quote
     * @return Object Returns a self reference
     */
    public function setOpenQuote ( $quote )
    {
        $this->openQuote = is_null( $quote ) ? null : ::cPHP::strval( $quote );
        return $this;
    }

    /**
     * Unsets the open quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearOpenQuote ()
    {
        $this->openQuote = null;
        return $this;
    }

    /**
     * Returns whether this instance has an open quote
     *
     * @return Boolean
     */
    public function openQuoteExists ()
    {
        return isset($this->openQuote);
    }

    /**
     * Returns the close quote string
     *
     * @return String|null Returns null if there is no close quote set
     */
    public function getCloseQuote ()
    {
        return $this->closeQuote;
    }

    /**
     * Sets the close quote
     *
     * @param String $quote The new close quote
     * @return Object Returns a self reference
     */
    public function setCloseQuote ( $quote )
    {
        $this->closeQuote = is_null( $quote ) ? null : ::cPHP::strval( $quote );
        return $this;
    }

    /**
     * Unsets the close quote from this instance
     *
     * @return Object Returns a self reference
     */
    public function clearCloseQuote ()
    {
        $this->closeQuote = null;
        return $this;
    }

    /**
     * Returns whether this instance has an close quote
     *
     * @return Boolean
     */
    public function closeQuoteExists ()
    {
        return isset($this->closeQuote);
    }

    /**
     * Returns the value of this instance
     *
     * @return String
     */
    public function __toString ()
    {
        return $this->getOpenQuote() . $this->getContent() . $this->getCloseQuote();
    }
}

?>