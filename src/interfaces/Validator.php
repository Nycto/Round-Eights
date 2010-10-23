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
 * @package Validator
 */

namespace r8\iface;

/**
 * Core validator definition
 */
interface Validator
{

    /**
     * Takes a value, processes it, and returns an instance of Validator Results
     *
     * @param mixed $value The value to validate
     * @return \r8\Validator\Result
     */
    public function validate ( $value );

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @param mixed $value The value to validate
     * @return Boolean
     */
    public function isValid ( $value );

    /**
     * Throws an exception if the given value doesn't validate
     *
     * @throws \r8\Exception\Data Thrown if validation fails
     * @param Mixed $value The value to validate
     * @return \r8\iface\Validator Returns a self reference
     */
    public function ensure ( $value );

}

