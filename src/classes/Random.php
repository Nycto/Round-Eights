<?php
/**
 * Base class for random number generators
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
 * @package Random
 */

namespace h2o;

/**
 * Base class for random number generators
 */
abstract class Random implements \h2o\iface\Random
{

    /**
     * The maximum size the generated numbers can be
     *
     * @var Integer
     */
    const MAX_INT = 0x7fffffff;

    /**
     * Returns the next random number as a float value between 0 and 1
     *
     * @return Float
     */
    public function nextFloat ()
    {
        return round( $this->nextInteger() / self::MAX_INT, 14 );
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

    /**
     * Returns the next random number as an integer between the given minimum
     * and maximum
     *
     * @param Integer $min The minimum allowed value, inclusive
     * @param Integer $max The maximum value, inclusive
     * @return Integer
     */
    public function nextRange ( $min, $max )
    {
        return \h2o\num\intWrap( $this->nextInteger(), $min, $max );
    }

}

?>