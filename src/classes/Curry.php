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
 * @package Curry
 */

namespace cPHP;

/**
 * Base class for Argument Currying classes
 */
abstract class Curry implements \cPHP\iface\Filter
{

    /**
     * Any arguments to pass to curry to the left
     */
    protected $leftArgs = array();

    /**
     * Any arguments to pass to curry to the right
     */
    protected $rightArgs = array();

    /**
     * For slicing the input arguments, this is the offset.
     *
     * See array_slice for details
     */
    protected $offset = 0;

    /**
     * For slicing the input arguments, this is the length of the array to allow
     *
     * See array_slice for details
     */
    protected $length;

    /**
     * Static method for creating a new curry object
     *
     * This takes the called function and looks for a class under
     * the \cPHP\Curry namespace.
     *
     * @param String $curry The curry class to create
     * @param array $args Any constructor args to use during instantiation
     * @return Object Returns a new \cPHP\Curry subclass
     */
    static public function __callStatic ( $curry, $args )
    {
        $curry = "\\cPHP\\Curry\\". trim( \cPHP\strval($curry) );

        if ( !class_exists($curry, true) ) {
            throw new \cPHP\Exception\Argument(
                    0,
                    "Curry Class Name",
                    "Class could not be found in \\cPHP\\Curry namespace"
                );
        }

        if ( !\cPHP\kindOf( $curry, "\\cPHP\\Curry") ) {
            throw new \cPHP\Exception\Argument(
                    0,
                    "Curry Class Name",
                    "Class is not a child of \\cPHP\\Curry"
                );
        }

        if ( count($args) <= 0 ) {
            return new $curry;
        }
        else if ( count($args) == 1 ) {
            return new $curry( reset($args) );
        }
        else {
            $refl = new \ReflectionClass( $curry );
            return $refl->newInstanceArgs( $args );
        }
    }

    /**
     * Sets the leftward arguments
     *
     * @param mixed $args... Any arguments to curry to the left
     * @return object Returns a self reference
     */
    public function setLeft ()
    {
        $args = func_get_args();
        $this->leftArgs = array_values( $args );
        return $this;
    }

    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setLeftByArray ( array $args = array() )
    {
        $this->leftArgs = array_values( $args );
        return $this;
    }

    /**
     * Returns the leftward argument list
     *
     * @return Array
     */
    public function getLeft ()
    {
        return $this->leftArgs;
    }

    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearLeft ()
    {
        $this->leftArgs = array();
        return $this;
    }

    /**
     * Sets the rightward arguments
     *
     * @param mixed $args... Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRight ()
    {
        $args = func_get_args();
        $this->rightArgs = array_values( $args );
        return $this;
    }

    /**
     * Sets the rightward arguments from an array
     *
     * @param mixed $args Any arguments to curry to the right
     * @return object Returns a self reference
     */
    public function setRightByArray ( array $args = array() )
    {
        $this->rightArgs = array_values( $args );
        return $this;
    }

    /**
     * Returns the rightward argument list
     *
     * @return Array
     */
    public function getRight ()
    {
        return $this->rightArgs;
    }

    /**
     * Removes any rightward arguments
     *
     * @return object Returns a self reference
     */
    public function clearRight ()
    {
        $this->rightArgs = array();
        return $this;
    }

    /**
     * Clears both the left and right arguments
     *
     * @return object Returns a self reference
     */
    public function clearArgs ()
    {
        return $this->clearRight()->clearLeft();
    }

    /**
     * Set the start offset used to slice up the call arguments
     *
     * @param Integer $offset
     * @return object Returns a self reference
     */
    public function setOffset ( $offset )
    {
        $this->offset = intval($offset);
        return $this;
    }

    /**
     * Returns the argument slicing offset
     *
     * @return Integer
     */
    public function getOffset ()
    {
        return $this->offset;
    }

    /**
     * Returns the argument slicing offset
     *
     * @return object Returns a self reference
     */
    public function clearOffset ()
    {
        $this->offset = 0;
        return $this;
    }

    /**
     * Set the length limit for slicing up the call arguments
     *
     * @param Integer $limit
     * @return object Returns a self reference
     */
    public function setLimit ( $limit )
    {
        $this->limit = intval($limit);
        return $this;
    }

    /**
     * Returns whether the argument slicing limit is set
     *
     * @return Boolean
     */
    public function issetLimit ()
    {
        return isset($this->limit);
    }

    /**
     * Returns the argument slicing limit
     *
     * @return FALSE|Integer Returns FALSE if no limit is set
     */
    public function getLimit ()
    {
        if ( !$this->issetLimit() )
            return FALSE;
        else
            return $this->limit;
    }

    /**
     * Clears the argument slicing limit
     *
     * @return object Returns a self reference
     */
    public function clearLimit ()
    {
        unset( $this->limit );
        return $this;
    }

    /**
     * Clears both the argument slicing limit and the offset
     *
     * @return object Returns a self reference
     */
    public function clearSlicing ()
    {
        return $this->clearLimit()->clearOffset();
    }

    /**
     * Clears all the settings from this instance
     *
     * @return object Returns a self reference
     */
    public function clear ()
    {
        return $this->clearArgs()->clearSlicing();
    }

    /**
     * Applies the slicing and combines the given arguments with the left args and right args
     *
     * @param array $args The arguments to curry
     * @return Returns the arguments to pass to the function
     */
    public function collectArgs ( array $args )
    {

        // Slicing is only needed if the offset is not 0, or they have inflicted a length limit
        if ( $this->offset != 0 || isset($this->limit) ) {

            if ( isset($this->limit) )
                $args = array_slice( $args, $this->offset, $this->limit );
            else
                $args = array_slice( $args, $this->offset );
        }

        return array_merge( $this->leftArgs, $args, $this->rightArgs );
    }

    /**
     * Internal function that actually executs the currying.
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