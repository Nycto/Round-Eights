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
 * @package HTML
 */

namespace r8;

/**
 * A place holder class for building HTML objects
 */
abstract class HTML
{

    /**
     * Static method for creating a new HTML object
     *
     * @param String $class The class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new object
     */
    static public function __callStatic ( $class, $args )
    {
        $class = '\r8\HTML\\'. trim( \r8\strval($class) );

        if ( !class_exists($class, true) ) {
            throw new \r8\Exception\Argument(
                    0,
                    "Class Name",
                    'Class could not be found in \r8\HTML namespace'
                );
        }

        if ( count($args) <= 0 ) {
            return new $class;
        }
        else if ( count($args) == 1 ) {
            return new $class( reset($args) );
        }
        else {
            $refl = new \ReflectionClass( $class );
            return $refl->newInstanceArgs( $args );
        }
    }

}

?>