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
 * Reprsents a single value from a cache
 */
class Value
{

    /**
     * The key to this value
     *
     * @var String
     */
    private $key;

    /**
     * The cache to interact with
     *
     * @var \r8\iface\Cache
     */
    private $cache;

    /**
     * Constructor...
     *
     * @param String $key The key to this value
     * @param \r8\iface\Cache $cache The cache to interact with
     */
    public function __construct ( $key, \r8\iface\Cache $cache )
    {
        $this->key = \r8\indexVal( $key );
        $this->cache = $cache;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Value Returns a self reference
     */
    public function set ( $value, $expire = 0 )
    {
        $this->cache->set( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Returns a the raw value from the cache
     *
     * @return mixed Returns the cached value
     */
    public function get ()
    {
        return $this->cache->get( $this->key );
    }

    /**
     * Deletes this value from the cache
     *
     * @return \r8\Cache\Value Returns a self reference
     */
    public function delete ()
    {
        $this->cache->delete( $this->key );
        return $this;
    }

    /**
     * Sets a new value, but only if this key doesn't exist in the cache
     *
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Value Returns a self reference
     */
    public function add ( $value, $expire = 0 )
    {
        $this->cache->add( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Sets a new value, but only if the key already exists
     *
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Value Returns a self reference
     */
    public function replace ( $value, $expire = 0 )
    {
        $this->cache->replace( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Appends a string to the end of a this value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param mixed $value The value to append
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Value Returns a self reference
     */
    public function append ( $value, $expire = 0 )
    {
        $this->cache->append( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Prepends a value to the end of a this value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param mixed $value The value to prepend
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Value Returns a self reference
     */
    public function prepend ( $value, $expire = 0 )
    {
        $this->cache->prepend( $this->key, $value, $expire );
        return $this;
    }

    /**
     * Increments a this value by one
     *
     * @return \r8\Cache\Value Returns a self reference
     */
    public function increment ()
    {
        $this->cache->increment( $this->key );
        return $this;
    }

    /**
     * Decrements this value by one
     *
     * @return \r8\Cache\Value Returns a self reference
     */
    public function decrement ()
    {
        $this->cache->decrement( $this->key );
        return $this;
    }

}

?>