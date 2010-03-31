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
 * Uses the APC shared memory interface as a cache
 */
class APC extends \r8\Cache\Base
{

    /**
     * Constructor...
     */
    public function __construct ()
    {
        if ( !extension_loaded('apc') )
            throw new \r8\Exception\Extension("apc", "Extension is not loaded");

        if ( php_sapi_name() == 'cli' && ini_get('apc.enable_cli') != 1 )
            throw new \r8\Exception\Config("apc.enable_cli", "CLI mode must be enabled");
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
        \apc_store(
            \r8\indexVal($key),
            \serialize($value),
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
        $success = NULL;
        $result = \apc_fetch( \r8\indexVal($key), $success );

        if ( !$result )
            return NULL;

        if ( $result == "b:0;" )
            return FALSE;

        $result = @\unserialize( $result );

        if ( $result === FALSE )
        {
            $this->delete( $key );
            return NULL;
        }

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
        \apc_delete( \r8\indexVal($key) );
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
        \apc_add(
            \r8\indexVal($key),
            \serialize($value),
            max(0, (int) $expire)
        );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * Due to limitations in the APC interface, this is not an atomic operation.
     * Instead, it is a two step process. The value is pulled from the cache,
     * updated and then saved. This means that race conditions can possibly
     * occur in the momemnt between the read and write.
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        if ( $this->get($key) !== NULL )
            $this->set( $key, $value, $expire );
        return $this;
    }

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * Due to limitations in the APC interface, this is not an atomic operation.
     * Instead, it is a two step process. The value is pulled from the cache,
     * updated and then saved. This means that race conditions can possibly
     * occur in the momemnt between the read and write.
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
            \r8\reduce( $this->get($key) ) . \r8\reduce($value),
            $expire
        );
    }

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * Due to limitations in the APC interface, this is not an atomic operation.
     * Instead, it is a two step process. The value is pulled from the cache,
     * updated and then saved. This means that race conditions can possibly
     * occur in the momemnt between the read and write.
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
            \r8\reduce($value) . \r8\reduce( $this->get($key) ),
            $expire
        );
    }

    /**
     * Increments a given value by one
     *
     * Due to limitations in the APC interface, this is not an atomic operation.
     * Instead, it is a two step process. The value is pulled from the cache,
     * updated and then saved. This means that race conditions can possibly
     * occur in the momemnt between the read and write.
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function increment ( $key )
    {
        $value = $this->get($key);
        return $this->set( $key, is_numeric($value) ? $value + 1 : 0 );
    }

    /**
     * Decrements a given value by one
     *
     * Due to limitations in the APC interface, this is not an atomic operation.
     * Instead, it is a two step process. The value is pulled from the cache,
     * updated and then saved. This means that race conditions can possibly
     * occur in the momemnt between the read and write.
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function decrement ( $key )
    {
        $value = $this->get($key);
        return $this->set( $key, is_numeric($value) ? $value - 1 : 0 );
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function flush ()
    {
        apc_clear_cache();
        return $this;
    }

}

?>