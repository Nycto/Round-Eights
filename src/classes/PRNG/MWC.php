<?php
/**
 * A "Multiply-With-Carry" pseudo-random number generator
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
 * @package PRNG
 */

namespace cPHP\PRNG;

/**
 * Multiply-With-Carry pseudo-random number generator
 */
class MWC implements \cPHP\iface\PRNG
{

    /**
     * The constant to use while generating the next random number
     *
     * This is a safe prime, which means that (p-1)/2 is also a prime.
     *
     * @var Integer
     */
    const scalar = "2147354603";

    /**
     * The maximum size the generated numbers can be
     *
     * @var Integer
     */
    const base = 0x7fffffff;

    /**
     * The previously generated random number
     *
     * @var Integer
     */
    private $num;

    /**
     * The carry value used when generating random numbers
     *
     * The initial value of this property represents the initial
     * carry value. Note that this is a prime number
     *
     * @var Integer
     */
    private $carry = 22500011;

    /**
     * Constructor...
     *
     * @param \cPHP\PRNG\Seed $seed The seed to feed into the random number generator
     */
    public function __construct ( \cPHP\PRNG\Seed $seed )
    {
        if ( !extension_loaded('bcmath') )
            throw new Exception("BC Math required");

        $this->num = abs( $seed->getInteger() );

        // Throw away the first value
        $this->nextInteger();
    }

    /**
     * Returns the next random number in this sequence
     *
     * @return Integer
     */
    public function nextInteger ()
    {
        // Generate the 64 bit number to base the next random number off of
        $long = bcmul( self::scalar, $this->num );
        $long = bcadd( $long, $this->carry );

        // Pull the low order byte as the next random number
        $num = bcmod( $long, self::base );

        // Pull the high order byte as the next carry value
        $carry = bcdiv( $long, self::base );

        // Save these values so they can be used to generate the next number
        $this->num = abs( intval($num) );
        $this->carry = abs( intval($carry) );

        return $this->num;
    }

    /**
     * Returns the next random number as a float value between 0 and 1
     *
     * @return Float
     */
    public function nextFloat ()
    {
        return round( $this->nextInteger() / self::base, 14 );
    }

    /**
     * Returns the next random number as a string
     *
     * @return String Returns a 40 character alpha-numeric string
     */
    public function nextString ()
    {
        return sha1( $this->nextInteger() );
    }

}

?>