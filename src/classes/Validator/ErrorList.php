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
 * Helper class for managing a list of errors
 */
class ErrorList
{

    /**
     * The list of errors in this instance
     *
     * @var array
     */
    private $errors = array();

    /**
     * Adds a new error to this instance
     *
     * @param String $message The error message to add
     * @return \r8\Validator\ErrorList Returns a self reference
     */
    public function addError ( $message )
    {
        $message = (string) $message;

        if ( \r8\isEmpty($message) )
            throw new \r8\Exception\Argument( 0, "Error Message", "Must Not Be Empty" );

        if ( !in_array($message, $this->errors) )
            $this->errors[] = $message;

        return $this;
    }

    /**
     * Adds multiple errors at once
     *
     * This method accepts any number of arguments. They will be flattened down,
     * converted to strings and added as errors
     *
     * @param String|Array $errors... Errors to add to this instance
     * @return \r8\Validator\ErrorList Returns a self reference
     */
    public function addErrors ( $errors )
    {
        $errors = func_get_args();
        $errors = \r8\ary\flatten( $errors );
        $errors = \r8\ary\compact( $errors );
        $errors = \array_unique( $errors );

        array_walk( $errors, array($this, "addError") );

        return $this;
    }

    /**
     * Returns the errors contained in this instance
     *
     * @return array
     */
    public function getErrors ()
    {
        return $this->errors;
    }

    /**
     * Clears all the errors from
     *
     * @return \r8\Validator\ErrorList Returns a self reference for chaining
     */
    public function clearErrors ()
    {
        $this->errors = array();
        return $this;
    }

    /**
     * Clears all other errors and sets
     *
     * @param String $message The error message to add
     * @return \r8\Validator\ErrorList Returns a self reference for chaining
     */
    public function setError ( $message )
    {
        return $this->clearErrors()->addError( $message );
    }

    /**
     * Returns whether or not this instance has any errors contained in it
     *
     * @return Boolean
     */
    public function hasErrors ()
    {
        return count( $this->errors ) > 0 ? TRUE : FALSE;
    }

    /**
     * Returns the first error contained in this instance
     *
     * @return String|Null Returns NULL if there aren't any errors in this instance
     */
    public function getFirstError ()
    {
        if ( count($this->errors) == 0 )
            return NULL;

        return reset( $this->errors );
    }

}

