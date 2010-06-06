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
 * Collects a list of filters into a single filter
 *
 * This will feed the result of each filter in to the next
 */
class Chain extends \r8\Filter
{

    /**
     * The list of filters to run through
     *
     * @var Array A list of \r8\iface\Filter objects
     */
    private $filters = array();

    /**
     * Constructor
     *
     * @param \r8\iface\Filter $filters... The list of filters being chained
     */
    public function __construct ()
    {
        if ( func_num_args() > 0 ) {
            $args = func_get_args();
            foreach ( $args AS $filter ) {
                if ( $filter instanceof \r8\iface\Filter )
                    $this->add( $filter );
            }
        }
    }

    /**
     * Removes all the filters from this instance
     *
     * @return \r8\Filter\Chain Returns a self reference
     */
    public function clear ()
    {
        $this->filters = array();
        return $this;
    }

    /**
     * Adds a new filter to this interface
     *
     * @param \r8\iface\Filter $filter The filter to add
     * @return \r8\Filter\Chain Returns a self reference
     */
    public function add ( \r8\iface\Filter $filter )
    {
        $this->filters[] = $filter;
        return $this;
    }

    /**
     * Returns the array of filters contained in this instance
     *
     * @return \r8\Filter\Chain Returns an Array object
     */
    public function getFilters ()
    {
        return $this->filters;
    }

    /**
     * Applies the contained filters to the given value and returns the results
     *
     * @param Mixed $value The value to filter
     * @return Mixed The result of the filtering
     */
    public function filter ( $value )
    {
        foreach ( $this->filters AS $filter ) {
            $value = $filter->filter( $value );
        }
        return $value;
    }

}

?>