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
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package Template
 */

namespace r8;

/**
 * A base Template class that provides data access
 */
abstract class Template extends \r8\Template\Access implements \r8\iface\Template
{

    /**
     * Allows you to instantiate a template in-line by calling it like a static method
     *
     * @param String $class The template class to instantiate
     * @param Array $args Any arguments to pass to the template
     * @return Object Returns a new \r8\Template object of the given type
     */
    static public function __callStatic ( $class, $args )
    {
        $class = "\\r8\\Template\\". trim( \r8\strval($class) );

        if ( !class_exists( $class, true ) ) {
            throw new \r8\Exception\Argument(
                    0,
                    "Template Class Name",
                    "Class could not be found in \\r8\\Template namespace"
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