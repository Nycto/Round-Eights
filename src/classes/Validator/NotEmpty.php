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
 * Validates that a value is not considered empty using the isEmpty function
 */
class NotEmpty extends \r8\Validator
{

    /**
     * Any flags to pass to the isEmpty function
     *
     * @see \r8\isEmpty()
     * @var Integer
     */
    protected $flags = 0;

    /**
     * Constructor...
     *
     * @param Integer $flags Any flags to pass to the isEmpty function. For
     *      more details, take a look at that function
     */
    public function __construct ( $flags = 0 )
    {
        $this->flags = max( intval($flags), 0 );
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( \r8\isEmpty($value, $this->flags) )
            return "Must not be empty";
    }

}

?>