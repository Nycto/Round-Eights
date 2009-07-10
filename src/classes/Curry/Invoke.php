<?php
/**
 * Function Currying
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
 * @author James Frasca <james@Raindropphp.com>
 * @copyright Copyright 2008, James Frasca, All Rights Reserved
 * @package curry
 */

namespace h2o\Curry;

/**
 * A curry class for invoking object methods
 */
class Invoke extends \h2o\Curry
{

    /**
     * The name of the method to invoke
     */
    protected $method;

    /**
     * Constructor...
     *
     * @param String $method The name of the method to invoke
     * @param mixed $args... Any rightward arguments
     */
    public function __construct ( $method )
    {
        $method = trim( \h2o\strVal( $method ) );

        if ( !\h2o\Validator::Method()->isValid($method) )
            throw new \h2o\Exception\Argument( 0, "Method", "Invalid method name" );

        $this->method = $method;

        if ( func_num_args() > 1 ) {
            $args = func_get_args();
            array_shift( $args );
            $this->setRightByArray( $args );
        }
    }

    /**
     * Instantiates the current class with the given array of arguments and returns the new object
     *
     * @param Object $object The object to invoke this method against
     * @param $args Array The arguments to apply to the callback
     * @return Object Returns a new instance
     */
    protected function rawExec ( $object, array $args = array() )
    {
        if ( !is_object($object) )
            throw new \h2o\Exception\Argument( 0, "Object", "Must be an object" );

        // Take the easy outs if we can
        if ( count($args) <= 0 )
            return $object->{ $this->method }();

        else if ( count($args) == 1 )
            return $object->{ $this->method } ( reset($args) );

        return call_user_func_array(
                array( $object, $this->method ),
                $args
            );
    }

    /**
     * Invokes this method against a given object using the contents of an array
     * as the arguments
     *
     * @param Object $object The object whose method should be invoked
     * @param array $args The list of arguments to apply to this function
     * @return mixed Returns the results of the function call
     */
    public function apply ( $object, array $args = array() )
    {
        return $this->rawExec( $object, $this->collectArgs($args) );
    }

    /**
     * Invokes this method against the given object with the given arguments
     *
     * @param Object $object The object whose method should be invoked
     * @param mixed $args... The arguments to pass to the callback
     * @return mixed Returns the result of the invokation
     */
    public function exec ( $object )
    {
        $args = func_get_args();
        array_shift( $args );
        return $this->rawExec( $object, $this->collectArgs($args) );
    }

    /**
     * Invokes this method against the given object with the given arguments
     *
     * @param Object $object The object whose method should be invoked
     * @param mixed $args... Any arguments to apply to the function
     * @return mixed Returns the results of the invokation
     */
    public function __invoke ( $object )
    {
        $args = func_get_args();
        array_shift( $args );
        return $this->rawExec( $object, $this->collectArgs($args) );
    }

    /**
     * Method for use with the filtering objects. Invokes the contained method
     * against the given object
     *
     * @param Object $object The object whose method should be invoked
     * @return mixed The result of the filtering
     */
    public function filter ( $object )
    {
        return $this->rawExec( $object, $this->collectArgs(array()) );
    }

}

?>