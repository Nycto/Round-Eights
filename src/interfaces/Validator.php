<?php
/**
 * Core Validator interface
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
 * @package Validator
 */

namespace cPHP\iface;

/**
 * Basic filter definition
 */
interface Validator
{

    /**
     * Takes a value, processes it, and returns an instance of Validator Results
     *
     * @param mixed $value The value to validate
     * @result object An instance of validator results
     */
    public function validate ( $value );

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @param mixed $value The value to validate
     * @return Boolean
     */
    public function isValid ( $value );

}

?>