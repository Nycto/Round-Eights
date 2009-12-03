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
 * @package Validators
 */

namespace r8\Validator;

/**
 * Validates that a given value does not contain spaces, tabs, or newlines
 *
 * This will return positive for Boolean, Integers, Floats and Null
 */
class NoSpaces extends \r8\Validator
{

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( is_bool($value) || is_int($value) || is_float($value) || is_null($value) )
            return null;

        if ( !is_string($value) )
            return "Must be a string";

        if ( \r8\str\contains(' ', $value) )
            return "Must not contain any spaces";

        if ( \r8\str\contains("\t", $value) )
            return "Must not contain any tabs";

        if ( \r8\str\contains("\n", $value) || \r8\str\contains("\r", $value) )
            return "Must not contain any new lines";
    }

}

?>