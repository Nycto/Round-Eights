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
 * Returns whether the validated value is in a preset list
 */
class In extends \r8\Validator
{

    /**
     * The list of valid values
     *
     * @var array
     */
    protected $list;

    /**
     * Constructor...
     *
     * @param Array $list The list of valid values
     */
    public function __construct ( array $list = array() )
    {
        $this->setList( $list );
    }

    /**
     * Sets the list of valid values
     *
     * @param Array $list The list of valid values
     * @return \r8\Validator\In Returns a self reference
     */
    public function setList ( array $list )
    {
        $this->list = array_unique( $list );

        return $this;
    }

    /**
     * Returns the list of valid objects
     *
     * @return array Returns a list of the valid values
     */
    public function getList ()
    {
        return $this->list;
    }

    /**
     * Tests whether a value is in the list of valid options
     *
     * @param mixed $value The value to test
     * @return Boolean Returns whether a given value is in the list
     */
    public function exists ( $value )
    {
        return in_array($value, $this->list);
    }

    /**
     * Adds a value to the list of valid values
     *
     * @param mixed $value The value to add
     * @return \r8\Validator\In Returns a self reference
     */
    public function add ( $value )
    {
        if ( !$this->exists($value) )
            $this->list[] = $value;

        return $this;
    }

    /**
     * Removes a value to the list of valid options
     *
     * @param mixed $value The value to remove
     * @return \r8\Validator\In Returns a self reference
     */
    public function remove ( $value )
    {
        $this->list = array_values( \r8\ary\without($this->list, $value ) );
        return $this;
    }

    /**
     * Validates that the given value is in a given list
     *
     * @param mixed $value The value to validate
     * @return String|NULL Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !in_array($value, $this->list) )
            return "Invalid option";
    }

}

