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
 * Encodes and decodes strings according to B MIME encoding
 *
 * The spec for B encoding can be found in rfc 2047, here:
 * http://tools.ietf.org/html/rfc2047#section-4.2
 */
class B extends \r8\Transform\MIME
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
        // can do one thing the iconv encode method cant: Handle encoding without
        // a header defined


        $string = (string) $string;

        // React to the input encoding
        $string = iconv(
                $this->getInputEncoding(),
                $this->getOutputEncoding(),
                $string
            );

        // Generate the part that describes the encoding
        $encodingPart = "=?". $this->getOutputEncoding() ."?B?";

        // Do the actual encoding
        $string = base64_encode( $string );

        // If we aren't doing any wrapping, take the easy out
        if ( $this->getLineLength() == FALSE ) {
            return
                ( $this->headerExists() ? $this->getHeader() .": " : "" )
                . $encodingPart . $string ."?=";
        }

        // If there is a header to attach, then we need to figure out how many
        // characters will fit on the first line with it
        if ( $this->headerExists() ) {

            // If the header is so long it won't fit on a line (plus one for the colon)
            if ( $this->getLineLength() < strlen($this->getHeader()) + 1 ) {
                $err = new \r8\Exception\Data(
                        $this->getHeader(),
                        "MIME Header",
                        "Header length exceeds the maximum line length"
                    );
                $err->addData("Max Line Length", $this->getLineLength());
                throw $err;
            }

            // Line length, minus the Header length, minus two for the colon and
            // space, minus the length of the encoding definition, minus two for
            // the trailing ?=
            $firstLineLength = $this->getLineLength()
                - strlen($this->getHeader()) - 2
                - strlen($encodingPart) - 2;

            // Force it to a multiple of 4
            $firstLineLength = floor( $firstLineLength / 4 ) * 4;

            $prepend = $this->getHeader() .":";

            // If there is room on the first line for at least four characters,
            // then add them on
            if ( $firstLineLength > 0 ) {

                $prepend .= " "
                    .$encodingPart
                    .substr($string, 0, $firstLineLength)
                    ."?=";

                $string = substr($string, $firstLineLength);

                // If it all fits on the first line, lets get out of here
                if ( $string == "" )
                    return $prepend;
            }

            $prepend .= $this->getEOL() ."\t". $encodingPart;

        }
        else {
            $prepend = $encodingPart;
        }

        // The line length, minus one to compensate for the leading fold, minus
        // the length of the encoding definition, minus two for the trailing ?=
        $lineLength = $this->getLineLength() - 1 - strlen($encodingPart) - 2;

        // Force it to a multiple of four
        $lineLength = floor( $lineLength / 4 ) * 4;

        // If the required data won't fit on a line, throw an error
        if ( $lineLength <= 0 ) {
            throw new \r8\Exception\Data(
                    $this->getLineLength(),
                    "Max Line Length",
                    "Required content length exceeds the maximum line length"
                );
        }

        return
            $prepend
            .implode(
                    "?=". $this->getEOL() ."\t". $encodingPart,
                    str_split( $string, $lineLength )
                )
            ."?=";
    }

}

