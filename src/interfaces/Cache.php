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

namespace r8\iface;

/**
 * Interface for a key/value caching system
 */
interface Cache
{

    /**
     * Returns a cached value based on it's key
     *
     * @param String $key The value to retrieve
     * @return mixed Returns the cached value
     */
    public function get ( $key );

    /**
     * Checks the cache for a value and returns it if it exists.
     * Otherwise, the callback is invoked. The return value is saved
     * to the cache and returned.
     *
     * @param String $key The value to retrieve
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @param Callable $callback The method to invoke if the key
     *      doesn't exist in the database
     * @return mixed Returns the cached value
     */
    public function yield ( $key, $expire, $callback );

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 );

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 );

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 );

    /**
     * Appends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to append
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 );

    /**
     * Prepends a value to the end of an existing cached value.
     *
     * If the value doesn't exist, it will be set with the given value
     *
     * @param String $key The key for the value
     * @param mixed $value The value to prepend
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\iface\Cache Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 );

    /**
     * Increments a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return \r8\iface\Cache Returns a self reference
     */
    public function increment ( $key );

    /**
     * Decrements a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return \r8\iface\Cache Returns a self reference
     */
    public function decrement ( $key );

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\iface\Cache Returns a self reference
     */
    public function delete ( $key );

    /**
     * Deletes all values in the cache
     *
     * @return \r8\iface\Cache Returns a self reference
     */
    public function flush ();

}

