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
 * Verifies the integrity of another Transform object by prepending a cryptographic
 * hash to the transformed value
 */
class Verify implements \r8\iface\Transform
{
    
    /**
     * The Transform being decorated
     *
     * @var \r8\iface\Transform
     */
    private $wrapped;

    /**
     * Implementation of the PBKDF2 key derivation function as described in RFC 2898.
     *
     * This is essentially just a really thorough hashing algorithm
     *
     * PBKDF2 was published as part of PKCS #5 v2.0 by RSA Security. The standard is
     * also documented in IETF RFC 2898.
     *
     * @author Henry Merriam <php@henrymerriam.com>
     * @param string $string The value being hashed
     * @param string $salt The hashing salt
     * @param int $keyLength The derived key length (octets)
     * @param int $iterations The number of iterations to perform
     * @return string Returns the
     */
    static public function pbkdf2 ( $string, $salt, $keyLength = 32, $iterations = 1000 )
    {
        $string = \r8\strval( $string );
        $salt = \r8\strval( $salt );
        $keyLength = max( 1, \r8\numVal( $keyLength ) );
        $iterations = max( 1, (int) $iterations );

        $algo = "sha256";
        $hashLength = 32;

        if ( $keyLength > ( pow(2, 32) - 1 ) * $hashLength )
            throw new \r8\Exception\Argument( 3, "Key Length", "Derived key length is too long" );

        // number of derived key blocks to compute
        $max = ceil( $keyLength / $hashLength);

        $accum = null;

        for ($i = 1; $i <= $max; $i++) {

            $f = $u = hash_hmac($algo, $salt . pack('N', $i), $string, true);

            for ($j = 1; $j < $iterations; $j++) {
                $f ^= ($u = hash_hmac($algo, $u, $string, true));
            }

            // concatenate blocks of the derived key
            $accum .= $f;
        }

        // Shorten down the accumulated hash to the requested key length
        return substr($accum, 0, $keyLength);
    }

    /**
     * Constructor...
     *
     * @param \r8\iface\Transform $transform The transform object being wrapped
     */
    public function __construct ( \r8\iface\Transform $transform )
    {
        $this->transform = $transform;
    }

    /**
     * Transforms a string
     *
     * @param mixed $value The value to transform
     * @return mixed The result of the transformation process
     */
    public function to ( $string )
    {
    }

    /**
     * Reverses the transformation on a string
     *
     * @param mixed $value The value to reverse transform
     * @return mixed The original, untransformed value
     */
    public function from ( $string )
    {
    }

}

?>