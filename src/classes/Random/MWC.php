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
 * @package Random
 */

namespace r8\Random;

/**
 * Multiply-With-Carry pseudo-random number generator
 */
class MWC extends \r8\Random
{

    /**
     * The constant to use while generating the next random number
     *
     * This is a safe prime, which means that (p-1)/2 is also a prime.
     *
     * @var Integer
     */
    const SCALAR = 2147354603;

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
     * @param \r8\Seed $seed The seed to feed into the random number generator
     */
    public function __construct ( \r8\Seed $seed )
    {
        if ( !extension_loaded('bcmath') )
            throw new \r8\Exception\Extension("BC Math extension required");

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
        $long = bcmul( self::SCALAR, $this->num );
        $long = bcadd( $long, $this->carry );

        // Pull the low order bits as the next random number
        $this->num = (int) bcmod( $long, self::MAX_INT );

        // Pull the high order bits as the next carry value
        $this->carry = (int) bcdiv( $long, self::MAX_INT );

        return $this->num;
    }

}

?>