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
 * Validator collection that will return positive if ANY of the contained validators return positive
 */
class Any extends \r8\Validator\Collection
{

    /**
     * Runs all of the contained validators
     *
     * @param mixed $value The value being validated
     */
    protected function process ( $value )
    {
        $errors = array();

        foreach( $this->validators AS $valid ) {

            $result = $valid->validate( $value );

            if ( !$result instanceof \r8\Validator\Result )
                throw new \r8\Exception\Data( $result, "Validator Result", "Must be an instance of \r8\Validator\Result" );

            // Break out once any of the validators returns positively
            if ( $result->isValid() )
                return array();

            $errors = array_merge( $errors, $result->getErrors() );

        }

        return $errors;
    }

}

?>