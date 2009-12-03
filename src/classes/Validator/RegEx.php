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
 * Validates that a value matches a given regular expression
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative.
 *
 * This uses preg_match to execute the regular expression and does not add the
 * wrap characters. You must include those on instantiation
 */
class RegEx extends \r8\Validator
{

    /**
     * The Regular Expression to compare the value to
     *
     * @var String
     */
    protected $regex;

    /**
     * Constructor...
     *
     * @param String $regex The Regular Expression to compare the value to
     */
    public function __construct( $regex )
    {
        $regex = \r8\strVal( $regex );
        if ( \r8\isEmpty($regex) )
            throw new \r8\Exception\Argument(0, "Regular Expression", "Must not be empty");
        $this->regex = $regex;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( is_bool($value) || is_int($value) || is_float($value) || is_null($value) )
            $value = \r8\strval($value);

        if ( !is_string($value) )
            return "Must be a string";

        if ( !preg_match($this->regex, $value) )
            return "Must match the following regular expression: ". $this->regex;
    }

}

?>