<?php
/**
 * Encodes a string using base 64
 *
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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Encoding
 */

namespace r8\Transform;

/**
 * Encodes and decodes strings according to the Ascii85 specification
 *
 * This encoding algorithm is similar in purpose to Base64 encoding. It takes
 * a string of bytes and encodes them such that the resulting string only
 * uses a short list of characters. Specifically, it uses 85 characters.
 *
 * The strength of Ascii85 over Base64 lies in the output size. A Base64 encoded
 * string will be 33% longer than the original string -- for every 3 bytes in,
 * 4 come out. Ascii85 only increases string length by 25% -- for every 4 bytes
 * in, 5 come out. However, it uses a larger set of characters to represent
 * the data than Base64 does.
 *
 * For more information, see the Wikipedia article here:
 * http://en.wikipedia.org/wiki/Ascii85
 */
class Ascii85 implements \r8\iface\Transform\Encode
{

    /**
     * Whether to enable Z and Y compression
     *
     * @var Boolean
     */
    private $compress;

    /**
     * Whether to wrap the output in braces
     *
     * @var Boolean
     */
    private $wrap;

    /**
     * Constructor...
     *
     * @param Boolean $compress Whether Z and Y compression should be enabled
     * @param Boolean $wrap Whether to wrap the output in braces
     */
    public function __construct ( $compress = TRUE, $wrap = TRUE )
    {
        $this->compress = (bool) $compress;
        $this->wrap = (bool) $wrap;
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
        $length = strlen( $string );
        $result = "";

        $zGroup = chr(0) . chr(0) . chr(0) . chr(0);

        // Iterate over the string in groups of 4
        for ( $i = 0; $i < $length; $i += 4 ) {

            $quadruple = substr($string, $i, 4);

            if ( $this->compress ) {

                // Short circuit for sections that are made up of all spaces
                if ( $quadruple === "    " ) {
                    $result .= "y";
                    continue;
                }

                // Another compression short circuit... all null characters
                if ( $quadruple === $zGroup ) {
                    $result .= "z";
                    continue;
                }
            }

            // Convert each character into a binary string and combine then
            $tuple = "";
            foreach ( str_split( $quadruple, 1 ) AS $chr )
            {
                // We do this instead of bit shifting because PHP chokes when you
                // get into numbers approaching 2^32 - 1
                $tuple .= str_pad( decbin( ord( $chr ) ), 8, "0", STR_PAD_LEFT );
            }

            // If the substring is shorter than 4 characters, we need to ad it
            if ( $length - $i < 4 )
                $tuple = str_pad( $tuple, 32, "0", STR_PAD_RIGHT );

            $tuple = (float) bindec( $tuple );

            $quintuple = "";
            for ( $j = 4; $j >= 0; $j-- )
            {
                // we use fmod instead of % because it has better support for very large numbers
                $quintuple = chr( fmod($tuple, 85) + 33 ) . $quintuple;
                $tuple /= 85;
            }

            // If the substring is shorter than 4 characters, remove the padding
            if ( $length - $i < 4 )
                $quintuple = substr( $quintuple, 0, $length - $i + 1 );

            $result .= $quintuple;
        }

        return ( $this->wrap ? "<~" : "" )
            .$result
            .( $this->wrap ? "~>" : "" );
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function from ( $string )
    {
    }

}

?>