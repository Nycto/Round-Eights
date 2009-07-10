<?php
/**
 * Encodes a string according to the Q or B Encoding specifications layed out
 * in rfc 2047, here: http://tools.ietf.org/html/rfc2047#section-4.2
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Encoding
 */

namespace h2o\Encode;

/**
 * Encodes and decodes strings according to B or Q MIME encoding
 */
class MIME implements \h2o\iface\Encoder
{

    /**
     * Represents that Auto encoding should be used
     */
    const ENCODE_AUTO = 0;

    /**
     * Represents Raw encoding
     */
    const ENCODE_RAW = 1;

    /**
     * Represents B encoding
     */
    const ENCODE_B = 2;

    /**
     * Represents Q encoding
     */
    const ENCODE_Q = 3;

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
        $header = \h2o\strval( $header );

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
        $this->header = \h2o\isEmpty( $name ) ? null : $name;
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
        $charset = \h2o\str\stripW($charset, \h2o\str\ALLOW_DASHES);

        if ( \h2o\isEmpty($charset) )
            throw new \h2o\Exception\Argument(0, "Character Set", "Must not be empty");

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
        $charset = \h2o\str\stripW($charset, \h2o\str\ALLOW_DASHES);

        if ( \h2o\isEmpty($charset) )
            throw new \h2o\Exception\Argument(0, "Character Set", "Must not be empty");

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
        $this->eol = \h2o\strval( $eol );
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
     * Sets that this class should automatically select the best encoding method
     * depending on the input.
     *
     * When set, the encode method will use raw encoding if the string contains
     * all ascii printable characters. Otherwise, it will perform both 'B' and 'Q'
     * encoding, then choose whichever produces the shortest result.
     *
     * @return Object Returns a self reference
     */
    public function useAuto ()
    {
        $this->encoding = self::ENCODE_AUTO;
        return $this;
    }

    /**
     * Sets that the encode method should always use raw encoding, no matter what
     *
     * @return Object Returns a self reference
     */
    public function useRaw ()
    {
        $this->encoding = self::ENCODE_RAW;
        return $this;
    }

    /**
     * Sets that the encode method should always use 'B' encoding, no matter what
     *
     * @return Object Returns a self reference
     */
    public function useB ()
    {
        $this->encoding = self::ENCODE_B;
        return $this;
    }

    /**
     * Sets that the encode method should always use 'Q' encoding, no matter what
     *
     * @return Object Returns a self reference
     */
    public function useQ ()
    {
        $this->encoding = self::ENCODE_Q;
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
        $string = \h2o\strval( $string );

        // React to the input encoding
        $string = iconv( $this->getInputEncoding(), 'ISO-8859-1', $string );

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

    /**
     * Performs 'B' encoding on the string
     *
     * @param String $string The string to encode
     * @return String
     */
    public function bEncode ( $string )
    {
        // Yes, I realize that the iconv methods can handle this, but this method
        // can do one thing the iconv encode method cant: Handle encoding without
        // a header defined


        $string = \h2o\strval( $string );

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
                ( $this->headerExists() ? $this->header .": " : "" )
                . $encodingPart . $string ."?=";
        }

        // If there is a header to attach, then we need to figure out how many
        // characters will fit on the first line with it
        if ( $this->headerExists() ) {

            // If the header is so long it won't fit on a line (plus one for the colon)
            if ( $this->getLineLength() < strlen($this->getHeader()) + 1 ) {
                $err = new \h2o\Exception\Data(
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

            $prepend = $this->header .":";

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
            throw new \h2o\Exception\Data(
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

    /**
     * Performs 'B' encoding on the string
     *
     * @param String $string The string to encode
     * @return String
     */
    public function qEncode ( $string )
    {
        // Yes, I realize that the iconv methods can handle this, but this method
        // can do a few things the iconv encode method cant: Handle encoding without
        // a header defined, use the underscore as spaces to save on space.

        $string = \h2o\strval( $string );

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

            $result = ( $this->headerExists() ? $this->header .":" : "" );

            // If the header is so long it won't fit on a line (plus one for the colon)
            if ( $this->getLineLength() !== FALSE && $this->getLineLength() < strlen($result) + 1 ) {
                $err = new \h2o\Exception\Data(
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
            throw new \h2o\Exception\Data(
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

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function encode ( $string )
    {
        if ( $this->encoding == self::ENCODE_B )
            return $this->bEncode( $string );

        else if ( $this->encoding == self::ENCODE_Q )
            return $this->qEncode( $string );

        else if ( $this->encoding == self::ENCODE_RAW )
            return $this->rawEncode( $string );

        // At this point, we know that we're doing automatic selection

        // If we can raw encode, always select that option
        if ( self::canRawEncode($string) )
            return $this->rawEncode( $string );

        $bEncoded = $this->bEncode( $string );
        $qEncoded = $this->qEncode( $string );

        if ( strlen($bEncoded) <= strlen($qEncoded) )
            return $bEncoded;
        else
            return $qEncoded;
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function decode ( $string )
    {
        $string = \h2o\strval( $string );

        // Strip the header name off, if it exists
        $string = preg_replace('/^[\x21-\x7E]+\s*\:/', '', $string);

        $string = trim( $string );

        // Look for a character encoding definition. If you can't find one, just return the string
        if ( !preg_match('/=\?[a-z0-9\-\s\/]+?\?[BQ]\?.+\?\=/i', $string) )
            return $string;

        return iconv_mime_decode($string);
    }

}

?>