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

namespace r8\iface;

/**
 * Interface for pseudo-random number generators
 */
interface Random
{

    /**
     * Returns the next random integer
     *
     * @return Integer
     */
    public function nextInteger ();

    /**
     * Returns the next random number as a float value between 0 and 1
     *
     * @return Float
     */
    public function nextFloat ();

    /**
     * Returns the next random number as a string
     *
     * @return String Returns a 40 character alpha-numeric string
     */
    public function nextString ();

    /**
     * Returns the next random number as an integer between the given minimum
     * and maximum
     *
     * @param Integer $min The minimum allowed value, inclusive
     * @param Integer $max The maximum value, inclusive
     * @return Integer
     */
    public function nextRange ( $min, $max );

}

?>