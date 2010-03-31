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
 * Adapter for the Memcached extension to the standardized cache interface
 */
class Memcached extends \r8\Cache\Base implements \r8\iface\Cache\Updatable
{

    /**
     * The Memcached server object
     *
     * @var Memcached
     */
    private $memcached;

    /**
     * Formats the given data for storage on the server
     *
     * @param Mixed $input
     * @return String
     */
    static private function encode ( $value )
    {
        if ( $value === NULL || is_numeric($value) || is_string($value) )
            return $value;

        return serialize( $value );
    }

    /**
     * Constructor...
     *
     * @param \Memcached $memcached The Memcached server object
     */
    public function __construct ( \Memcached $memcached )
    {
        if ( !extension_loaded('memcached') )
            throw new \r8\Exception\Extension("memcached", "Extension is not loaded");

        $this->memcached = $memcached;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $this->memcached->set(
            $key,
            self::encode( $value ),
            max(0, (int) $expire)
        );
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
        $result =  $this->memcached->get( $key );

        if ( $result === FALSE )
            return NULL;

        else if ( is_numeric($result) )
            return \r8\numVal( $result );

        else if ( $result == "b:0;" )
            return FALSE;

        else if ( !is_string($result) )
            return $result;

        $unserialized = @unserialize( $result );

        if ( $unserialized === FALSE )
            return $result;

        return $unserialized;
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function delete ( $key )
    {
        $this->memcached->delete( $key );
        return $this;
    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 )
    {
        $this->memcached->add(
            $key,
            self::encode( $value ),
            max(0, (int) $expire)
        );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        $this->memcached->replace(
            $key,
            self::encode( $value ),
            max(0, (int) $expire)
        );
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
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 )
    {
        if ( !$this->memcached->append( $key, (string) $value ) )
            $this->set( $key, $value, $expire );
        return $this;
    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        if ( !$this->memcached->prepend( $key, (string) $value ) )
            $this->set( $key, $value, $expire );
        return $this;
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function increment ( $key )
    {
        $this->memcached->increment( $key );
        return $this;
    }

    /**
     * Decrements a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function decrement ( $key )
    {
        $this->memcached->decrement( $key );
        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function flush ()
    {
        $this->memcached->flush();
        return $this;
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
     * @return \r8\Cache\Result
     */
    public function getForUpdate ( $key )
    {
        $token = NULL;
        $value = $this->memcached->get( $key, NULL, $token );
        return new \r8\Cache\Result( $this, $key, $token, $value );
    }

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param \r8\Cache\Result $result A result object that was returned by
     *      the getForUpdate method
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function setIfSame ( \r8\Cache\Result $result, $value, $expire = 0 )
    {
        $this->memcached->cas(
            $result->getHash(),
            $result->getKey(),
            $value,
            $expire
        );

        return $this;
    }

}

?>