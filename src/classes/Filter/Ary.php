<?php
/**
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace h2o\Filter;

/**
 * Applies a given filter to every value in an array, non-recursively
 */
class Ary extends \h2o\Filter
{

    /**
     * The filter that will be applied to each value
     */
    private $filter;

    /**
     * Constructor...
     *
     * @param Object The filter to apply to each value in the array
     */
    public function __construct( \h2o\iface\Filter $filter )
    {
        $this->setFilter( $filter );
    }

    /**
     * Returns the filter loaded in this instance
     *
     * @return Object
     */
    public function getFilter ()
    {
        return $this->filter;
    }

    /**
     * Sets the filter that will be applied to each value of a filtered array
     *
     * @param Object The filter to load in to this instance
     * @return Object Returns a self reference
     */
    public function setFilter ( \h2o\iface\Filter $filter )
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * Apply this filter to an array value
     *
     * @param Array $value The value to filter
     * @return Array Returns the filtered version
     */
    public function filter ( $value )
    {
        $value = \h2o\arrayVal($value);

        foreach( $value AS $key => $val ) {
            $value[ $key ] = $this->filter->filter( $val );
        }

        return $value;
    }

}

?>