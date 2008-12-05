<?php
/**
 * String parsing class
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

namespace cPHP;

/**
 * Parses a string and splits it in to a list of quoted and unquoted sections
 */
class Quoter
{

    /**
     * The escape string
     */
    protected $escape = "\\";

    /**
     * An array of strings that represent quotes
     *
     * This is a multidimensional array. The key of the first dimension
     * is the opening quote character. The second dimension is a list of characters
     * that are allowed to close the opening quote
     */
    protected $quotes = array(
            "'" => array( "'" ),
            '"' => array( '"' )
        );

    /**
     * Returns whether an offset is preceded by an unescaped escape string
     *
     * @param String $string The haystack
     * @param Integer $offset The offset to walk back from
     * @param String $escape The escape character
     * @return Boolean
     */
    static public function isEscaped ( $string, $offset, $escape = '\\' )
    {
        $escape = \cPHP\strval( $escape );

        // Something can't be escaped if there is no escape string
        if ( \cPHP\isEmpty( $escape, ALLOW_SPACES ) )
            return false;

        $string = \cPHP\strval( $string );
        $offset = intval( $offset );

        if ( $offset > strlen( $string ) )
            return false;

        $escapeLen = strlen( $escape );

        // If there isn't enough room for the escape string, how could it be escaped?
        if ( $offset - $escapeLen < 0 )
            return false;

        // If the preceding characters are not the escape string, then it is definitely not escaped
        if ( strcasecmp( substr($string, $offset - $escapeLen, $escapeLen), $escape ) != 0 )
            return false;

        // Now, we need to determine whether the escape string is escaped
        return !self::isEscaped( $string, $offset - $escapeLen, $escape );
    }

    /**
     * Returns the position of the next unescaped character in a string
     *
     * @param String $string The haystack
     * @param Array $needles The list of strings to look for
     * @param String $escape The escape character to use
     */
    static public function findNext ( $string, array $needles, $escape = '\\' )
    {
        if ( count($needles) <= 0 )
            return array( false, false );

        $resultOffset = false;
        $resultNeedle = false;

        // Loop through each needle so we can figure out the one with the minimum offset
        foreach( $needles AS $needle ) {

            $needle = \cPHP\strval( $needle );

            if ( \cPHP\isEmpty( $needle, ALLOW_SPACES ) )
                throw new \cPHP\Exception\Data($needle, "needle", "Needle must not be empty");

            // Cache the length of the needle so it isn't continually calculated
            $needleLen = strlen( $needle );

            // Give $pos an initial value because it is used by
            // stripos to determine where to start looking
            $pos = 0 - $needleLen;

            do {

                $pos = stripos(
                        $string,
                        $needle,

                        // Since $pos represents the location of the last found needle,
                        // to avoid an infinite loops, we need to start search at the
                        // offset just after the last needle was found
                        $pos + $needleLen
                    );

                // If $pos is false, it means that the needle was not found in the string at all
                // In this circumstance, we can immediately break out of this loop and
                // continue with the next needle. Hence "continue 2"
                if ( $pos === FALSE )
                    continue 2;

            // Continue searching if this character is escaped
            } while ( self::isEscaped($string, $pos, $escape) );

            // If we have not yet found any needles, or this needle appears
            // before any of the other ones we've found so far, then mark
            // it as the one that will be returned
            if ( $resultOffset === FALSE || $pos < $resultOffset ) {
                $resultOffset = $pos;
                $resultNeedle = $needle;
            }

        }

        return array( $resultOffset, $resultNeedle );

    }

    /**
     * Returns the list of quotes registered in this instance
     *
     * This returns a multidimensional array. The key of the first dimension
     * is the opening quote character. The second dimension is a list of characters
     * that are allowed to close the opening quote
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function getQuotes ()
    {
        return new \cPHP\Ary( $this->quotes );
    }

    /**
     * Clears the list of quotes in this instance
     *
     * @return object Returns a self reference
     */
    public function clearQuotes ()
    {
        $this->quotes = array();
        return $this;
    }

    /**
     * Registers a set of quotes
     *
     * If the opening quote has already been registered, the closing quotes will
     * be replaced with the new set
     *
     * @param String $open The opening quote
     * @param Null|String|Array If left empty, this will assume the closing quote
     *      is the same as the opening quote. If an array is given, it will be
     *      flattened and compacted.
     * @return object Returns a self reference
     */
    public function setQuote ( $open, $close = FALSE )
    {
        $open = \cPHP\strval( $open );

        if ( \cPHP\isEmpty($open, ALLOW_SPACES) )
            throw new \cPHP\Exception\Argument( 0, "Open Quote", "Must not be empty" );

        if ( \cPHP\isVague( $close, ALLOW_SPACES ) ) {
            $close = array( $open );
        }
        else {

            $close = \cPHP\Ary::create( $close )
                ->flatten()
                ->collect("\cPHP\strval")
                ->compact( ALLOW_SPACES )
                ->unique()
                ->get();

        }

        $this->quotes[ $open ] = $close;

        return $this;
    }

    /**
     * Returns a flat list of all the open and close quotes registered in this instance
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function getAllQuotes ()
    {
        return \cPHP\Ary::create( array_values( $this->quotes ) )
            ->flatten()
            ->merge( array_keys( $this->quotes ) )
            ->unique()
            ->values();
    }

    /**
     * Returns a list of all the opening quotes
     *
     * @return Object Returns a \cPHP\Ary object
     */
    public function getOpenQuotes ()
    {
        return new \cPHP\Ary( array_keys( $this->quotes ) );
    }

    /**
     * Returns whether a given quote is an opening quote
     *
     * @param String $quote The quote to check
     * @return Boolean
     */
    public function isOpenQuote ( $quote )
    {
        $quote = \cPHP\strval( $quote );
        return array_key_exists( $quote, $this->quotes );
    }

    /**
     * Returns a list of closing quotes for an opening quote
     *
     * @param String $quote The opening quote
     * @return Object Returns a \cPHP\Ary object
     */
    public function getCloseQuotesFor ( $quote )
    {
        $quote = \cPHP\strval( $quote );

        if ( !$this->isOpenQuote($quote) )
            throw new \cPHP\Exception\Argument( 0, "Open Quote", "Invalid open quote" );

        return \cPHP\Ary::create( $this->quotes[ $quote ] )->values();
    }

    /**
     * Returns the escape string in this instance
     *
     * @return Boolean|String Returns the escape string, or null if there isn't one set
     */
    public function getEscape ()
    {
        if ( !isset($this->escape) )
            return null;
        else
            return $this->escape;
    }

    /**
     * Sets the escape string
     *
     * @param String The new escape string
     * @return Object Returns a self reference
     */
    public function setEscape ( $escape )
    {
        $escape = \cPHP\strval( $escape );
        if ( \cPHP\isEmpty( $escape, ALLOW_SPACES ) )
            throw new \cPHP\Exception\Argument( 0, "Escape String", "Must not be empty" );
        $this->escape = $escape;
        return $this;
    }

    /**
     * Removes the escape string
     *
     * @return Object Returns a self reference
     */
    public function clearEscape ()
    {
        $this->escape = null;
        return $this;
    }

    /**
     * Returns whether there is an escape string in this instance
     *
     * @return Boolean
     */
    public function escapeExists ()
    {
        return isset( $this->escape );
    }

    /**
     * Breaks a string up according the settings in this instance
     *
     * @param String $string The string to parse
     * @return Object Returns a \cPHP\Quoter\Parsed object
     */
    public function parse ( $string )
    {
        $string = \cPHP\strval( $string );

        $openQuotes = $this->getOpenQuotes()->get();

        $result = new \cPHP\Quoter\Parsed;

        // As we walk through the string, this is updated as the offset
        // relative to the original string
        $totalOffset = 0;

        do {

            // Find the next open quote and it's offset
            list( $openOffset, $openQuote ) =
                self::findNext( $string, $openQuotes, $this->escape );

            // If a quote couldn't be found, break out of the loop
            if ( $openOffset === FALSE ) {

                $result->addSection(
                        new \cPHP\Quoter\Section\Unquoted( $totalOffset, $string )
                    );

                break;
            }

            else if ( $openOffset > 0 ) {

                // Construct the unquoted section and add it to the result
                $result->addSection(
                        new \cPHP\Quoter\Section\Unquoted(
                                $totalOffset,
                                substr( $string, 0, $openOffset )
                            )
                    );

            }

            $totalOffset += $openOffset + strlen( $openQuote );


            // Remove the unquoted section from the string
            $string = substr( $string, $openOffset + strlen( $openQuote ) );

            // Look for the close quote
            list( $closeOffset, $closeQuote ) =
                self::findNext( $string, $this->getCloseQuotesFor($openQuote)->get(), $this->escape );


            if ( $closeOffset === FALSE ) {
                $quoted = $string;
            }
            else {
                $quoted = substr( $string, 0, $closeOffset );
                $string = substr( $string, $closeOffset + strlen( $closeQuote ) );
            }


            // Construct the quoted section and add it to the result
            $result->addSection(
                    new \cPHP\Quoter\Section\Quoted(
                            $totalOffset,
                            $quoted,
                            $openQuote,
                            $closeQuote
                        )
                );

            $totalOffset += $closeOffset + strlen( $closeQuote );

        } while ( $closeOffset !== FALSE && $string !== FALSE );

        return $result;

    }

}

?>