<?php
/**
 * Core filter interface
 *
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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace r8;

/**
 * Base Filtering class
 */
abstract class Filter implements \r8\iface\Filter
{

    /**
     * Static method for creating a new filtering instance
     *
     * This takes the called function and looks for a class under
     * the \r8\Filter namespace.
     *
     * @throws \r8\Exception\Argument Thrown if the filter class can't be found
     * @param String $filter The filter class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new \r8\Filter subclass
     */
    static public function __callStatic ( $filter, $args )
    {
        $filter = '\r8\Filter\\'. trim( \r8\strval($filter) );

        if ( !class_exists($filter, true) ) {
            throw new \r8\Exception\Argument(
                    0,
                    "Filter Class Name",
                    'Filter could not be found in \r8\Filter namespace'
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