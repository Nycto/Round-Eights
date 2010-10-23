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
 * @package Quoter
 */

namespace r8;

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
        $escape = (string) $escape;

        // Something can't be escaped if there is no escape string
        if ( \r8\isEmpty( $escape, ALLOW_SPACES ) )
            return false;

        $string = (string) $string;
        $offset = (int) $offset;

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

            $needle = (string) $needle;

            if ( \r8\isEmpty( $needle, ALLOW_SPACES ) )
                throw new \r8\Exception\Data($needle, "needle", "Needle must not be empty");

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
     * @return array
     */
    public function getQuotes ()
    {
        return $this->quotes;
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
     * @param Null|String|Array $close If left empty, this will assume the closing quote
     *      is the same as the opening quote. If an array is given, it will be
     *      flattened and compacted.
     * @return object Returns a self reference
     */
    public function setQuote ( $open, $close = FALSE )
    {
        $open = (string) $open;

        if ( \r8\isEmpty($open, ALLOW_SPACES) )
            throw new \r8\Exception\Argument( 0, "Open Quote", "Must not be empty" );

        if ( \r8\isVague( $close, ALLOW_SPACES ) ) {
            $close = array( $open );
        }
        else {

            $close = (array) $close;
            $close = \r8\ary\flatten( $close );
            $close = \r8\ary\stringize( $close );
            $close = \r8\ary\compact( $close, \r8\ALLOW_SPACES );
            $close = \array_unique( $close );

        }

        $this->quotes[ $open ] = $close;

        return $this;
    }

    /**
     * Returns a flat list of all the open and close quotes registered in this instance
     *
     * @return Array
     */
    public function getAllQuotes ()
    {
        $quotes = array_values( $this->quotes );
        $quotes = \r8\ary\flatten( $quotes );
        $quotes = \array_merge( $quotes, array_keys($this->quotes) );
        $quotes = \array_unique( $quotes );
        return \array_values($quotes);
    }

    /**
     * Returns a list of all the opening quotes
     *
     * @return Array
     */
    public function getOpenQuotes ()
    {
        return array_keys( $this->quotes );
    }

    /**
     * Returns whether a given quote is an opening quote
     *
     * @param String $quote The quote to check
     * @return Boolean
     */
    public function isOpenQuote ( $quote )
    {
        $quote = (string) $quote;
        return array_key_exists( $quote, $this->quotes );
    }

    /**
     * Returns a list of closing quotes for an opening quote
     *
     * @param String $quote The opening quote
     * @return Array
     */
    public function getCloseQuotesFor ( $quote )
    {
        $quote = (string) $quote;

        if ( !$this->isOpenQuote($quote) )
            throw new \r8\Exception\Argument( 0, "Open Quote", "Invalid open quote" );

        return array_values( $this->quotes[ $quote ] );
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
        $escape = (string) $escape;
        if ( \r8\isEmpty( $escape, ALLOW_SPACES ) )
            throw new \r8\Exception\Argument( 0, "Escape String", "Must not be empty" );
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
     * @return Object Returns a \r8\Quoter\Parsed object
     */
    public function parse ( $string )
    {
        $string = (string) $string;

        $openQuotes = $this->getOpenQuotes();

        $result = new \r8\Quoter\Parsed;

        do {

            // Find the next open quote and it's offset
            list( $openOffset, $openQuote ) =
                self::findNext( $string, $openQuotes, $this->escape );

            // If a quote couldn't be found, break out of the loop
            if ( $openOffset === FALSE ) {

                $result->addSection(
                        new \r8\Quoter\Section\Unquoted( $string )
                    );

                break;
            }

            else if ( $openOffset > 0 ) {

                // Construct the unquoted section and add it to the result
                $result->addSection(
                        new \r8\Quoter\Section\Unquoted(
                                substr( $string, 0, $openOffset )
                            )
                    );

            }


            // Remove the unquoted section from the string
            $string = substr( $string, $openOffset + strlen( $openQuote ) );

            // Look for the close quote
            list( $closeOffset, $closeQuote ) =
                self::findNext( $string, $this->getCloseQuotesFor($openQuote), $this->escape );


            if ( $closeOffset === FALSE ) {
                $quoted = $string;
            }
            else {
                $quoted = substr( $string, 0, $closeOffset );
                $string = substr( $string, $closeOffset + strlen( $closeQuote ) );
            }


            // Construct the quoted section and add it to the result
            $result->addSection(
                    new \r8\Quoter\Section\Quoted(
                            $quoted,
                            $openQuote,
                            $closeQuote
                        )
                );

        } while ( $closeOffset !== FALSE && $string !== FALSE );

        return $result;

    }

}

