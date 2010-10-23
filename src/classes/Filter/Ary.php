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
 * Applies a given filter to every value in an array, non-recursively
 */
class Ary extends \r8\Filter
{

    /**
     * The filter that will be applied to each value
     */
    private $filter;

    /**
     * Constructor...
     *
     * @param \r8\iface\Filter The filter to apply to each value in the array
     */
    public function __construct( \r8\iface\Filter $filter )
    {
        $this->filter = $filter;
    }

    /**
     * Returns the filter loaded in this instance
     *
     * @return \r8\iface\Filter
     */
    public function getFilter ()
    {
        return $this->filter;
    }

    /**
     * Apply this filter to an array value
     *
     * @param Array $value The value to filter
     * @return Array Returns the filtered version
     */
    public function filter ( $value )
    {
        $value = (array) $value;

        foreach( $value AS $key => $val ) {
            $value[ $key ] = $this->filter->filter( $val );
        }

        return $value;
    }

}

