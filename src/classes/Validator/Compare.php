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
 * Takes a comparison operator and a value and validates the given value against it
 */
class Compare extends \r8\Validator
{

    /**
     * The operator to use for comparison
     *
     * @var string
     */
    protected $operator;

    /**
     * The value to compare against
     *
     * @var mixed
     */
    protected $versus;

    /**
     * Constructor...
     *
     * @param String $operator The operator to use for comparison
     * @param mixed $versus The value to compare against
     */
    public function __construct( $operator, $versus )
    {
        $operator = trim( \r8\strval($operator) );

        if ( !preg_match( '/^(?:<=?|>=?|={1,3}|<>|!={1,2})$/', $operator ) )
            throw new \r8\Exception\Argument( 0, "Comparison Operator", "Unsupported comparison operator" );

        $this->operator = $operator;

        $this->versus = $versus;
    }

    /**
     * Validates the given value
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {

        switch( $this->operator ) {

            case "<":
                if ($value >= $this->versus)
                    return "Must be less than ". $this->versus;
                break;

            case ">":
                if ($value <= $this->versus)
                    return "Must be greater than ". $this->versus;
                break;

            case "<=":
                if ($value > $this->versus)
                    return "Must be less than or equal to ". $this->versus;
                break;

            case ">=":
                if ($value < $this->versus)
                    return "Must be greater than or equal to ". $this->versus;
                break;

            case "===":
                if ($value !== $this->versus)
                    return "Must be exactly equal to ". $this->versus;
                break;

            case "==":
            case "=":
                if ($value != $this->versus)
                    return "Must be equal to ". $this->versus;
                break;

            case "!==":
                if ($value === $this->versus)
                    return "Must not be exactly equal to ". $this->versus;
                break;

            case "!=":
            case "<>":
                if ($value == $this->versus)
                    return "Must not be equal to ". $this->versus;
                break;

        }

    }

}

?>