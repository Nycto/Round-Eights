<?php
/**
 * Filter class for standardizing an empty value
 *
 * @license Artistic License 2.0
 *
 * This file is part of RaindropPHP.
 *
 * RaindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * RaindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with RaindropPHP. If not, see <http://www.RaindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace h2o\Filter;

/**
 * This funnels the multiple incarnations of "empty" in to a standard value
 *
 * If the value passed in ISN'T empty, it will return the value. Otherwise,
 * it returns the defined "standard" value
 */
class StandardEmpty extends \h2o\Filter
{

    /**
     * Any flags to use while calling \h2o\isEmpty
     */
    protected $flags = 0;

    /**
     * The value returned when the filter input is empty
     */
    protected $value = NULL;

    /**
     * Constructor
     *
     * @param mixed $value The value to return when the filter input is empty
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
     * @return Object Returns a self reference
     */
    public function setFlags ( $flags )
    {
        $this->flags = max( intval($flags), 0 );
        return $this;
    }

    /**
     * Adds the value for the flags in this instance
     *
     * This adds the given flags in to the existing flags, leaving the current values set.
     *
     * @param Integer $flags
     * @return Object Returns a self reference
     */
    public function addFlags ( $flags )
    {
        $flags = max( intval($flags), 0 );
        $this->flags = $this->flags | $flags;
        return $this;
    }

    /**
     * Returns the standard empty value
     *
     * @return mixed
     */
    public function getValue ()
    {
        return $this->value;
    }

    /**
     * Sets the standard empty value
     *
     * @param mixed $value The new value
     * @return object Returns a self reference
     */
    public function setValue ( $value )
    {
        $this->value = $value;
        return $this;
    }

    /**
     * Performs the filtering
     *
     * @param mixed $value The value to filter
     * @return mixed
     */
    public function filter ( $value )
    {
        if ( \h2o\isEmpty( $value, $this->flags ) )
            return $this->value;

        return $value;
    }

}

?>