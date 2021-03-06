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
 * @package Filters
 */

namespace r8\Filter;

/**
 * This funnels the multiple incarnations of "empty" in to a standard value
 *
 * If the value passed in ISN'T empty, it will return the value. Otherwise,
 * it returns the defined "standard" value
 */
class StandardEmpty extends \r8\Filter
{

    /**
     * Any flags to use while calling \r8\isEmpty
     *
     * @var Integer
     */
    protected $flags = 0;

    /**
     * The value returned when the filter input is empty
     *
     * @var Mixed
     */
    protected $value = NULL;

    /**
     * Constructor
     *
     * @param Mixed $value The value to return when the filter input is empty
     * @param Integer $flags Any flags to pass to isEmpty
     */
    public function __construct ( $value = NULL, $flags = 0 )
    {
        $this->setValue( $value );
        $this->setFlags( $flags );
    }

    /**
     * Returns the current flag
     *
     * @return Integer
     */
    public function getFlags ()
    {
        return $this->flags;
    }

    /**
     * Sets the value for the flags in this instance
     *
     * This unsets any existing values and replaces it with the given parameter
     *
     * @param Integer $flags
     * @return \r8\Filter\StandardEmpty Returns a self reference
     */
    public function setFlags ( $flags )
    {
        $this->flags = max( (int) $flags, 0 );
        return $this;
    }

    /**
     * Adds the value for the flags in this instance
     *
     * This adds the given flags in to the existing flags, leaving the current values set.
     *
     * @param Integer $flags
     * @return \r8\Filter\StandardEmpty Returns a self reference
     */
    public function addFlags ( $flags )
    {
        $flags = max( (int) $flags, 0 );
        $this->flags = $this->flags | $flags;
        return $this;
    }

    /**
     * Returns the standard empty value
     *
     * @return Mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Sets the standard empty value
     *
     * @param Mixed $value The new value
     * @return \r8\Filter\StandardEmpty Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Performs the filtering
     *
     * @param Mixed $value The value to filter
     * @return Mixed
     */
    public function filter ( $value )
    {
        return \r8\isEmpty( $value, $this->flags ) ? $this->value : $value;
    }

}


