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
 * Allows you to register filters that will be run against specific array offsets
 *
 * This will ignore any offsets that do not have registered filters and return
 * them as they are. It will also ignore registered filters that don't have
 * corresponding offsets in the array being filtered.
 */
class AryOffset extends \r8\Filter
{

    /**
     * The filters to apply indexed by their matching offset
     *
     * @var Array An array of \r8\iface\filter objects
     */
    private $filters = array();

    /**
     * Constructor...
     *
     * @param Array A list of filters to apply indexed by the offsets to apply each filter to
     */
    public function __construct( array $filters = array() )
    {
        $this->import( $filters );
    }

    /**
     * Returns the list of filters loaded in this instance
     *
     * @return Array
     */
    public function getFilters ()
    {
        return $this->filters;
    }

    /**
     * Sets an index/filter pair in this instance
     *
     * This will overwrite any previous filters for the given index
     *
     * @param mixed $index The index this filter will be applied to
     * @param \r8\iface\Filter $filter The filter to apply to the given index
     * @return \r8\Filter\AryOffset Returns a self reference
     */
    public function setFilter ( $index, \r8\iface\Filter $filter )
    {
        $index = \r8\reduce( $index );
        $this->filters[ $index ] = $filter;
        return $this;
    }

    /**
     * Imports a list of filters in to this instance
     *
     * @param Array The list of filters to import indexed by the offsets to apply each filter to
     * @return \r8\Filter\AryOffset Returns a self reference
     */
    public function import ( array $filters )
    {
        foreach ( $filters AS $key => $value ) {
            if ( $value instanceof \r8\iface\Filter )
                $this->setFilter( $key, $value );
        }

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
        if ( !is_array( $value ) )
            return $value;

        foreach ( $this->filters AS $key => $filter ) {
            if ( isset($value[$key]) )
                $value[$key] = $filter->filter( $value[$key] );
        }

        return $value;
    }

}

?>