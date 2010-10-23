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
 * @package Session
 */

namespace r8\Session;

/**
 * Provides Session access to an array by reference
 */
class Reference implements \r8\iface\Session
{

    /**
     * A reference to the array that the data is being stored in
     *
     * @var Array
     */
    private $ref;

    /**
     * Builds an instance from the global session object
     *
     * @param String $namespace The namespace to use within the global instance
     * @return \r8\Session\Reference
     */
    static public function fromSession ( $namespace )
    {
        @session_start();

        if ( !isset($_SESSION[ $namespace ]) || !is_array($_SESSION[ $namespace ]) )
            $_SESSION[ $namespace ] = array();

        return new self( $_SESSION[ $namespace ] );
    }

    /**
     * Constructor...
     *
     * @param Array $ref A reference to the array that the data is being stored in
     */
    public function __construct ( array &$ref )
    {
        $this->ref =& $ref;
    }

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key )
    {
        return isset( $this->ref[$key] ) ? $this->ref[$key] : null;
    }

    /**
     * Sets a value in the session
     *
     * @param String $key The key to set
     * @param Mixed $value The value to save
     * @return \r8\iface\Session Returns a self reference
     */
    public function set ( $key, $value )
    {
        $this->ref[ $key ] = $value;
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
        return isset( $this->ref[$key] );
    }

    /**
     * Removes a specific value from the session
     *
     * @param String $key The key to remove
     * @return \r8\iface\Session Returns a self reference
     */
    public function clear ( $key )
    {
        if ( array_key_exists( $key, $this->ref ) )
            unset( $this->ref[$key] );
        return $this;
    }

    /**
     * Treats the key as an array and pushes a new value onto the end of it
     *
     * @param String $key The key to push on to
     * @param Mixed $value The value to push
     * @return \r8\iface\Session Returns a self reference
     */
    public function push ( $key, $value )
    {
        if ( !isset($this->ref[$key]) )
            $this->ref[$key] = array();

        else if ( !is_array($this->ref[$key]) )
            $this->ref[$key] = array( $this->ref[$key] );

        $this->ref[$key][] = $value;

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
        if ( !isset($this->ref[$key]) )
            return NULL;

        if ( !is_array($this->ref[$key]) )
            $result = $this->ref[$key];
        else
            $result = array_pop( $this->ref[$key] );

        if ( !is_array($this->ref[$key]) || empty($this->ref[$key]) )
            unset( $this->ref[$key] );

        return $result;
    }

    /**
     * Removes all values from the session
     *
     * @return \r8\iface\Session Returns a self reference
     */
    public function clearAll ()
    {
        $this->ref = array();
        return $this;
    }

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ()
    {
        return $this->ref;
    }

}

