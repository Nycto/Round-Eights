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
 * Provides Session wrapper around the cookies
 */
class Cookie implements \h2o\iface\Session
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
        return setCookie( $key, $value, $expire );
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
     * @return \h2o\iface\Session Returns a self reference
     */
    public function clear ( $key )
    {
        $this->setCookie( $key, null, time() - 3600 * 24 );
        unset( $this->data[$key] );
        return $this;
    }

    /**
     * Removes all values from the session
     *
     * @return \h2o\iface\Session Returns a self reference
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