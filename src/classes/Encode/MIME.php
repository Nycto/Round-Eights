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
     * The default value for this is 78 characters
     *
     * @var Integer
     */
    private $length = 78;

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