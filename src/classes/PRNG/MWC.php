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
     * The integer to seed the algorithm with
     *
     * @var Integer
     */
    private $current;

    /**
     * Constructor...
     *
     * @param \cPHP\PRNG\Seed $seed The seed used to start the sequence of random numbers
     */
    public function __construct ( \cPHP\PRNG\Seed $seed )
    {
        $this->current = $seed->getInteger();
    }

    /**
     * Returns the next random integer
     *
     * @return Integer
     */
    public function nextIngeter ()
    {

    }

}