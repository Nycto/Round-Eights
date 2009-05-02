<?php
/**
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
 * @package FileFinder
 */

namespace cPHP\Cache;

/**
 * Adapter for the Memcache extension to the standardized cache interface
 */
class Memcache implements \cPHP\iface\Cache
{

    /**
     * The host of the Memcache server
     *
     * @var String
     */
    private $host;

    /**
     * The port to connect to
     *
     * @var Integer
     */
    private $port;

    /**
     * Whether to open a persistent connection
     *
     * @var Boolean
     */
    private $persistent;

    /**
     * The connection timeout value in seconds
     *
     * @var Integer
     */
    private $timeout;

    /**
     * The actual connection to the server
     *
     * @var \Memcache
     */
    private $link;

    /**
     * Constructor...
     *
     * @param String $host The host of the Memcache server
     * @param Integer $port The port to connect to
     * @param Boolean $persistent Whether to connect with a persistent connection
     * @param Integer $timeout The connection timeout value in seconds
     */
    public function __construct ( $host = "127.0.0.1", $port = 11211, $persistent = FALSE, $timeout = 1 )
    {
        $this->host = trim( \cPHP\strval( $host ) );
        if ( \cPHP\isEmpty($this->host) )
            throw new \cPHP\Exception\Argument(0, "Memcache Host", "Must not be empty");

        $this->port = intval( $port );
        if ( $this->port < 0 )
            throw new \cPHP\Exception\Argument(1, "Memcache Port", "Must be at least 0");

        $this->persistent = \cPHP\boolval( $persistent );

        $this->timeout = intval( $timeout );
        if ( $this->timeout <= 0 )
            throw new \cPHP\Exception\Argument(1, "Memcache Port", "Must be greater than 0");
    }

    /**
     * Destructor...
     *
     * @return null
     */
    public function __destruct ()
    {
        $this->disconnect();
    }

    /**
     * Returns whether a connection is currently open
     *
     * @return Boolean
     */
    public function isConnected ()
    {
        return isset($this->link);
    }

    /**
     * Opens the memcache connection
     *
     * @return \cPHP\Cache\Memcache Returns a self reference
     */
    public function connect ()
    {
        // If we are already connected
        if ( $this->isConnected() )
            return $this;

        $link = new \Memcache;

        // Connect with persistence, if they requested it
        if ( $this->persistent )
            $result = @$link->pconnect( $this->host, $this->port, $this->timeout );
        else
            $result = @$link->connect( $this->host, $this->port, $this->timeout );

        // If an error occured while connect, translate it to an exception
        if ( !$result ) {
            $error = \error_get_last();
            throw new \cPHP\Exception\Memcache\Connection(
                    $error['message'],
                    $error['type']
                );
        }

        // Now that we have verified the connection, save it
        $this->link = $link;

        return $this;
    }

    /**
     * Closes this memcache connection
     *
     * @return \cPHP\Cache\Memcache Returns a self reference
     */
    function disconnect ()
    {
        // Only close the connection if it is open
        if ( $this->isConnected() ) {
            $this->link->close();
            $this->link = null;
        }

        return $this;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return \cPHP\Cache\Memcache Returns a self reference
     */
    public function set ( $key, $value )
    {
        $this->connect();
        $this->link->set($key, $value);
        return $this;
    }

    /**
     * Returns a cached value based on it's key
     *
     * @param String $key The value to retrieve
     * @return mixed Returns the cached value
     */
    public function get ( $key )
    {
        $this->connect();
        return $this->link->get($key);
    }

    /**
     * Returns a cached value based on it's key
     *
     * This returns a cached value in the form of an object. This object will allow
     * you to run an update on the value with the clause that it shouldn't be
     * changed if it has changed since it was retrieved. This can be used to
     * prevent race conditions.
     *
     * @param String $key The value to retrieve
     * @return Object A cPHP\Cache\Value object
     */
    public function getForUpdate ( $key )
    {

    }

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param cPHP\Cache\Result $result A result object that was returned by
     *      the getForUpdate method
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function setIfSame ( \cPHP\Cache\Result $result, $value )
    {

    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function add ( $key, $value )
    {

    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @return Object Returns a self reference
     */
    public function replace ( $key, $value )
    {

    }

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to append
     * @return Object Returns a self reference
     */
    public function append ( $key, $value )
    {

    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @return Object Returns a self reference
     */
    public function prepend ( $key, $value )
    {

    }

    /**
     * Increments a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function increment ( $key )
    {

    }

    /**
     * Decrements a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function decrement ( $key )
    {

    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return Object Returns a self reference
     */
    public function delete ( $key )
    {

    }

    /**
     * Deletes all values in the cache
     *
     * @return Object Returns a self reference
     */
    public function flush ()
    {

    }

}

?>