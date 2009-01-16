<?php
/**
 * Encodes a string according to the Q or B Encoding specifications layed out
 * in rfc 2047, here: http://tools.ietf.org/html/rfc2047#section-4.2
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Encoding
 */

namespace cPHP\Encode;

/**
 * Encodes and decodes strings according to B or Q MIME encoding
 */
class MIME implements \cPHP\iface\Encoder
{

    /**
     * The maximum length a line can be, not including the eol marker.
     *
     * The default value for this is 78 characters. When set to 0, no line
     * wrapping will be performed.
     *
     * @var Integer
     */
    private $length = 78;

    /**
     * The name of the header to prepend to this encoding chunk
     *
     * @var String|NULL
     */
    private $header;

    /**
     * The end-of-line marker to wrap lines with
     *
     * @var String
     */
    private $eol = "\r\n";

    /**
     * The character encoding that the encoded value will be in
     *
     * The default value for this property is pulled from the iconv.output_encoding
     * php.ini setting
     *
     * @var String
     */
    private $outEncoding;

    /**
     * The character encoding of the input value
     *
     * The default value for this property is pulled from the iconv.input_encoding
     * php.ini setting
     *
     * @var String
     */
    private $inEncoding;

    /**
     * Strips any invalid characters from a header name string.
     *
     * According to RFC 2822 (http://tools.ietf.org/html/rfc2822), header
     * field names can only contain ascii characters >= 33 and <= 126, except
     * the colon character.
     *
     * @param String $header The header label to strip down
     * @return String
     */
    static public function stripHeaderName ( $header )
    {
        // Convert it to a string
        $header = \cPHP\strval( $header );

        // Remove any non-printable ascii characters
        $header = preg_replace('/[^\x21-\x7E]/', '', $header);

        // Strip out the colons
        $header = str_replace(':', '', $header);

        return $header;
    }

    /**
     * Returns whether a string can be used in its raw form, without any encoding.
     *
     * The ability to be raw encoded requires that a string contains only
     * ascii printable characters. These are any characters with a code >= 32
     * and <= 126
     *
     * @param String $string The string being tested
     * @return Boolean This will return TRUE if the given string contains only
     *      printable characters.
     */
    static public function canRawEncode ( $string )
    {
        return preg_match('/[^\x20-\x7E]/', $string) ? FALSE : TRUE;
    }

    /**
     * Constructor...
     *
     * Sets the default values for the encoding
     */
    public function __construct ()
    {
        $this->inEncoding = iconv_get_encoding("input_encoding");
        $this->outEncoding = iconv_get_encoding("output_encoding");
    }

    /**
     * Returns the maximum number of characters a line can contain, not including
     * the end-of-line marker.
     *
     * @return Integer|Boolean Returns the line length, or FALSE if line wrapping
     *      has been disabled.
     */
    public function getLineLength ()
    {
        return $this->length > 0 ? $this->length : FALSE;
    }

    /**
     * Sets the maximum character length a single line can contain, not
     * including the end-of-line characters.
     *
     * Set this to 0 to disable line wrapping
     *
     * @param Integer $length
     * @return Object Returns a self reference
     */
    public function setLineLength ( $length )
    {
        $this->length = max( intval( $length ), 0 );
        return $this;
    }

    /**
     * Returns the name of the header to label this data with
     *
     * @return String|Null This will return the string name, or NULL if no header
     *      header is set
     */
    public function getHeader ()
    {
        return $this->header;
    }

    /**
     * Sets the name of the header to label this data with
     *
     * @param String $name The header name
     * @return Object Returns a self reference
     */
    public function setHeader ( $name )
    {
        $name = self::stripHeaderName( $name );
        $this->header = \cPHP\isEmpty( $name ) ? null : $name;
        return $this;
    }

    /**
     * Returns whether a header name has been set
     *
     * @return Boolean
     */
    public function headerExists ()
    {
        return isset($this->header);
    }

    /**
     * Unsets the header name
     *
     * @return Object Returns a self reference
     */
    public function clearHeader ()
    {
        $this->header = null;
        return $this;
    }

    /**
     * Returns the string that will be used to break lines
     *
     * @return String Returns the eol string
     */
    public function getEOL ()
    {
        return $this->eol;
    }

    /**
     * Sets the string to use as the end-of-line marker
     *
     * @param String $name The end-of-line string
     * @return Object Returns a self reference
     */
    public function setEOL ( $eol )
    {
        $this->eol = \cPHP\strval( $eol );
        return $this;
    }

    /**
     * Resets the end-of-line character to its default value, which is \r\n
     *
     * @return Object Returns a self reference
     */
    public function resetEOL ()
    {
        $this->eol = "\r\n";
        return $this;
    }

    /**
     * Prepares the string using raw encoding.
     *
     * Invalid characters are stripped, and new lines are normalized and properly
     * indented. It will then attach the header name and wrap the string to the
     * appropriate length.
     *
     * @param String $string The string to encode
     * @return String
     */
    public function rawEncode ( $string )
    {
        $string = \cPHP\strval( $string );

        // Remove any non-printable ascii characters, except for \r and \n
        $string = preg_replace( '/[^\x20-\x7E\r\n]/', '', $string );

        // Replace any line returns and following spaces with folding compatible eols
        $string = preg_replace( '/[\r\n][\s]*/', $this->eol ."\t", $string );

        $string = trim($string);

        // If we aren't doing any wrapping, take the easy out
        if ( $this->getLineLength() == FALSE )
            return ( $this->headerExists() ? $this->header .": " : "" ) . $string;

        // If the header length (plus two for the colon and space) is longer than
        // the allowed line, we don't want to wrap it. Instead, just wrap immediately after it
        if ( $this->headerExists() && strlen( $this->getHeader() ) + 2 >= $this->getLineLength() ) {
            return
                $this->getHeader() .":". $this->getEOL() ."\t"
                .wordwrap(
                    $string,
                    $this->getLineLength(),
                    $this->getEOL() ."\t",
                    true
                );
        }

        return wordwrap(
                ( $this->headerExists() ? $this->getHeader() .": " : "" ) . $string,
                $this->getLineLength(),
                $this->getEOL() ."\t",
                true
            );
    }

    /**
     * Performs 'B' encoding on the string
     *
     * @param String $string The string to encode
     * @return String
     */
    public function bEncode ( $string )
    {
        $string = \cPHP\strval( $string );


    }

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @result mixed The result of the encoding process
     */
    public function encode ( $string )
    {

    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @result mixed The original, unencoded value
     */
    public function decode ( $string )
    {

    }

}

?>