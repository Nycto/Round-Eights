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
     * The maximum cache time is 30 days
     */
    const MAX_EXPIRATION = 2592000;

    /**
     * The Memcached server object
     *
     * @var Memcached
     */
    private $memcached;

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
     * Checks and handles errors
     *
     * @return NULL
     */
    private function handleErrors ()
    {
        $result = $this->memcached->getResultCode();
        if (
            $result === \Memcached::RES_SUCCESS
            || $result === \Memcached::RES_NOTFOUND
            || $result === \Memcached::RES_NOTSTORED
            || $result === \Memcached::RES_DATA_EXISTS
        ) {
            return NULL;
        }

        $refl = new \ReflectionClass( '\Memcached' );
        $constant = \r8\ary\first( preg_grep(
            '/^RES_/',
            array_keys( array_filter(
                $refl->getConstants(),
                function ( $value ) use ($result) {
                    return $result == $value;
                }
            ) )
        ) );

        if ( empty($constant) )
            $constant = "UNKNOWN";

        throw new \r8\Exception\Cache( "Memcache Error: ". $constant, $result );
    }

    /**
     * Prepares a key to be saved in Memcached
     *
     * @param String $key The key to modify
     * @return String
     */
    private function prepareKey ( $key )
    {
        // Memcached has a 250 character limit on it's keys. Hashing will
        // compensate for that. It will also compensate for the character
        // restrictions imposed.
        return base_convert( sha1( (string) $key ), 16, 36 );
    }

    /**
     * Prepares an expiration to be used against a cache
     *
     * @param Integer $expire
     * @return Integer
     */
    private function prepareExpire ( $expire )
    {
        return $expire < 0 ? 1 : min( (int) $expire, self::MAX_EXPIRATION );
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
            $this->prepareKey($key),
            $value,
            $this->prepareExpire( $expire )
        );

        $this->handleErrors();

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
        $result =  $this->memcached->get( $this->prepareKey($key) );

        if (
            $result === FALSE
            && $this->memcached->getResultCode() === \Memcached::RES_NOTFOUND
        ) {
            return NULL;
        }
        else {
            $this->handleErrors();
        }

        return $result;
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Memcached Returns a self reference
     */
    public function delete ( $key )
    {
        $this->memcached->delete( $this->prepareKey($key) );

        $this->handleErrors();

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
            $this->prepareKey($key),
            $value,
            $this->prepareExpire( $expire )
        );

        $this->handleErrors();

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
            $this->prepareKey($key),
            $value,
            max(0, (int) $expire)
        );

        $this->handleErrors();

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
        if ( !$this->memcached->append( $this->prepareKey($key), (string) $value ) )
            $this->set( $key, $value, $expire );

        $this->handleErrors();

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
        if ( !$this->memcached->prepend( $this->prepareKey($key), (string) $value ) )
            $this->set( $key, $value, $expire );

        $this->handleErrors();

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
        $this->memcached->increment( $this->prepareKey($key) );

        $this->handleErrors();

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
        $this->memcached->decrement( $this->prepareKey($key) );

        $this->handleErrors();

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

        $this->handleErrors();

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
        $value = $this->memcached->get( $this->prepareKey($key), NULL, $token );

        $this->handleErrors();

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
            $this->prepareKey( $result->getKey() ),
            $value,
            $this->prepareExpire( $expire )
        );

        $this->handleErrors();

        return $this;
    }

}

?>