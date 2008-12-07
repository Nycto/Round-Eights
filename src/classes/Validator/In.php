<?php
/**
 * Validation class
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
 * @package Validators
 */

namespace cPHP\Validator;

/**
 * Returns whether the validated value is in a preset list
 */
class In extends \cPHP\Validator
{

    /**
     * The list of valid values
     */
    protected $list;

    /**
     * Constructor...
     *
     * @param mixed $list The list of valid values
     */
    public function __construct ( $list = array() )
    {
        $this->setList( $list );
    }

    /**
     * Sets the list of valid values
     *
     * @param mixed $list The list of valid values
     * @return Object Returns a self reference
     */
    public function setList ( $list )
    {
        if ( !\cPHP\Ary::is( $list ) )
            throw new \cPHP\Exception\Argument( 0, "Valid Value List", "Must be an array or a traversable object" );

        $this->list = \cPHP\Ary::create( $list )->unique();

        return $this;
    }

    /**
     * Returns the list of valid objects
     *
     * @return Object Returns a \cPHP\Ary object of the valid values
     */
    public function getList ()
    {
        return clone $this->list;
    }

    /**
     * Tests whether a value is in the list of valid options
     *
     * @param mixed $value The value to test
     * @return Boolean Returns whether a given value is in the list
     */
    public function exists ( $value )
    {
        return $this->list->contains($value);
    }

    /**
     * Adds a value to the list of valid values
     *
     * @param mixed $value The value to add
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
     */
    public function remove ( $value )
    {
        $this->list = $this->list->without( $value )->values();
        return $this;
    }

    /**
     * Validates that the given value is in a given list
     *
     * @param mixed $value The value to validate
     * @return String Any errors encountered
     */
    protected function process ( $value )
    {
        if ( !$this->list->contains($value) )
            return "Invalid option";
    }

}

?>