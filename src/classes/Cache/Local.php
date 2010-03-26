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
 * Uses an internal array to locally store cached values
 */
class Local extends \r8\Cache\Base
{

    /**
     * The hash table of values
     *
     * @var Array
     */
    private $cache = array();

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
        if ( $value === NULL )
            return $this->delete( $key );

        if ( is_object($value) )
            $value = clone $value;

        $this->cache[ \r8\indexVal( $key ) ] = array(
            "exp" => ( $expire == 0 ? 0 : time() + max(0, (int) $expire) ),
            "val" => $value
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
        $key = \r8\indexVal( $key );

        if ( !isset( $this->cache[ $key ] ) )
            return NULL;

        if ( $this->cache[$key]['exp'] <= time() && $this->cache[$key]['exp'] != 0 ) {
            $this->delete( $key );
            return NULL;
        }

        if ( is_object($this->cache[$key]['val']) )
            return clone $this->cache[$key]['val'];
        else
            return $this->cache[$key]['val'];
    }

    /**
     * Deletes a value from the cache
     *
     * @param String $key The value to delete
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function delete ( $key )
    {
        unset( $this->cache[ \r8\indexVal( $key ) ] );
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
        if ( !isset($this->cache[ \r8\indexVal( $key ) ]) )
            $this->set( $key, $value, $expire );
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
        if ( isset($this->cache[ \r8\indexVal( $key ) ]) )
            $this->set( $key, $value, $expire );
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
        $key = \r8\indexVal( $key );

        if ( !isset($this->cache[ $key ]) || !\r8\isBasic($this->cache[ $key ]['val']) )
            return $this->set( $key, $value, $expire );

        $this->cache[ $key ] = array(
            "exp" => ( $expire == 0 ? 0 : time() + max(0, (int) $expire) ),
            "val" => (string) $this->cache[ $key ]['val'] . $value
        );

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
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function prepend ( $key, $value, $expire = 0 )
    {
        $key = \r8\indexVal( $key );

        if ( !isset($this->cache[ $key ]) || !\r8\isBasic($this->cache[ $key ]['val']) )
            return $this->set( $key, $value, $expire );

        $this->cache[ $key ] = array(
            "exp" => ( $expire == 0 ? 0 : time() + max(0, (int) $expire) ),
            "val" => $value . (string) $this->cache[ $key ]['val']
        );

        return $this;
    }

    /**
     * Increments a given value by one
     *
     * @param String $key The key for the value
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function increment ( $key )
    {
        $key = \r8\indexVal( $key );

        if ( !isset($this->cache[ $key ]) || !\is_numeric($this->cache[ $key ]['val']) )
            $this->set( $key, 0, 0 );
        else
            $this->cache[ $key ]['val']++;

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
        $key = \r8\indexVal( $key );

        if ( !isset($this->cache[ $key ]) || !\is_numeric($this->cache[ $key ]['val']) )
            $this->set( $key, 0, 0 );
        else
            $this->cache[ $key ]['val']--;

        return $this;
    }

    /**
     * Deletes all values in the cache
     *
     * @return \r8\Cache\Memcache Returns a self reference
     */
    public function flush ()
    {
        $this->cache = array();
        return $this;
    }

}

?>