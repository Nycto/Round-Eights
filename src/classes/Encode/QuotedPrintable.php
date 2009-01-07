<?php
/**
 * Encodes a string according to the Quoted Printable specifications layed out
 * in rfc 2045, here: http://tools.ietf.org/html/rfc2045
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
 * Encodes and decodes strings according to the Quoted-Printable specifications
 */
class QuotedPrintable implements \cPHP\iface\Encoder
{

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @result mixed The result of the encoding process
     */
    public function encode ( $string )
    {
        return quoted_printable_encode( \cPHP\strval($string) );
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @result mixed The original, unencoded value
     */
    public function decode ( $string )
    {
        return quoted_printable_decode( \cPHP\strval($string) );
    }

}

?>