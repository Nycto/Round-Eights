<?php
/**
 * Core filter interface
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
 * @author James Frasca <James@RaindropPHP.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace h2o;

/**
 * Base Filtering class
 */
abstract class Filter implements \h2o\iface\Filter
{

    /**
     * Static method for creating a new filtering instance
     *
     * This takes the called function and looks for a class under
     * the \h2o\Filter namespace.
     *
     * @throws \h2o\Exception\Argument Thrown if the filter class can't be found
     * @param String $filter The filter class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new \h2o\Filter subclass
     */
    static public function __callStatic ( $filter, $args )
    {
        $filter = "\h2o\\Filter\\". trim( \h2o\strval($filter) );

        if ( !class_exists($filter, true) ) {
            throw new \h2o\Exception\Argument(
                    0,
                    "Filter Class Name",
                    "Filter could not be found in \h2o\Filter namespace"
                );
        }

        if ( count($args) <= 0 ) {
            return new $filter;
        }
        else if ( count($args) == 1 ) {
            return new $filter( reset($args) );
        }
        else {
            $refl = new \ReflectionClass( $filter );
            return $refl->newInstanceArgs( $args );
        }

    }

    /**
     * Magic method to allow this instance to be invoked like a function.
     *
     * Causes the filtering to happen as if the filter method was invoked
     *
     * @param mixed $value The value to filter
     * @return mixed The result of the filtering
     */
    public function __invoke( $value )
    {
        return $this->filter( $value );
    }

}

?>