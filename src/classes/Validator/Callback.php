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
 * Uses a given callback to validate a value
 *
 * The callback is invoked with one argument, which is the value being validated.
 * The result of the callback will be used as the error message for the validator.
 * Different types of return values will be treated different ways:
 *
 * Arrays and traversable objects will be converted to arrays, flattened, stringized
 * and compacted. Anything left will be considered an error message. If it turns
 * out empty, validation will pass
 *
 * Strings, Boolean, Null, False, Integers and Floats will be converted to strings.
 * If they are considered empty according to the "isEmpty" standards, the value
 * will pass validation. Otherwise, the string value will be used as the error message.
 */
class Callback extends \h2o\Validator
{

    /**
     * The callback that will be invoked
     */
    protected $callback;

    /**
     * Constructor...
     *
     * @param mixed $callback The callback to use for validation
     */
    public function __construct( $callback )
    {

        if ( !is_callable($callback) )
            throw new \h2o\Exception\Argument(0, "Callback", "Must be callable");

        $this->callback = $callback;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        return call_user_func( $this->callback, $value );
    }

}

?>