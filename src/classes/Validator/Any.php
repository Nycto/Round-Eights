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
 * Validator collection that will return positive if ANY of the contained validators return positive
 */
class Any extends \cPHP\Validator\Collection
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

            if ( !$result instanceof \cPHP\Validator\Result )
                throw new \cPHP\Exception\Data( $result, "Validator Result", "Must be an instance of \cPHP\Validator\Result" );

            // Break out once any of the validators returns positively
            if ( $result->isValid() )
                return array();

            $errors = array_merge( $errors, $result->getErrors() );

        }

        return $errors;
    }

}

?>