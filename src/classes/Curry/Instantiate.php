<?php
/**
 * Function Currying
 *
 * @license Artistic License 2.0
 *
 * This file is part of commonPHP.
 *
 * commonPHP is free software: you can redistribute it and/or modify
 * it under the terms of the Artistic License as published by
 * the Open Source Initiative, either version 2.0 of the License, or
 * (at your option) any later version.
 *
 * commonPHP is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * Artistic License for more details.
 *
 * You should have received a copy of the Artistic License
 * along with commonPHP. If not, see <http://www.commonphp.com/license.php>
 * or <http://www.opensource.org/licenses/artistic-license-2.0.php>.
 *
 * @author James Frasca <james@commonphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package curry
 */

namespace cPHP\Curry;

/**
 * A curry class for handling class instantiation
 */
class Instantiate extends \cPHP\Curry
{

    /**
     * The class being instantiated
     */
    protected $class;

    /**
     * Constructor...
     *
     * @param mixed $class The class to instantiate
     * @param mixed $args... Any rightward arguments
     */
    public function __construct ( $class )
    {
        if ( !class_exists($class, TRUE) )
            throw new \cPHP\Exception\Argument( 0, "Class", "Class does not exist" );

        $this->class = $class;

        if ( func_num_args() > 1 ) {
            $args = func_get_args();
            array_shift( $args );
            $this->setRightByArray( $args );
        }
    }

    /**
     * Instantiates the current class with the given array of arguments and returns the new object
     *
     * @param $args Array The arguments to apply to the callback
     * @return Object Returns a new instance
     */
    protected function rawExec ( array $args = array() )
    {
        // Take the easy outs if we can
        if ( count($args) <= 0 )
            return new $this->class;

        else if ( count($args) == 1 )
            return new $this->class( reset($args) );

        // Otherwise, we need to use a reflection class
        $refl = new \ReflectionClass( $this->class );

        return $refl->newInstanceArgs( $args );
    }

}

?>