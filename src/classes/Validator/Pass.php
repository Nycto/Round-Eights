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
 * @package Validators
 */

namespace r8\Validator;

/**
 * A validator that will always return successful
 */
class Pass implements \r8\iface\Validator
{

    /**
     * Performs the validation and returns the result
     *
     * @param Mixed $value The value to validate
     * @return \r8\Validator\Result
     */
    public function validate ( $value )
    {
        return new \r8\Validator\Result( $value );
    }

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @param Mixed $value The value to validate
     * @return Boolean
     */
    public function isValid ( $value )
    {
        return TRUE;
    }

    /**
     * Throws an exception if the given value doesn't validate
     *
     * @param Mixed $value The value to validate
     * @return \r8\Validator Returns a self reference
     */
    public function ensure ( $value )
    {
        return $this;
    }

}

