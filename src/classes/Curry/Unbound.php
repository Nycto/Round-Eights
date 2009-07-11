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
 * @package Curry
 */

namespace h2o\Curry;

/**
 * A base currying class for calling methods that aren't bound to objects
 */
abstract class Unbound extends \h2o\Curry
{

    /**
     * Internal function that actually executes the currying.
     *
     * It is given an array of arguments. It should call the method and return the results
     *
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    abstract protected function rawExec ( array $args = array() );

    /**
     * Calls the method using the contents of an array as the arguments
     *
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    public function apply ( array $args = array() )
    {
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... The arguments to pass to the callback
     * @return mixed Returns the result of the invokation
     */
    public function exec ()
    {
        $args = func_get_args();
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Calls the contained function with the given arguments
     *
     * @param mixed $args... Any arguments to apply to the function
     * @return mixed Returns the results of the invokation
     */
    public function __invoke ()
    {
        $args = func_get_args();
        return $this->rawExec( $this->collectArgs($args) );
    }

    /**
     * Method for use with the filtering objects. Invokes the contained method with the given value
     *
     * @param $value mixed The value to be filtered
     * @return mixed The result of the filtering
     */
    public function filter ( $value )
    {
        return $this->rawExec( $this->collectArgs( array($value) ) );
    }

}

?>