<?php
/**
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Validators
 */

namespace h2o\Validator;

/**
 * Validates that a given value is the same or shorter than a given length
 *
 * This will convert Boolean, Integers, Floats and Null to strings before
 * processing them. Anything else that isn't a string will cause validation to
 * return negative
 */
class MaxLength extends \h2o\Validator
{

    /**
     * The string length the value must be less than or equal to
     *
     * @var Integer
     */
    protected $length;

    /**
     * Constructor...
     *
     * @param Integer $length The string length the value must be less than or equal to
     *      This must be greater than or equal to 0. Any negative numbers will be set to 0
     */
    public function __construct( $length )
    {
        $this->length = max( intval($length), 0 );
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
            $value = \h2o\strval($value);

        if ( !is_string($value) )
            return "Must be a string";

        if ( strlen($value) > $this->length ) {
            return \h2o\str\pluralize(
                    "Must not be longer than ". $this->length ." character",
                    $this->length
                );
        }
    }

}

?>