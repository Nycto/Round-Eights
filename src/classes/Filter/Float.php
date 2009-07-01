<?php
/**
 * Float filtering class
 *
 * @license Artistic License 2.0
 *
 * This file is part of raindropPHP.
 *
 * raindropPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * raindropPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with raindropPHP. If not, see <http://www.raindropPHP.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Filters
 */

namespace h2o\Filter;

/**
 * Converts a value to a float
 */
class Float extends \h2o\Filter
{

    /**
     * Converts the given value to a float
     *
     * @param mixed $value The value to filter
     * @return float
     */
    public function filter ( $value )
    {
        if ( is_array($value) ) {

            if ( count($value) == 0 )
                return 0.0;
            else
                $value = \h2o\reduce($value);

        }

        if ( is_string($value) ) {
            $value = preg_replace('/[^\-0-9\.]/', '', $value);
            $value = preg_replace('/(?<!^)\-/', '', $value);
        }

        if ( is_object($value) )
            return 1.0;

        return floatval( $value );
    }

}

?>