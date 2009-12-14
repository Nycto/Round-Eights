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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Transform
 */

namespace r8\Transform;

/**
 * Encodes and decodes strings according to the base 64 specification
 */
class Base64 implements \r8\iface\Transform\Encode
{

    /**
     * Whether to break the specs and use only URL safe characters in
     * the encoded strings
     *
     * @var Boolean
     */
    private $urlSafe;

    /**
     * Constructor...
     *
     * @param Boolean $urlSafe Whether to break the specs and use only URL
     * 		safe characters in the encoded strings. This will also cause the
     * 		trailing "=" signs to be stripped off
     */
    public function __construct ( $urlSafe = FALSE )
    {
        $this->urlSafe = (bool) $urlSafe;
    }

    /**
     * Encodes a string
     *
     * @param mixed $value The value to encode
     * @return mixed The result of the encoding process
     */
    public function to ( $string )
    {
        $result = base64_encode( \r8\strval($string) );

        if ( $this->urlSafe )
            $result = \rtrim( \strtr( $result, '+/', '-_' ), "=" );

        return $result;
    }

    /**
     * Decodes an encoded string
     *
     * @param mixed $value The value to decode
     * @return mixed The original, unencoded value
     */
    public function from ( $string )
    {
        $string = \r8\strval($string);

        if ( $this->urlSafe )
            $string = \strtr( $string, '-_', '+/' );

        return base64_decode( $string );
    }

}

?>