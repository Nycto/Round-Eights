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
 * @package Transform
 */

namespace r8\Transform;

/**
 * Base class for MIME encoders
 */
abstract class MIME implements \r8\iface\Transform\Encode
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
     * The default value for this property is pulled from the iconv.internal_encoding
     * php.ini setting
     *
     * @var String
     */
    private $outEncoding;

    /**
     * The character encoding of the input value
     *
     * The default value for this property is pulled from the iconv.internal_encoding
     * php.ini setting
     *
     * @var String
     */
    private $inEncoding;

    /**
     * This is an internal flag that stores the specific encoding type selected.
     * See the constants associated with this class for valid values
     *
     * @var Integer
     */
    private $encoding = 0;

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
        $header = (string) $header;

        // Remove any non-printable ascii characters
        $header = preg_replace('/[^\x21-\x7E]/', '', $header);

        // Strip out the colons
        $header = str_replace(':', '', $header);

        return $header;
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
        $this->length = max( (int) $length, 0 );
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
        $this->header = \r8\isEmpty( $name ) ? null : $name;
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
     * Returns the character encoding that the input string will be encoded with.
     *
     * If a value has not been explicitly set, this value will be pulled from
     * the iconv.internal_encoding php.ini setting.
     *
     * @return String This will return the encoding
     */
    public function getInputEncoding ()
    {
        if ( isset($this->inEncoding) )
            return $this->inEncoding;
        else
            return iconv_get_encoding("internal_encoding");
    }

    /**
     * Sets the character encoding that the input string will be encoded with.
     *
     * If a value has not been explicitly set, this value will be pulled from
     * the iconv.internal_encoding php.ini setting.
     *
     * @param String $charset The name of the input character set
     * @return Object Returns a self reference
     */
    public function setInputEncoding ( $charset )
    {
        $charset = \r8\str\stripW($charset, "-");

        if ( \r8\isEmpty($charset) )
            throw new \r8\Exception\Argument(0, "Character Set", "Must not be empty");

        $this->inEncoding = $charset;
        return $this;
    }

    /**
     * Resets the input encoding to the iconv.internal_encoding php.ini settings
     *
     * @return Object Returns a self reference
     */
    public function resetInputEncoding ()
    {
        $this->inEncoding = null;
        return $this;
    }

    /**
     * Returns the character encoding that the unencoded output strings will be in.
     *
     * If a value has not been explicitly set, this value will be pulled from
     * the iconv.internal_encoding php.ini setting.
     *
     * @return String This will return the encoding
     */
    public function getOutputEncoding ()
    {
        if ( isset($this->outEncoding) )
            return $this->outEncoding;
        else
            return iconv_get_encoding("internal_encoding");
    }

    /**
     * Sets the character encoding that the output string will be encoded with.
     *
     * If a value has not been explicitly set, this value will be pulled from
     * the iconv.internal_encoding php.ini setting.
     *
     * @param String $charset The name of the output character set
     * @return Object Returns a self reference
     */
    public function setOutputEncoding ( $charset )
    {
        $charset = \r8\str\stripW($charset, "-");

        if ( \r8\isEmpty($charset) )
            throw new \r8\Exception\Argument(0, "Character Set", "Must not be empty");

        $this->outEncoding = $charset;
        return $this;
    }

    /**
     * resets the output encoding to the iconv.internal_encoding php.ini settings
     *
     * @return Object Returns a self reference
     */
    public function resetOutputEncoding ()
    {
        $this->outEncoding = null;
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
        $this->eol = (string) $eol;
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
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function from ( $string )
    {
        $string = (string) $string;

        // Strip the header name off, if it exists
        $string = preg_replace('/^[\x21-\x7E]+\s*\:/', '', $string);

        $string = trim( $string );

        // Look for a character encoding definition. If you can't find one, just return the string
        if ( !preg_match('/=\?[a-z0-9\-\s\/]+?\?[BQ]\?.+\?\=/i', $string) )
            return $string;

        return iconv_mime_decode($string);
    }

}

