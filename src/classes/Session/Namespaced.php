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
 * @package Random
 */

namespace h2o\Session;

/**
 * Provides an interface for treating a subsection of a Session as a first class Session
 */
class Namespaced extends \h2o\Session\Decorator
{

    /**
     * The namespace to nest values within
     *
     * @var String
     */
    private $namespace;

    /**
     * Constructor...
     *
     * @param String $namespace The namespace to nest values within
     * @param \h2o\iface\Session $decorated The object being decorated
     */
    public function __construct ( $namespace, \h2o\iface\Session $decorated )
    {
        parent::__construct( $decorated );

        $namespace = \h2o\indexVal( $namespace );

        if ( \h2o\IsEmpty($namespace) )
            throw new \h2o\Exception\Argument( 0, "Namespace", "Must be a valid key" );

        $this->namespace = $namespace;
    }

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key )
    {
        $root = parent::get( $this->namespace );

        if ( is_array($root) && isset($root[$key]) )
            return $root[$key];
        else
            return NULL;
    }

    /**
     * Sets a value in the session
     *
     * @param String $key The key to set
     * @param Mixed $value The value to save
     * @return \h2o\iface\Session Returns a self reference
     */
    public function set ( $key, $value )
    {
        $root = parent::get( $this->namespace );

        if ( !is_array($root) )
            $root = array();

        $root[$key] = $value;

        parent::set( $this->namespace, $root );

        return $this;
    }

    /**
     * Returns whether a value has been set in the session
     *
     * @param String $key The key to test
     * @return Boolean
     */
    public function exists ( $key )
    {
        $root = parent::get( $this->namespace );
        return is_array($root) && isset($root[$key]);
    }

    /**
     * Removes a specific value from the session
     *
     * @param String $key The key to remove
     * @return \h2o\iface\Session Returns a self reference
     */
    public function clear ( $key )
    {
        $root = parent::get( $this->namespace );

        if ( !is_array($root) ) {
            parent::set( $this->namespace, array() );
        }
        else if ( array_key_exists( $key, $root) ) {
            unset( $root[$key] );
            parent::set( $this->namespace, $root );
        }

        return $this;
    }

    /**
     * Treats the key as an array and pushes a new value onto the end of it
     *
     * @param String $key The key to push on to
     * @param Mixed $value The value to push
     * @return \h2o\iface\Session Returns a self reference
     */
    public function push ( $key, $value )
    {
        $root = parent::get( $this->namespace );

        if ( !is_array($root) )
            $root = array();

        if ( !isset($root[$key]) )
            $root[$key] = array();

        else if ( !is_array($root[$key]) )
            $root[$key] = array( $root[$key] );

        $root[$key][] = $value;

        parent::set( $this->namespace, $root );

        return $this;
    }

    /**
     * Treats the key as an array and pops value from the end of it
     *
     * @param String $key The key to pop a value off of
     * @return Mixed Returns the popped value
     */
    public function pop ( $key )
    {
        $root = parent::get( $this->namespace );

        if ( !is_array($root) ) {
            parent::set( $this->namespace, array() );
            return NULL;
        }

        else if ( !isset($root[$key]) ) {
            return NULL;
        }

        if ( !is_array($root[$key]) ) {
            $result = $root[$key];
            unset( $root[$key] );
        }
        else {
            $result = array_pop( $root[$key] );
            if ( empty($root[$key]) )
                unset( $root[$key] );
        }

        parent::set( $this->namespace, $root );

        return $result;
    }

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ()
    {
        $root = parent::get( $this->namespace );
        return is_array($root) ? $root : array();
    }

    /**
     * Removes all values from the session
     *
     * @return \h2o\iface\Session Returns a self reference
     */
    public function clearAll ()
    {
        parent::set( $this->namespace, array() );
        return $this;
    }

}

?>