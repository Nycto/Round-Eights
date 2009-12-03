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
 * @package Session
 */

namespace r8\Session;

/**
 * Provides Session wrapper around the cookies
 */
class Cookie implements \r8\iface\Session
{

    /**
     * A local datastore that keeps a copy of the cookie data
     *
     * This exists so that modifications to the cookies can be tracked
     * without modifying the global variable
     *
     * @var Array
     */
    private $data = array();

    /**
     * The unix timestamp representing when the cookies will expire
     *
     * @var Integer
     */
    private $expire;

    /**
     * Decodes an cookie value
     *
     * @param Mixed $input The input value
     * @return mixed
     */
    static private function decode ( $input )
    {
        if ( !is_string($input) )
            return $input;

        if ( $input === "b:0;" )
            return FALSE;

        $unsrl = @unserialize( $input );

        return $unsrl === FALSE ? $input : $unsrl;
    }

    /**
     * Constructor...
     *
     * @param Integer $expire The number of seconds you wish the cookies to survive.
     * 		If set to 0, the cookies will expire at the end of the user's session.
     */
    public function __construct ( $expire = 0 )
    {
        foreach ( $_COOKIE AS $key => $value )
        {
            $this->data[ $key ] = self::decode( $value );
        }

        $expire = (int) $expire;
        if ( $expire != 0 )
            $expire += time();

        $this->expire = $expire;
    }

    /**
     * Returns a value from the session
     *
     * @param String $key The key of the value to return
     * @return Mixed
     */
    public function get ( $key )
    {
        return isset( $this->data[ $key ] ) ? $this->data[ $key ] : null;
    }

    /**
     * An internal method that simply wraps the setCookie function
     *
     * This method exists for unit testing purposes. It can be mocked
     * to test assertions against the setCookie function
     *
     * @param String $key The cookie being set
     * @param String $value The value of the cookie
     * @param Integer $expire The expiration time
     * @return Boolean
     */
    protected function setCookie ( $key, $value, $expire )
    {
        // @codeCoverageIgnoreStart
        return setCookie( $key, $value, $expire );
        // @codeCoverageIgnoreEnd
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
        if ( $value === NULL )
            return $this->clear( $key );

        $this->setCookie(
            $key,
            is_string($value) || is_int($value) || is_float($value)
                ? $value : serialize($value),
            $this->expire
        );

        $this->data[ $key ] = $value;

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
        return isset( $this->data[ $key ] );
    }

    /**
     * Removes a specific value from the session
     *
     * @param String $key The key to remove
     * @return \r8\iface\Session Returns a self reference
     */
    public function clear ( $key )
    {
        $this->setCookie( $key, null, time() - 3600 * 24 );
        unset( $this->data[$key] );
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
        if ( !isset($this->data[$key]) )
            $temp = array();

        else if ( !is_array($this->data[$key]) )
            $temp = array( $this->data[$key] );

        else
            $temp = $this->data[$key];

        $temp[] = $value;

        $this->set( $key, $temp );

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
        if ( !isset($this->data[$key]) )
            return NULL;

        if ( !is_array($this->data[$key]) ) {
            $result = $this->data[$key];
            $this->clear( $key );
        }
        else {
            $temp = $this->data[$key];
            $result = array_pop( $temp );

            if ( empty($temp) )
                $this->clear( $key );
            else
                $this->set( $key, $temp );
        }

        return $result;
    }

    /**
     * Removes all values from the session
     *
     * @return \r8\iface\Session Returns a self reference
     */
    public function clearAll ()
    {
        foreach ( $this->data AS $key => $value )
        {
            $this->clear( $key );
        }
        return $this;
    }

    /**
     * Returns all the values in the session
     *
     * @return Array
     */
    public function getAll ()
    {
        return $this->data;
    }

}

?>