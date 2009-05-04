<?php
/**
 * Hash table caching interface
 *
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

namespace cPHP\iface;

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
    public function getForUpdate ( $key );

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return Object Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 );

    /**
     * Sets the value for this key only if the value hasn't changed in the cache
     * since it was originally pulled
     *
     * @param cPHP\Cache\Result $result A result object that was returned by
     *      the getForUpdate method
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return Object Returns a self reference
     */
    public function setIfSame ( \cPHP\Cache\Result $result, $value, $expire = 0 );

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return Object Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 );

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
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
     * @return Object Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 );

    /**
     * Increments a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function increment ( $key );

    /**
     * Decrements a given value by one
     *
     * If a given value isn't numeric, it will be treated as 0
     *
     * @param String $key The key for the value
     * @return Object Returns a self reference
     */
    public function decrement ( $key );

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return Object Returns a self reference
     */
    public function delete ( $key );

    /**
     * Deletes all values in the cache
     *
     * @return Object Returns a self reference
     */
    public function flush ();

}

?>