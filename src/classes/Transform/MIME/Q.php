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
 * Encodes and decodes strings according to Q MIME encoding
 *
 * The spec for Q encoding can be found in rfc 2047, here:
 * http://tools.ietf.org/html/rfc2047#section-4.2
 */
class Q extends \r8\Transform\MIME
{

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function to ( $string )
    {
        // Yes, I realize that the iconv methods can handle this, but this method
        // can do a few things the iconv encode method cant: Handle encoding without
        // a header defined, use the underscore as spaces to save on space.

        $string = (string) $string;

        // React to the input encoding
        $string = iconv(
                $this->getInputEncoding(),
                $this->getOutputEncoding(),
                $string
            );

        // This will hold the resulting string
        $result = "";

        // Generate the part that describes the encoding
        $encodingPart = "=?". $this->getOutputEncoding() ."?Q?";

        // This is a running total of the length of the current line
        // Once this reaches the max length, the line will be wrapped
        $currentLine = 0;

        if ( $this->headerExists() ) {

            $result = ( $this->headerExists() ? $this->getHeader() .":" : "" );

            // If the header is so long it won't fit on a line (plus one for the colon)
            if ( $this->getLineLength() !== FALSE && $this->getLineLength() < strlen($result) + 1 ) {
                $err = new \r8\Exception\Data(
                        $this->getHeader(),
                        "MIME Header",
                        "Header length exceeds the maximum line length"
                    );
                $err->addData("Max Line Length", $this->getLineLength());
                throw $err;
            }

            // If the encoding part and the header can't fit on the same line,
            // plus one for the space that hasn't been added yet,  plus two for the trailing '?=',
            // plus 3 for the length of a single encoded character
            if ( $this->getLineLength() !== FALSE
                    && strlen($result) + strlen($encodingPart) + 1 + 2 + 3 > $this->getLineLength() ) {
                $result .= $this->getEOL() ."\t";
            }
            else {
                $result .= " ";

                // Adjust the offset of the current line to compensate for the length of the header
                $currentLine += strlen($result) - 1;
            }

        }

        if ( $this->getLineLength() === FALSE ) {
            $maxLineLength = FALSE;
        }
        else {
            // The max line length is the line length, minus one for the fold,
            // minus the length of the encoding definition, minus two for the closing ?=
            $maxLineLength = $this->getLineLength() - 1 - strlen($encodingPart) - 2;
        }

        if ( $maxLineLength !== FALSE && $maxLineLength <= 0 ) {
            throw new \r8\Exception\Data(
                    $this->getLineLength(),
                    "Max Line Length",
                    "Required content length exceeds the maximum line length"
                );
        }

        // Attach the leading encoding info
        $result .= $encodingPart;

        // Grab the string length only once
        $stringLength = strlen($string);

        // Iterate over each character of the string we're encoding
        for ( $i = 0; $i < $stringLength; $i++ ) {

            // Replace spaces with underscores
            if ( $string[$i] == " " ) {
                $result .= "_";
                $currentLine++;
            }

            // Non-printable characters, equals, question marks and underscores must be encoded
            else if ( ord($string[$i]) <= 32 || ord($string[$i]) >= 127
                    || $string[$i] == "=" || $string[$i] == "?" || $string[$i] == "_" || $string[$i] == ":" ) {

                $result .= "=". strtoupper(
                        str_pad( dechex( ord($string[$i]) ), 2, "0", STR_PAD_LEFT )
                    );
                $currentLine += 3;
            }

            // Otherwise, it can just be added to the string as is
            else {
                $result .= $string[$i];
                $currentLine++;
            }

            if ( $maxLineLength !== FALSE ) {

                // If we have reached the max characters in a line, and this isn't
                // the final character in the string, then wrap
                if ( $currentLine >= $maxLineLength && $i != $stringLength - 1 ) {
                    $result .= "?=". $this->getEOL() ."\t". $encodingPart;
                    $currentLine = 0;
                }
            }

        }

        return $result ."?=";

    }

}

?>