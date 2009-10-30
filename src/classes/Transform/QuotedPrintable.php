<?php
/**
 * Encodes a string according to the Quoted Printable specifications layed out
 * in rfc 2045, here: http://tools.ietf.org/html/rfc2045
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Encoding
 */

namespace h2o\Transform;

/**
 * Encodes and decodes strings according to the Quoted-Printable specifications
 */
class QuotedPrintable implements \h2o\iface\Transform\Encode
{

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function encode ( $string )
    {
        return quoted_printable_encode( \h2o\strval($string) );
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function decode ( $string )
    {
        return quoted_printable_decode( \h2o\strval($string) );
    }

}

?>