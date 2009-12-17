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

namespace r8;

/**
 * This provides an interface for comparing a value to a set of parameters
 */
abstract class Validator extends \r8\Validator\ErrorList implements \r8\iface\Validator
{

    /**
     * Static method for creating a new validator instance
     *
     * This takes the called function and looks for a class under
     * the \r8\Validator namespace.
     *
     * @throws \r8\Exception\Argument Thrown if the validator class can't be found
     * @param String $validator The validator class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new \r8\Validator subclass
     */
    static public function __callStatic ( $validator, $args )
    {
        $validator = '\r8\Validator\\'. trim( \r8\strval($validator) );

        if ( !class_exists($validator, true) ) {
            throw new \r8\Exception\Argument(
                    0,
                    "Validator Class Name",
                    'Validator could not be found in \r8\Validator namespace'
                );
        }

        if ( !\r8\kindOf( $validator, '\r8\iface\Validator') ) {
            throw new \r8\Exception\Argument(
                    0,
                    "Validator Class Name",
                    'Class does not implement \r8\iface\Validator'
                );
        }

        if ( count($args) <= 0 ) {
            return new $validator;
        }
        else if ( count($args) == 1 ) {
            return new $validator( reset($args) );
        }
        else {
            $refl = new ReflectionClass( $validator );
            return $refl->newInstanceArgs( $args );
        }
    }

    /**
     * Performs the validation and returns the result
     *
     * @param mixed $value The value to validate
     * @return Object Returns an instance of \r8\Validator\Result
     */
    public function validate ( $value )
    {
        // Invoke the internal validator
        $result = $this->process( $value );

        if ( $result instanceof \Traversable )
            $result = \iterator_to_array( $result );

        // Normalize the results if it is an array
        if ( \is_array($result) ) {
            $result = \r8\ary\flatten( $result );
            $result = \array_map( '\r8\\strval', $result );
            $result = \r8\ary\compact( $result );
        }

        elseif ( $result instanceof \r8\Validator\Result )
            $result = $result->getErrors();

        elseif ( is_null($result) || is_bool($result) || $result === 0 || $result === 0.0 )
            $result = null;

        else
            $result = \r8\strval( $result );

        // Boot up the results of the validation process
        $output = new \r8\Validator\Result( $value );

        // If the internal validator returned a non-empty value
        // (either an array with values or a non-blank string)
        if ( !\r8\isEmpty($result) ) {

            // If this validator is hooked up with a set of custom error messages,
            // use them instead of what the result returned
            if ( $this->hasErrors() )
                $output->addErrors( $this->getErrors() );
            else
                $output->addErrors( $result );

        }

        return $output;
    }

    /**
     * Runs the validation and returns whether the value passes or not
     *
     * @param mixed $value The value to validate
     * @return Boolean
     */
    public function isValid ( $value )
    {
        return $this->validate( $value )->isValid();
    }

    /**
     * Throws an exception if the given value doesn't validate
     *
     * @param mixed $value The value to validate
     * @return Object Returns a self reference
     */
    public function ensure ( $value )
    {
        $result = $this->validate( $value );

        if ( !$result->isValid() ) {

            throw new \r8\Exception\Data(
                    $value,
                    "Validated Value",
                    $result->getFirstError()
                );

        }

        return $this;
    }

    /**
     * The function that actually performs the validation
     *
     * @param mixed $value It will be given the value to validate
     * @return mixed Should return any errors that are encountered.
     *      This can be an array, a string, a \r8\Validator\Result instance
     */
    abstract protected function process ($value);

}

?>