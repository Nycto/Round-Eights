<?php
/**
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
 * @package Validators
 */

namespace cPHP\Validator;

/**
 * Validates that a given string is a valid php variable name
 *
 * This does NOT look for the dollar sign at the beginning of the string. In fact,
 * that will cause validation to fail. It is generally looking for an alpha-numeric
 * string that starts with a letter.
 *
 * This will fail for any data type that is not a string
 */
class Variable extends \cPHP\Validator
{

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !is_string($value) )
            return "Must be a string";

        if ( !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/', $value) )
            return "Must be a valid PHP variable name";
    }

}

?>