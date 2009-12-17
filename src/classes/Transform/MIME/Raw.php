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

namespace r8\Transform\MIME;

/**
 * Performs raw MIME encoding on a value
 */
class Raw extends \r8\Transform\MIME
{

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
    static public function canEncode ( $string )
    {
        return preg_match('/[^\x20-\x7E]/', $string) ? FALSE : TRUE;
    }

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function to ( $string )
    {
        $string = \r8\strval( $string );

        // React to the input encoding
        $string = iconv( $this->getInputEncoding(), 'ISO-8859-1', $string );

        // Remove any non-printable ascii characters, except for \r and \n
        $string = preg_replace( '/[^\x20-\x7E\r\n]/', '', $string );

        // Replace any line returns and following spaces with folding compatible eols
        $string = preg_replace( '/[\r\n][\s]*/', $this->getEOL() ."\t", $string );

        $string = trim($string);

        // If we aren't doing any wrapping, take the easy out
        if ( $this->getLineLength() == FALSE )
            return ( $this->headerExists() ? $this->getHeader() .": " : "" ) . $string;

        // If the header length (plus two for the colon and space) is longer than
        // the allowed line, we don't want to wrap it. Instead, just wrap immediately after it
        if ( $this->headerExists() && strlen( $this->getHeader() ) + 2 >= $this->getLineLength() ) {
            return
                $this->getHeader() .":". $this->getEOL() ."\t"
                .wordwrap(
                    $string,
                    $this->getLineLength() - 1,
                    $this->getEOL() ."\t",
                    true
                );
        }

        return wordwrap(
                ( $this->headerExists() ? $this->getHeader() .": " : "" ) . $string,
                $this->getLineLength() - 1,
                $this->getEOL() ."\t",
                true
            );
    }

}

?>