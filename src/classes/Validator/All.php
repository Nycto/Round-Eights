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
 * Validator that requires all of its contained validators to return positively
 */
class All extends \h2o\Validator\Collection
{

    /**
     * Runs all of the contained validators
     *
     * @param mixed $value The value being validated
     * @return array Returns an array of errors
     */
    protected function process ( $value )
    {
        $errors = array();

        foreach( $this->validators AS $valid ) {

            $result = $valid->validate( $value );

            if ( !$result instanceof \h2o\Validator\Result )
                throw new \h2o\Exception\Data( $result, "Validator Result", "Must be an instance of \h2o\Validator\Result" );

            $errors = array_merge( $errors, $result->getErrors() );

        }

        return $errors;
    }

}

?>