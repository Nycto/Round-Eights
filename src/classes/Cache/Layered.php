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
 * Provides functionality for layering multiple caches
 */
class Layered extends \r8\Cache\Base
{

    /**
     * The first cache to check
     *
     * @var \r8\iface\Cache
     */
    private $primary;

    /**
     * The second cache to check
     *
     * @var \r8\iface\Cache
     */
    private $secondary;

    /**
     * Constructor...
     *
     * @param \r8\iface\Cache $primary The first cache to check
     * @param \r8\iface\Cache $secondary The second cache to check
     */
    public function __construct ( \r8\iface\Cache $primary, \r8\iface\Cache $secondary )
    {
        $this->primary = $primary;
        $this->secondary = $secondary;
    }

    /**
     * Sets a new caching value, overwriting any existing values
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Local Returns a self reference
     */
    public function set ( $key, $value, $expire = 0 )
    {
        $this->primary->set( $key, $value, $expire );
        $this->secondary->set( $key, $value, $expire );
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
        $first = $this->primary->get( $key );

        if ( $first !== NULL )
            return $first;

        return $this->secondary->get( $key );
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Local Returns a self reference
     */
    public function delete ( $key )
    {
        $this->primary->delete( $key );
        $this->secondary->delete( $key );
        return $this;
    }

    /**
     * Sets a new caching value, but only if that value doesn't exist
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Local Returns a self reference
     */
    public function add ( $key, $value, $expire = 0 )
    {
        $this->primary->add( $key, $value, $expire );
        $this->secondary->add( $key, $value, $expire );
        return $this;
    }

    /**
     * Sets a new caching value, but only if the value already exists
     *
     * @param String $key The key for the value
     * @param mixed $value The value to set
     * @param Integer $expire The lifespan of this cache value, in seconds
     * @return \r8\Cache\Local Returns a self reference
     */
    public function replace ( $key, $value, $expire = 0 )
    {
        $this->primary->replace( $key, $value, $expire );
        $this->secondary->replace( $key, $value, $expire );
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
     * @return \r8\Cache\Local Returns a self reference
     */
    public function append ( $key, $value, $expire = 0 )
    {
        $this->primary->append( $key, $value, $expire );
        $this->secondary->append( $key, $value, $expire );
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
     * @return \r8\Cache\Local Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        $this->primary->prepend( $key, $value, $expire );
        $this->secondary->prepend( $key, $value, $expire );
        return $this;
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Local Returns a self reference
     */
    public function increment ( $key )
    {
        $this->primary->increment( $key );
        $this->secondary->increment( $key );
        return $this;
    }

    /**
     * Decrements a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Local Returns a self reference
     */
    public function decrement ( $key )
    {
        $this->primary->decrement( $key );
        $this->secondary->decrement( $key );
        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Local Returns a self reference
     */
    public function flush ()
    {
        $this->primary->flush();
        $this->secondary->flush();
        return $this;
    }

}

?>