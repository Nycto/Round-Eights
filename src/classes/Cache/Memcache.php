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
 * @package Cache
 */

namespace r8\Cache;

/**
 * Adapter for the Memcache extension to the standardized cache interface
 */
class Memcache extends \r8\Cache\Base
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
        if ( !extension_loaded('memcache') )
            throw new \r8\Exception\Extension("memcache", "Extension is not loaded");

        $this->host = trim( (string) $host );
        if ( \r8\isEmpty($this->host) )
            throw new \r8\Exception\Argument(0, "Memcache Host", "Must not be empty");

        $this->port = (int) $port;
        if ( $this->port < 0 )
            throw new \r8\Exception\Argument(1, "Memcache Port", "Must be at least 0");

        $this->persistent = (bool) $persistent;

        $this->timeout = (int) $timeout;
        if ( $this->timeout <= 0 )
            throw new \r8\Exception\Argument(1, "Memcache Port", "Must be greater than 0");
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
     * @return \r8\Cache\Memcache Returns a self reference
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
            throw new \r8\Exception\Memcache\Connection(
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
     * @return \r8\Cache\Memcache Returns a self reference
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
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $this->connect();
        if ( $value === NULL || $value === FALSE )
            $this->link->delete( $key );
        else
            $this->link->set( $key, $value, false, max( 0, (int) $expire ) );
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
        $result = $this->link->get($key);

        if ( $result === FALSE || $result === NULL )
            return NULL;
        else if ( ctype_digit($result) )
            return \r8\numVal( $result );
        else
            return $result;
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function delete ( $key )
    {
        $this->connect();
        $this->link->delete($key);
        return $this;
    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 )
    {
        $this->connect();
        $this->link->add( $key, $value, false, max( 0, (int) $expire ) );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        $this->connect();
        $this->link->replace( $key, $value, false, max( 0, (int) $expire ) );
        return $this;
    }

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to append
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 )
    {
        return $this->set(
            $key,
            $this->get($key) . $value,
            max( 0, (int) $expire )
        );
    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        return $this->set(
            $key,
            $value . $this->get($key),
            max( 0, (int) $expire )
        );
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function increment ( $key )
    {
        $this->connect();
        $this->link->increment($key);
        return $this;
    }

    /**
     * Decrements a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function decrement ( $key )
    {
        $this->connect();
        $this->link->decrement($key);
        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function flush ()
    {
        $this->connect();
        $this->link->flush();
        return $this;
    }

}

?>